<?php

namespace App\Service\Impl;

use App\Entity\Student;
use App\Service\IStudentService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StudentServiceImpl implements IStudentService
{
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
        $student->setDob(new \DateTime($request['dob'] ?? null));
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

        return new JsonResponse($student->toArray(), Response::HTTP_CREATED);
    }

    public function getAllStudents(ManagerRegistry $doctrine): JsonResponse
    {
        //Get all students from the database (refer to the Student entity and call the findAll() method)
        $studentList = $doctrine
            ->getRepository(Student::class)
            ->findAll();

        if (!$studentList) {
            return new JsonResponse(['error' => 'No students found'], Response::HTTP_NOT_FOUND);
        }

        $data = [];

        //Convert the list of student objects to an array of associative arrays
        foreach ($studentList as $student) {
            $data[] = $student->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getStudentById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        //Get the student from the database based on the id (refer to the Student entity and call the find() method)
        $student = $doctrine->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($student->toArray(), Response::HTTP_OK);
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
            }
            else if ($key == 'email' && $value != null && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return new JsonResponse(['error' => 'Invalid email'], Response::HTTP_BAD_REQUEST);
            }
            if (array_key_exists($key, $fieldSetterMap) && $value != null) {
                $setter = $fieldSetterMap[$key];
                if (method_exists($student, $setter)) {
                    $student->$setter($value);
                }
            }
        }

        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArray(), Response::HTTP_OK);
    }

    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
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

    private function isValidDateFormat(string $dateString, string $format): bool {
        $date = DateTime::createFromFormat($format, $dateString);
        return $date && $date->format($format) === $dateString;
    }
}