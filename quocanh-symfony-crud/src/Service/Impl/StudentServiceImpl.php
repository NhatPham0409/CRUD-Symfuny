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
    public static function createStudent(ManagerRegistry $doctrine, Request $raw): JsonResponse
    {
        $request = json_decode($raw->getContent(), true);

        if (!$request['first_name'] || !$request['last_name'] || !$request['dob'] ||
            !$request['phone'] || !$request['email'] || !$request['address'] || !$request['sex']) {
            return new JsonResponse(['error' => 'All fields are required'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();

        $student = new Student();
        $student->setFirstName($request['first_name']);
        $student->setLastName($request['last_name']);
        $student->setDob(new DateTime($request['dob']));
        $student->setSex($request['sex']);
        $student->setAddress($request['address']);
        $student->setPhone($request['phone']);
        $student->setEmail($request['email']);

        $entityManager->persist($student);
        $entityManager->flush();

        $data = [
            'id' => $student->getId(),
            'first_name' => $student->getFirstName(),
            'last_name' => $student->getLastName(),
            'dob' => $student->getDob()->format('d-m-Y'),
            'sex' => $student->getSex(),
            'phone' => $student->getPhone(),
            'email' => $student->getEmail(),
            'address' => $student->getAddress(),
        ];

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    public static function getAllStudents(ManagerRegistry $doctrine): JsonResponse
    {
        $students = $doctrine
            ->getRepository(Student::class)
            ->findAll();

        if (!$students) {
            return new JsonResponse(['error' => 'No students found'], Response::HTTP_NOT_FOUND);
        }

        $data = [];

        foreach ($students as $student) {
            $data[] = [
                'id' => $student->getId(),
                'first_name' => $student->getFirstName(),
                'last_name' => $student->getLastName(),
                'dob' => $student->getDob()->format('d-m-Y'),
                'sex' => $student->getSex(),
                'address' => $student->getAddress(),
                'phone' => $student->getPhone(),
                'email' => $student->getEmail(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getStudentById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $student = $doctrine->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $student->getId(),
            'first_name' => $student->getFirstName(),
            'last_name' => $student->getLastName(),
            'dob' => $student->getDob()->format('d-m-Y'),
            'sex' => $student->getSex(),
            'address' => $student->getAddress(),
            'phone' => $student->getPhone(),
            'email' => $student->getEmail(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @throws Exception
     */
    public static function updateStudentInfo(ManagerRegistry $doctrine, int $id, Request $raw): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $request = json_decode($raw->getContent(), true);

        foreach (['first_name', 'last_name', 'dob', 'sex', 'address', 'phone', 'email'] as $property) {
            if (isset($request[$property])) {
                switch ($property) {
                    case 'dob':
                        $student->setDob(new DateTime($request['dob']));
                        break;
                    default:
                        $methodName = 'set' . ucfirst($property);
                        $student->$methodName($request[$property]);
                        break;
                }
            }
        }

        $entityManager->flush();

        $data = [
            'id' => $student->getId(),
            'first_name' => $student->getFirstName(),
            'last_name' => $student->getLastName(),
            'dob' => $student->getDob()->format('d-m-Y'),
            'sex' => $student->getSex(),
            'address' => $student->getAddress(),
            'phone' => $student->getPhone(),
            'email' => $student->getEmail(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public static function delete(ManagerRegistry $doctrine, int $id): JsonResponse
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