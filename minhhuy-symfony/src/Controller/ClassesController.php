<?php

namespace App\Controller;

use App\Entity\Classes;


use App\Entity\Student;
use App\Repository\ClassesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


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
    #[Route('/add/student-to-class', name: 'add_student_to_class', methods: ['POST'])]
    public function addStudentToClass(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Validate the request data
        $violations = $validator->validate($requestData, [
            new Assert\Collection([
                'class_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
                'student_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
            ])
        ]);

        // If there are validation errors, return them
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], 400);
        }

        // Retrieve class and student entities
        $class = $entityManager->getRepository(Classes::class)->find($requestData['class_id']);
        $student = $entityManager->getRepository(Student::class)->find($requestData['student_id']);

        // Check if class and student entities exist
        if (!$class) {
            return $this->json(['error' => 'Class not found'], 404);
        }

        if (!$student) {
            return $this->json(['error' => 'Student not found'], 404);
        }

        // Check if student is already associated with the class
        if ($class->getStudents()->contains($student)) {
            return $this->json(['error' => 'Student is already enrolled in this class'], 400);
        }

        // Add student to class
        $class->addStudent($student);

        // Persist changes
        $entityManager->flush();

        return $this->json(['message' => 'Student added to class successfully'], 200);
    }

    #[Route('/remove/student-from-class', name: 'remove_student_from_class', methods: ['POST'])]
    public function removeStudentFromClass(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Validate the request data
        $violations = $validator->validate($requestData, [
            new Assert\Collection([
                'class_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
                'student_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
            ])
        ]);

        // If there are validation errors, return them
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], 400);
        }

        // Retrieve class and student entities
        $class = $entityManager->getRepository(Classes::class)->find($requestData['class_id']);
        $student = $entityManager->getRepository(Student::class)->find($requestData['student_id']);

        // Check if class and student entities exist
        if (!$class) {
            return $this->json(['error' => 'Class not found'], 404);
        }

        if (!$student) {
            return $this->json(['error' => 'Student not found'], 404);
        }

        // Check if student is associated with the class
        if (!$class->getStudents()->contains($student)) {
            return $this->json(['error' => 'Student is not enrolled in this class'], 400);
        }

        // Remove student from class
        $class->removeStudent($student);

        // Persist changes
        $entityManager->flush();

        return $this->json(['message' => 'Student removed from class successfully'], 200);
    }

    //Search and pagination
    #[Route('/search/classes',name: 'get_classes',methods: ['GET'])]
    public function search(Request $request, ClassesRepository $classesRepository):JsonResponse
    {
        $searchParams = $request->query->all();

        $page = $request->query->getInt('page', 1); // Get the current page number from the request
        $perPage = $request->query->getInt('perPage', 5); // Get the number of items per page from the request


        // Use the findByFieldsPaginated method from the repository to search for classes with pagination
        $paginator = $classesRepository->findByFieldsPaginated($searchParams,$page,$perPage);
        $totalItems = $paginator->count(); // Total items without pagination
        $data = [];

        // Iterate over the paginated results
        foreach ($paginator as $class) {
            $classData = [
                'id' => $class->getId(),
                'class_name' => $class->getClassName(),
                'teacher' => $class->getTeacher(),
                'students' => []
            ];

            // Include student data
            foreach ($class->getStudents() as $student) {
                $classData['students'][] = [
                    'id' => $student->getId(),
                    'name' => $student->getName(),
                    'dob' => $student->getDoB(),
                    // Include other student information as needed
                ];
            }

            $data[] = $classData;
        }

        // Construct the pagination metadata
        $pagination = [
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalItems' => $totalItems,
            'totalPages' => ceil($totalItems / $perPage)
        ];

        // Combine data and pagination metadata into the response
        $response = [
            'data' => $data,
            'pagination' => $pagination
        ];

        return $this->json($response);
    }

}
