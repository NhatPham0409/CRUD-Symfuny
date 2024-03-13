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

class StudentServiceImpl implements IStudentService
{
    /**
     * @throws Exception
     */
    public function createStudent(ManagerRegistry $doctrine, Request $raw): JsonResponse
    {

        //Get the request data and convert to an array
        $request = json_decode($raw->getContent(), true);

        if (empty($request)) {
            return new JsonResponse(['error' => 'No data found in the request.'], Response::HTTP_BAD_REQUEST);
        }

        if (!$request['first_name'] || !$request['last_name'] || !$request['dob'] ||
            !$request['phone'] || !$request['email'] || !$request['address'] || !$request['gender']) {
            return new JsonResponse(['error' => 'Missing required fields for a student'], Response::HTTP_BAD_REQUEST);
        }

        //Get EntityManager to interact with DB
        $entityManager = $doctrine->getManager();

        $student = new Student();
        $student->setFirstName($request['first_name']);
        $student->setLastName($request['last_name']);
        $student->setDob(new DateTime($request['dob']));
        $student->setGender($request['gender']);
        $student->setAddress($request['address']);
        $student->setPhone($request['phone']);
        $student->setEmail($request['email']);

        //Store the student object in DB
        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse($student->toArray(), Response::HTTP_CREATED);
    }

    public function getAllStudents(ManagerRegistry $doctrine): JsonResponse
    {
        //Get all students from the DB (get a reference to the StudentReposìtory and call the findAll() method)
        $studentList = $doctrine
            ->getRepository(Student::class)
            ->findAll();

        if (!$studentList) {
            return new JsonResponse(['error' => 'No students found'], Response::HTTP_NOT_FOUND);
        }

        $data = [];

        //Loop through the list of students and store the data in an array
        foreach ($studentList as $student) {
            $data[] = $student->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getStudentById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        //Get one student from the DB by ID (get a reference to the StudentReposìtory and call the find($id) method)
        $student = $doctrine->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($student->toArray(), Response::HTTP_OK);
    }

    /**
     * @throws Exception
     */
    public function updateStudentInfo(ManagerRegistry $doctrine, int $id, Request $raw): JsonResponse
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

        //Loop through the properties list and update the student object
        foreach (['first_name', 'last_name', 'dob', 'gender', 'address', 'phone', 'email'] as $property) {
            if (isset($request[$property])) {
                switch ($property) {
                    case 'dob':
                        $student->setDob(new DateTime($request['dob']));
                        break;
                    case 'first_name':
                        $student->setFirstName($request['first_name']);
                        break;
                    case 'last_name':
                        $student->setLastName($request['last_name']);
                        break;
                    default:
                        $methodName = 'set' . ucfirst($property);
                        $student->$methodName($request[$property]);
                        break;
                }
            }
        }

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
}