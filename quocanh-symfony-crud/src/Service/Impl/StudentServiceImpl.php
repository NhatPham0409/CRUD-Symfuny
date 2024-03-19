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
    private string $DATE_FORMAT = 'Y-m-d';

    private function isValidDateFormat(string $dateString, string $format): bool
    {
        $date = DateTime::createFromFormat($format, $dateString);
        return $date && $date->format($format) === $dateString;
    }

    private function paginationHelper($query, $perPage, $page): array
    {
        $offset = ($page - 1) * $perPage;
        $paginator = new Paginator($query);
        $studentList = $paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getResult();
        $totalItems = $paginator->count();
        $totalPages = (int)ceil($totalItems / $perPage);

        $students = [];
        // Convert the list of student objects to an array of associative arrays
        foreach ($studentList as $student) {
            $students[] = $student->toArrayForStudent();
        }

        return [
            'students' => $students,
            'pagination' => [
                'current_page' => $page,
                'total_students' => $totalItems,
                'total_pages' => $totalPages,
            ]
        ];
    }

    //CRUD operations for student

    /**
     * @throws Exception
     */
    public function createStudent(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse
    {
        // Get the request data and convert to an array
        $request = json_decode($raw->getContent(), true);

        if (empty($request)) {
            return new JsonResponse(['error' => 'No data found in the request'], Response::HTTP_BAD_REQUEST);
        }

        // Validate the date of birth format (cannot use Assert\Date in the entity class??)
        if (!$this->isValidDateFormat($request['dob'], $this->DATE_FORMAT)) {
            return new JsonResponse(['error' => 'Invalid date of birth'], Response::HTTP_BAD_REQUEST);
        }

        // Get the entity manager to interact with database
        $entityManager = $doctrine->getManager();

        $student = new Student();
        $student->setFirstName($request['first_name'] ?? null);
        $student->setLastName($request['last_name'] ?? null);
        $student->setPhone($request['phone'] ?? null);
        $student->setDob(new DateTime($request['dob'] ?? null));
        $student->setEmail($request['email'] ?? null);
        $student->setGender($request['gender'] ?? null);
        $student->setAddress($request['address'] ?? null);

        // Validate the student object before storing it in DB
        $errors = $validator->validate($student);

        if (count($errors) > 0) {
            // Return the array of validation errors as a JSON response
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // Store the student object in the database
        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_CREATED);
    }

    public function getAllStudents(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // Get the limit and page query parameters from the request
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;

        // Get all students from the database (refer to the Student entity and call the findAllStudentPagination() method)
        $query = $doctrine->getRepository(Student::class)->findAllStudentPagination();

        $data = $this->paginationHelper($query, $perPage, $page);

        if (empty($data['students'])) {
            return new JsonResponse(['error' => 'No student found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getStudentInfoById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        // Get the student from the database based on the id (refer to the Student entity and call the find() method)
        $student = $doctrine->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_OK);
    }

    public function getStudentClassInfo(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $student = $doctrine->getRepository(Student::class)->find($id);

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
            return new JsonResponse(['error' => 'No data found in the request'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        // Get the fieldSetterMap from the Student entity
        $fieldSetterMap = Student::fieldSetterMap();

        // Validate and update student properties based on JSON data
        foreach ($request as $key => $value) {
            // Validate the date of birth format (cannot use Assert\Date in the entity class??)
            if ($key == 'dob' && $value != null && !$this->isValidDateFormat($value, $this->DATE_FORMAT)) {
                return new JsonResponse(['error' => 'Invalid date of birth'], Response::HTTP_BAD_REQUEST);
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

    private function studentEnrollOrUnenrollClassHelper(ManagerRegistry $doctrine, int $studentId, int $classId, string $action): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($studentId);
        $classRoom = $entityManager->getRepository(ClassRoom::class)->find($classId);

        if (!$student || !$classRoom) {
            $errorMessage = (!$student) ? 'No student found for id: ' . $studentId : 'No class found for id: ' . $classId;
            return new JsonResponse(['error' => $errorMessage], Response::HTTP_NOT_FOUND);
        }

        if ($action == 'enroll') {
            if ($student->getClassList()->contains($classRoom)) {
                return new JsonResponse(['error' => 'The student already enrolled in ' . $classRoom->getClassName()], Response::HTTP_BAD_REQUEST);
            }

            $student = $student->addClassList($classRoom);
        } else {
            if (!$student->getClassList()->contains($classRoom)) {
                return new JsonResponse(['error' => 'The student not enrolled in ' . $classRoom->getClassName()], Response::HTTP_BAD_REQUEST);
            }

            $student = $student->removeClassList($classRoom);
        }

        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArrayForStudent(), Response::HTTP_OK);
    }

    public function enrollStudent(ManagerRegistry $doctrine, int $studentId, int $classId): JsonResponse
    {
        return $this->studentEnrollOrUnenrollClassHelper($doctrine, $studentId, $classId, 'enroll');
    }

    public function unenrollStudent(ManagerRegistry $doctrine, int $studentId, int $classId): JsonResponse
    {
        return $this->studentEnrollOrUnenrollClassHelper($doctrine, $studentId, $classId, 'unenroll');
    }

    public function deleteStudent(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $student_name = $student->getFirstName() . ' ' . $student->getLastName();

        $entityManager->remove($student);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Student ' . $student_name . ' has been deleted'], Response::HTTP_NO_CONTENT);
    }

    //Search operations for student
    public function findStudentByFields(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;

        $request->query->remove('limit');
        $request->query->remove('page');

        $criteria = $request->query->all();

        $studentRepository = $doctrine->getRepository(Student::class);
        $query = $studentRepository->findStudentByFields($criteria);

        $data = $this->paginationHelper($query, $perPage, $page);

        if (empty($data['students'])) {
            return new JsonResponse(['error' => 'No student found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}