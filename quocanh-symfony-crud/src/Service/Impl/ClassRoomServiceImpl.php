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

class ClassRoomServiceImpl implements IClassRoomService
{
    private function paginationHelper($query, $perPage, $page): array
    {
        $offset = ($page - 1) * $perPage;
        $paginator = new Paginator($query);
        $classList = $paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getResult();
        $totalItems = $paginator->count();
        $totalPages = (int)ceil($totalItems / $perPage);

        $classes = [];
        foreach ($classList as $classRoom) {
            $classes[] = $classRoom->toArrayForClass();
        }

        return [
            'classes' => $classes,
            'pagination' => [
                'current_page' => $page,
                'total_classes' => $totalItems,
                'total_pages' => $totalPages,
            ]
        ];
    }

    public function createClassRoom(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse
    {
        $request = json_decode($raw->getContent(), true);

        if (empty($request)) {
            return new JsonResponse(['error' => 'No data found in the request'], Response::HTTP_BAD_REQUEST);
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

        $query = $doctrine->getRepository(ClassRoom::class)->findAllClassPagination();

        $data = $this->paginationHelper($query, $perPage, $page);

        if (empty($data['classes'])) {
            return new JsonResponse(['error' => 'No class room found'], Response::HTTP_NOT_FOUND);
        }

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
            return new JsonResponse(['error' => 'No data found in the request'], Response::HTTP_BAD_REQUEST);
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

    private function classAddOrRemoveStudentHelper(ManagerRegistry $doctrine, int $classId, int $studentId, string $action): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($classId);
        $student = $entityManager->getRepository(Student::class)->find($studentId);

        if (!$student || !$classRoom) {
            $errorMessage = (!$student) ? 'No student found for id: ' . $studentId : 'No class found for id: ' . $classId;
            return new JsonResponse(['error' => $errorMessage], Response::HTTP_NOT_FOUND);
        }

        if ($action === 'add') {
            if ($classRoom->getStudentList()->contains($student)) {
                return new JsonResponse(['error' => 'The student already enrolled in the class'], Response::HTTP_BAD_REQUEST);
            }

            $classRoom = $classRoom->addStudent($student);
        }
        else {
            if (!$classRoom->getStudentList()->contains($student)) {
                return new JsonResponse(['error' => 'The student not enrolled in the class'], Response::HTTP_BAD_REQUEST);
            }

            $classRoom = $classRoom->removeStudent($student);
        }

        $entityManager->persist($classRoom);
        $entityManager->flush();

        return new JsonResponse($classRoom->toArrayForClass(), Response::HTTP_OK);
    }

    public function addStudent(ManagerRegistry $doctrine, int $classId, int $studentId): JsonResponse
    {
        return $this->classAddOrRemoveStudentHelper($doctrine, $classId, $studentId, 'add');
    }

    public function removeStudent(ManagerRegistry $doctrine, int $classId, int $studentId): JsonResponse
    {
        return $this->classAddOrRemoveStudentHelper($doctrine, $classId, $studentId, 'remove');
    }

    public function deleteClass(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($id);

        if (!$classRoom) {
            return new JsonResponse(['error' => 'No class room found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $class_name = $classRoom->getClassName();
        $entityManager->remove($classRoom);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Class ' . $class_name . ' has been deleted'], Response::HTTP_OK);
    }

    public function findClassByFields(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;

        $request->query->remove('limit');
        $request->query->remove('page');

        $criteria = $request->query->all();

        $classRepository = $doctrine->getRepository(ClassRoom::class);
        $query = $classRepository->findClassByFields($criteria);

        $data = $this->paginationHelper($query, $perPage, $page);

        if (empty($data['classes'])) {
            return new JsonResponse(['error' => 'No class room found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}