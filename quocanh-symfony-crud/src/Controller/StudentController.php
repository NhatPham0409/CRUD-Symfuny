<?php

namespace App\Controller;

use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Student;

#[Route('/api/v1', name: 'api_')]
class StudentController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/student', name: 'student_create', methods:['POST'])]
    public function create(ManagerRegistry $doctrine, Request $raw) : JsonResponse
    {
        #Sửa chỗ nàyyyyyyyy
        #Sửa chỗ nàyyyyyyyy
        #Sửa chỗ nàyyyyyyyy
        #Sửa chỗ nàyyyyyyyy
        #Sửa chỗ nàyyyyyyyy
        $request = json_decode($raw->getContent(), true)['first_name'];
        echo $request;

        if (!$request->get('first_name') || !$request->get('last_name') || !$request->get('dob') ||
            !$request->get('phone') || !$request->get('email') || !$request->get("address") || !$request->get("sex"))
        {
            return new JsonResponse(['error' => 'All fields are required'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();

        $student = new Student();
        $student->setFirstName($request->request->get('first_name'));
        $student->setLastName($request->request->get('last_name'));
        $student->setDob(new DateTime($request->request->get('dob')));
        $student->setSex($request->request->get('sex'));
        $student->setAddress($request->request->get('address'));
        $student->setPhone($request->request->get('phone'));
        $student->setEmail($request->request->get('email'));

        $entityManager->persist($student);
        $entityManager->flush();

        $data = [
            'id' => $student->getId(),
            'first_name' => $student->getFirstName(),
            'last_name' => $student->getLastName(),
            'dob' => $student->getDob()->format('d-m-Y'),
            'phone' => $student->getPhone(),
            'email' => $student->getEmail(),
        ];

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/student', name: 'student_list', methods:['GET'])]
    public function index(ManagerRegistry $doctrine) : JsonResponse
    {
        $students = $doctrine
            ->getRepository(Student::class)
            ->findAll();

        if (!$students) {
            return new JsonResponse(['error' => 'No students found'], Response::HTTP_NOT_FOUND);
        }

        $data = [];

        foreach($students as $student) {
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

    #[Route('/student/{id}', name: 'student_get', methods:['GET'])]
    public function getOne(ManagerRegistry $doctrine, int $id) : JsonResponse
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
    #[Route('/student/{id}', name: 'student_update', methods:['PUT', 'PATCH'])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id) : JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        if (!$request->get('first_name') || !$request->get('last_name') || !$request->get('dob') ||
            !$request->get('phone') || !$request->get('email') || !$request->get('sex') || !$request->get('address'))
        {

            return new JsonResponse(['error' => 'All fields are required'], Response::HTTP_BAD_REQUEST);
        }

        $student->setFirstName($request->request->get('first_name'));
        $student->setLastName($request->request->get('last_name'));
        $student->setSex($request->request->get('sex'));
        $student->setDob(new DateTime($request->request->get('dob')));
        $student->setPhone($request->request->get('phone'));
        $student->setEmail($request->request->get('email'));
        $student->setAddress($request->request->get('address'));

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

    #[Route('/student/{id}', name: 'student_delete', methods:['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id) : JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return new JsonResponse(['error' => 'No student found for id: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($student);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Student with id ' .$id .'has been deleted'], Response::HTTP_NO_CONTENT);
    }
}