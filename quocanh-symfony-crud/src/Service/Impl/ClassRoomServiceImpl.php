<?php

namespace App\Service\Impl;

use App\Entity\ClassRoom;
use App\Entity\Student;
use App\Service\IClassRoomService;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class ClassRoomServiceImpl implements IClassRoomService
{
    public function createClassRoom(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse
    {
        $request = json_decode($raw->getContent(), true);

        if (empty($request)) {
            return new JsonResponse(['error' => 'No data found in the request.'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();

        $classRoom = new ClassRoom();
        $classRoom->setClassName($request['room_name'] ?? null);
        $classRoom->setTeacherName($request['teacher_name'] ?? null);

        $errors = $validator->validate($classRoom);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($classRoom);
        $entityManager->flush();

        return new JsonResponse($classRoom->toArrayForClass(), Response::HTTP_CREATED);
    }

    public function getAllClassRooms(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $query = $doctrine->getRepository(ClassRoom::class)->findAllClassPagination();
        $paginator = new Paginator($query);
        $classList = $paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getResult();
        $totalItems = $paginator->count();
        $totalPages = (int)ceil($totalItems / $perPage);

        if (!$classList) {
            return new JsonResponse(['error' => 'No class room found.'], Response::HTTP_NOT_FOUND);
        }

        $classes = [];
        foreach ($classList as $classRoom) {
            $classes[] = $classRoom->toArrayForClass();
        }

        $data = [
            'data' => $classes,
            'meta' => [
                'current_page' => $page,
                'total_classes' => $totalItems,
                'total_pages' => $totalPages,

            ]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getClassById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $classRoom = $doctrine->getRepository(ClassRoom::class)->find($id);

        if (!$classRoom) {
            return new JsonResponse(['error' => 'No class room found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($classRoom->toArrayForClass(), Response::HTTP_OK);
    }

    public function updateClassInfo(ManagerRegistry $doctrine, int $id, Request $raw, ValidatorInterface $validator): JsonResponse
    {
        $request = json_decode($raw->getContent(), true);

        if (empty($request)) {
            return new JsonResponse(['error' => 'No data found in the request.'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($id);

        if (!$classRoom) {
            return new JsonResponse(['error' => 'No class room found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $fieldSetterMap = ClassRoom::fieldSetterMap();

        foreach ($request as $key => $value) {
            if (array_key_exists($key, $fieldSetterMap) && $value != null) {
                $setter = $fieldSetterMap[$key];

                if (method_exists($classRoom, $setter)) {
                    $classRoom->$setter($value);
                }
            }
        }

        $entityManager->persist($classRoom);
        $entityManager->flush();

        return new JsonResponse($classRoom->toArrayForClass(), Response::HTTP_OK);
    }

    public function addStudent(ManagerRegistry $doctrine, int $classId, int $studentId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($classId);
        $student = $entityManager->getRepository(Student::class)->find($studentId);

        if (!$student || !$classRoom) {
            $errorMessage = (!$student) ? 'No student found for id: ' . $studentId : 'No class found for id: ' . $classId;
            return new JsonResponse(['error' => $errorMessage], Response::HTTP_NOT_FOUND);
        }

        if ($classRoom->getStudentList()->contains($student)) {
            return new JsonResponse(['error' => 'Student already enrolled in the class'], Response::HTTP_BAD_REQUEST);
        }

        $classRoom->addStudent($student);
        $entityManager->persist($classRoom);
        $entityManager->flush();

        return new JsonResponse($classRoom->toArrayForClass(), Response::HTTP_OK);
    }

    public function removeStudent(ManagerRegistry $doctrine, int $classId, int $studentId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($classId);
        $student = $entityManager->getRepository(Student::class)->find($studentId);

        if (!$student || !$classRoom) {
            $errorMessage = (!$student) ? 'No student found for id: ' . $studentId : 'No class found for id: ' . $classId;
            return new JsonResponse(['error' => $errorMessage], Response::HTTP_NOT_FOUND);
        }

        if (!$classRoom->getStudentList()->contains($student)) {
            return new JsonResponse(['error' => 'Student not enrolled in the class'], Response::HTTP_BAD_REQUEST);
        }

        $classRoom->removeStudent($student);
        $entityManager->persist($classRoom);
        $entityManager->flush();

        return new JsonResponse($classRoom->toArrayForClass(), Response::HTTP_OK);
    }

    public function deleteClass(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($id);

        if (!$classRoom) {
            return new JsonResponse(['error' => 'No class room found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($classRoom);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Class room deleted successfully.'], Response::HTTP_OK);
    }

    public function findClassByFields(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $request->query->remove('limit');
        $request->query->remove('page');

        $criteria = $request->query->all();

        $entityManager = $doctrine->getManager();
        $classRepository = $doctrine->getRepository(ClassRoom::class);
        $classList = $classRepository->findClassByFields($criteria);

        $paginator = new Paginator($classList);
        $classList = $paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getResult();

        $totalItems = count($paginator);
        $totalPages = (int)ceil($totalItems / $perPage);

        if (!$classList) {
            return new JsonResponse(['error' => 'No class room found.'], Response::HTTP_NOT_FOUND);
        }

        $classes = [];
        foreach ($classList as $classRoom) {
            $classes[] = $classRoom->toArrayForClass();
        }

        $data = [
            'data' => $classes,
            'meta' => [
                'current_page' => $page,
                'total_classes' => $totalItems,
                'total_pages' => $totalPages,
            ]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}