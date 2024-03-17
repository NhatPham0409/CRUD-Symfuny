<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api',name: 'api_')]
class StudentController extends AbstractController
{
    #[Route('/get/student',name: 'get_student',methods: ["GET"])]
    public function getClasses(ManagerRegistry $doctrine) :JsonResponse
    {
        $students = $doctrine->getRepository(Student::class)->findAll();

        $data = [];

        foreach ($students as $student) {
            $data[] = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'phone' => $student->getPhone(),
                'address' =>$student->getAddress(),
                'email' =>$student->getEmail(),
                'dob' => $student->getDoB(),
                'gender' => $student->getGender(),
            ];
        }

        return $this->json($data);
    }


    #[Route('/create/student', name: 'create_student',methods: ["POST"])]
    public function createClasses(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), true);
        $student = new Student();
        $student->setName($requestBody['name']);
        $student->setPhone($requestBody['phone']);
        $student->setAddress($requestBody['address']);
        $student->setEmail($requestBody['email']);
        $student->setDoB($requestBody['dob']);
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

    #[Route('/get/class/{id}',name: 'get_classes_byId',methods: ["GET"])]
    public function getClassesById(ManagerRegistry $doctrine,int $id) : JsonResponse
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
    #[Route('/update/class/{id}',name: 'update_classes',methods: ["PUT","PATCH"])]
    public  function updateClasses(ManagerRegistry $doctrine, Request $request,int $id) : JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return $this->json('No student found for id' . $id, 404);
        }

        $student->setClassName($request->request->get('class_name'));
        $student->setTeacher($request->request->get('teacher'));
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
