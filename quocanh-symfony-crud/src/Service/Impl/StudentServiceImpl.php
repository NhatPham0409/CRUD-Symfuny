<?php

namespace App\Service\Impl;

use App\Entity\ClassRoom;
use App\Entity\Student;
use App\Service\IStudentService;
use DateTime;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StudentServiceImpl implements IStudentService
{
    private function isValidDateFormat(string $dateString, string $format): bool
    {
        $date = DateTime::createFromFormat($format, $dateString);
        return $date && $date->format($format) === $dateString;
    }

    //CRUD operations for student

    /**
     * @throws Exception
     */
    public function createStudent(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse
    {
        //Get the request data and convert to an array
        $request = json_decode($raw->getContent(), true);

        if (empty($request)) {
            return new JsonResponse(['error' => 'No data found in the request.'], Response::HTTP_BAD_REQUEST);
        }
        if (!$this->isValidDateFormat($request['dob'], 'Y-m-d')) {
            return new JsonResponse(['error' => 'Invalid date of birth'], Response::HTTP_BAD_REQUEST);
        }

        //Get the entity manager to interact with database
        $entityManager = $doctrine->getManager();

        $student = new Student();
        $student->setFirstName($request['first_name'] ?? null);
        $student->setLastName($request['last_name'] ?? null);
        $student->setPhone($request['phone'] ?? null);
        $student->setDob(new DateTime($request['dob'] ?? null));
        $student->setEmail($request['email'] ?? null);
        $student->setGender($request['gender'] ?? null);
        $student->setAddress($request['address'] ?? null);

        //Validate the student object before storing it in DB
        $errors = $validator->validate($student);

        if (count($errors) > 0) {
            //Return the array of validation errors as a JSON response
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            // Trả về response với thông báo lỗi
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        //lưu trữ dữ liệu vào DB
        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_CREATED);
    }

    public function getAllStudents(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;
        $offset = ($page - 1) * $perPage;
        //Get all students from the database (refer to the Student entity and call the findAll() method)
        $query = $doctrine->getRepository(Student::class)->findAllStudentPagination();
        $paginator = new Paginator($query);
        $studentList = $paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getResult();
        $totalItems = $paginator->count();
        $totalPages = (int)ceil($totalItems / $perPage);

        if (!$studentList) {
            return new JsonResponse(['error' => 'No student found'], Response::HTTP_NOT_FOUND);
        }

        $students = [];
        //Convert the list of student objects to an array of associative arrays
        foreach ($studentList as $student) {
            $students[] = $student->toArrayForStudent();
        }

        $data = [
            'students' => $students,
            'pagnition' => [
                'current_page' => $page,
                'total_students' => $totalItems,
                'total_pages' => $totalPages,
            ]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getStudentInfoById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        //Get the student from the database based on the id (refer to the Student entity and call the find() method)
        $student = $doctrine->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_OK);
    }

    public function getStudentClassInfo(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $student = $doctrine
            ->getRepository(Student::class)
            ->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $classList = $student->getClassList();

        if ($classList->isEmpty()) {
            return new JsonResponse(['error' => 'No class found for student id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($classList as $classRoom) {
            $data[] = $classRoom->toArrayForStudent();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @throws Exception
     */
    public function updateStudentInfo(ManagerRegistry $doctrine, int $id, Request $raw, ValidatorInterface $validator): JsonResponse
    {
        $request = json_decode($raw->getContent(), true);

        if (empty($request)) {
            return new JsonResponse(['error' => 'No data found in the request.'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $fieldSetterMap = Student::fieldSetterMap();

        // Validate and update student properties based on JSON data
        foreach ($request as $key => $value) {
            if ($key == 'dob' && $value != null && !$this->isValidDateFormat($value, 'Y-m-d')) {
                return new JsonResponse(['error' => 'Invalid date of birth'], Response::HTTP_BAD_REQUEST);
            } else if ($key == 'email' && $value != null && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return new JsonResponse(['error' => 'Invalid email'], Response::HTTP_BAD_REQUEST);
            }
            if (array_key_exists($key, $fieldSetterMap) && $value != null) {
                $setter = $fieldSetterMap[$key];

                if (method_exists($student, $setter)) {
                    $student->$setter($value);
                }
            }
        }

        $errors = $validator->validate($student);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_OK);
    }

    public function enrollStudent(ManagerRegistry $doctrine, int $studentId, int $classId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($studentId);
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($classId);

        if (!$student || !$classRoom) {
            $errorMessage = (!$student) ? 'No student found for id: ' . $studentId : 'No class found for id: ' . $classId;
            return new JsonResponse(['error' => $errorMessage], Response::HTTP_NOT_FOUND);
        }

        if ($student->getClassList()->contains($classRoom)) {
            return new JsonResponse(['error' => 'Student already enrolled in ' . $classRoom->getClassName()], Response::HTTP_BAD_REQUEST);
        }

        $student->addClassList($classRoom);
        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_OK);
    }

    public function unenrollStudent(ManagerRegistry $doctrine, int $studentId, int $classId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($studentId);
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($classId);

        if (!$student || !$classRoom) {
            $errorMessage = (!$student) ? 'No student found for id: ' . $studentId : 'No class found for id: ' . $classId;
            return new JsonResponse(['error' => $errorMessage], Response::HTTP_NOT_FOUND);
        }

        if (!$student->getClassList()->contains($classRoom)) {
            return new JsonResponse(['error' => 'Student not enrolled in ' . $classRoom->getClassName()], Response::HTTP_BAD_REQUEST);
        }

        $student->removeClassList($classRoom);
        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_OK);
    }

    public function deleteStudent(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($student);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Student with id ' . $id . 'has been deleted'], Response::HTTP_NO_CONTENT);
    }

    //Search operations for student
    public function findStudentByFields(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $request->query->remove('limit');
        $request->query->remove('page');

        $criteria = $request->query->all();

        $entityManager = $doctrine->getManager();
        $studentRepository = $entityManager->getRepository(Student::class);
        $qb = $studentRepository->findStudentByFields($criteria);

        $paginator = new Paginator($qb, fetchJoinCollection: false);
        $studentList = $paginator->getQuery()
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getResult();
        $totalItems = $paginator->count();
        $totalPages = (int)ceil($totalItems / $perPage);

        if (!$studentList) {
            return new JsonResponse(['error' => 'No student found'], Response::HTTP_NOT_FOUND);
        }

        $students = [];
        //Convert the list of student objects to an array of associative arrays
        foreach ($studentList as $student) {
            $students[] = $student->toArrayForStudent();
        }

        $data = [
            'students' => $students,
            'pagnition' => [
                'current_page' => $page,
                'total_students' => $totalItems,
                'total_pages' => $totalPages,
            ]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}