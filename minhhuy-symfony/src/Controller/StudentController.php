<?php

namespace App\Controller;

use DateTime;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api',name: 'api_student')]
class StudentController extends AbstractController
{
    #[Route('/get/students',name: 'get_students',methods: ['GET'])]
    public function getStudent(ManagerRegistry $doctrine) :JsonResponse
    {
        $students = $doctrine->getRepository(Student::class)->findAll();

        $data = array_map(function ($student){
            return [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'phone' => $student->getPhone(),
                'address' =>$student->getAddress(),
                'email' =>$student->getEmail(),
                'dob' => $student->getDoB(),
                'gender' => $student->getGender(),
            ];
        },$students);

        return $this->json($data);
    }


    #[Route('/create/student',name: 'create_student',methods: ['POST'])]
    public function createStudent(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), true);
        $student = new Student();
        $student->setName($requestBody['name']);
        $student->setPhone($requestBody['phone']);
        $student->setAddress($requestBody['address']);
        $student->setEmail($requestBody['email']);
        $student->setDoB(new DateTime($requestBody['dob']));
        $student->setGender($requestBody['gender']);

        $entityManager->persist($student);
        $entityManager->flush();

        $data = [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'phone' => $student->getPhone(),
            'address' =>$student->getAddress(),
            'email' =>$student->getEmail(),
            'dob' => $student->getDoB(),
            'gender' => $student->getGender(),
        ];
        return $this->json($data);
    }

    #[Route('/get/student/{id}',name: 'get_student_by_id',methods: ['GET'])]
    public function getStudentById(ManagerRegistry $doctrine,int $id) : JsonResponse
    {
        $student = $doctrine->getRepository(Student::class)->find($id);

        $data = [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'phone' => $student->getPhone(),
            'address' =>$student->getAddress(),
            'email' =>$student->getEmail(),
            'dob' => $student->getDoB(),
            'gender' => $student->getGender(),
        ];

        return $this->json($data);
    }
    #[Route('/update/student/{id}',name: 'update_classes',methods: ['PUT','PATCH'])]
    public  function updateStudent(ManagerRegistry $doctrine, Request $request,int $id) : JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return $this->json('No student found for id' . $id, 404);
        }

        $student->setName($request->request->get('name'));
        $student->setPhone($request->request->get('phone'));
        $student->setAddress($request->request->get('address'));
        $student->setEmail($request->request->get('email'));
        $student->setDoB(new DateTime($request->request->get('dob')));
        $student->setGender($request->get('gender'));
        $entityManager->flush();

        $data =  [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'phone' => $student->getPhone(),
            'address' =>$student->getAddress(),
            'email' =>$student->getEmail(),
            'dob' => $student->getDoB(),
            'gender' => $student->getGender(),
        ];

        return $this->json($data);
    }

    #[Route('/delete/student/{id}',name: 'delete_student',methods: ["DELETE"])]
    public function deleteStudent(ManagerRegistry $doctrine, int $id) :JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if(!$student){
            return $this->json('No student found for id '.$id,404);
        }

        $entityManager->remove($student);
        $entityManager->flush();

        return $this->json('Deleted a student successfully with id '.$id);
    }
}
