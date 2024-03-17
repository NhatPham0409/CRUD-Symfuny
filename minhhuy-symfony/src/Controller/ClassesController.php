<?php

namespace App\Controller;

use App\Entity\Classes;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('api',name: 'api_')]
class ClassesController extends AbstractController
{
    #[Route('/get/classes',name: 'get_classes',methods: ['GET'])]
    public function getClasses(ManagerRegistry $doctrine,Request $request) :JsonResponse
    {
        $classes = $doctrine->getRepository(Classes::class)->findAll();


       $data = array_map(function ($class){
           return [
            'id' => $class->getId(),
            'class_name' => $class->getClassName(),
            'teacher' => $class->getTeacher(),
           ];
       },$classes);

        return $this->json($data);
    }



    #[Route('/create/class', name: 'create_class',methods: ['POST'])]
    public function createClasses(EntityManagerInterface $entityManager, Request $request,ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Create a new instance of the class
        $class = new Classes();
        $class->setClassName($requestData['class_name'] ?? null);
        $class->setTeacher($requestData['teacher'] ?? null);

        // Validate the entity
        $errors = $validator->validate($class);

        // If there are validation errors, return them
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        // If validation passes, persist the entity
        $entityManager->persist($class);
        $entityManager->flush();

        $data = [
            'id' => $class->getId(),
            'class_name' => $class->getClassName(),
            'teacher' => $class->getTeacher(),
        ];
        return $this->json($data,201);
    }

    #[Route('/get/class/{id}',name: 'get_classes_by_id',methods: ['GET'])]
    public function getClassesById(ManagerRegistry $doctrine,int $id) : JsonResponse
    {
        $class = $doctrine->getRepository(Classes::class)->find($id);

        if(!$class){
            return $this->json(['error' => 'Class not found'],404);
        }

        $data = [
            'id' => $class->getId(),
            'class_name' => $class->getClassName(),
            'teacher' => $class->getTeacher(),
        ];

        return $this->json($data);
    }
    #[Route('/update/class/{id}',name: 'update_class',methods: ['PUT','PATCH'])]
    public  function updateClass(ManagerRegistry $doctrine, Request $request,int $id,ValidatorInterface $validator) : JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $class = $entityManager->getRepository(Classes::class)->find($id);

        if (!$class) {
            return $this->json(['error' => 'Class not found'], 404);
        }

        // Update class properties
        $class->setClassName($request->request->get('class_name'));
        $class->setTeacher($request->request->get('teacher'));

        // Validate the updated entity
        $errors = $validator->validate($class);

        // If there are validation errors, return them
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager->flush();

        $data =  [
            'id' => $class->getId(),
            'name' => $class->getClassName(),
            'description' => $class->getTeacher(),
        ];

        return $this->json($data);
    }

    #[Route('/delete/class/{id}',name: 'delete_class',methods: ['DELETE'])]
    public function deleteClasses(ManagerRegistry $doctrine, int $id) :JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $class = $entityManager->getRepository(Classes::class)->find($id);

        if(!$class){
            return $this->json(['error' => 'Class not found'],404);
        }

        $entityManager->remove($class);
        $entityManager->flush();

        return $this->json(['message' => 'Deleted class successfully', 'id' => $id]);
    }
}
