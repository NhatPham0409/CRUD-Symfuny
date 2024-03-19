<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Repository\StudentRepository;
use DateTime;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
    public function createStudent(EntityManagerInterface $entityManager, Request $request,ValidatorInterface $validator): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), true);
        $student = new Student();
        $student->setName($requestBody['name'] ?? null);
        $student->setPhone($requestBody['phone'] ?? null);
        $student->setAddress($requestBody['address'] ?? null);
        $student->setEmail($requestBody['email'] ?? null);
        $student->setDoB(new DateTime($requestBody['dob'] ?? null));
        $student->setGender($requestBody['gender'] ?? null);

        // Validate the entity
        $errors = $validator->validate($student);
        // If there are validation errors, return them

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

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
    public  function updateStudent(ManagerRegistry $doctrine, Request $request,int $id,ValidatorInterface $validator) : JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return $this->json(['error' => 'student not found'], 404);
        }

        $student->setName($request->request->get('name'));
        $student->setPhone($request->request->get('phone'));
        $student->setAddress($request->request->get('address'));
        $student->setEmail($request->request->get('email'));
        $student->setDoB(new DateTime($request->request->get('dob')));
        $student->setGender($request->get('gender'));

        // Validate the updated entity
        $errors = $validator->validate($student);

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

    #[Route('/add/class-to-student', name: 'add_class_to_student', methods: ['POST'])]
    public function addClassToStudent(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Validate the request data
        $violations = $validator->validate($requestData, [
            new Assert\Collection([
                'student_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
                'class_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
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

        // Retrieve student and class entities
        $student = $entityManager->getRepository(Student::class)->find($requestData['student_id']);
        $class = $entityManager->getRepository(Classes::class)->find($requestData['class_id']);

        // Check if student and class entities exist
        if (!$student) {
            return $this->json(['error' => 'Student not found'], 404);
        }

        if (!$class) {
            return $this->json(['error' => 'Class not found'], 404);
        }

        // Check if the student already contains the class
        if ($student->getClasses()->contains($class)) {
            return $this->json(['error' => 'Student already belongs to this class'], 400);
        }

        // Add class to student
        $student->addClass($class);

        // Persist changes
        $entityManager->flush();

        return $this->json(['message' => 'Class added to student successfully'], 200);
    }

    #[Route('/remove/class-from-student', name: 'remove_class_from_student', methods: ['POST'])]
    public function removeClassFromStudent(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Validate the request data
        $violations = $validator->validate($requestData, [
            new Assert\Collection([
                'student_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
                'class_id' => [new Assert\NotBlank(), new Assert\Type(['type' => 'integer'])],
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

        // Retrieve student and class entities
        $student = $entityManager->getRepository(Student::class)->find($requestData['student_id']);
        $class = $entityManager->getRepository(Classes::class)->find($requestData['class_id']);

        // Check if student and class entities exist
        if (!$student) {
            return $this->json(['error' => 'Student not found'], 404);
        }

        if (!$class) {
            return $this->json(['error' => 'Class not found'], 404);
        }

        // Check if the student contains the class
        if (!$student->getClasses()->contains($class)) {
            return $this->json(['error' => 'Student does not belong to this class'], 400);
        }

        // Remove class from student
        $student->removeClass($class);

        // Persist changes
        $entityManager->flush();

        return $this->json(['message' => 'Class removed from student successfully'], 200);
    }

    #[Route('/search/students', name: 'search_students', methods: ['GET'])]
    public function searchStudents(Request $request, StudentRepository $studentRepository): JsonResponse
    {
        $searchParams = $request->query->all();
        $page = $request->query->getInt('page', 1); // Default to page 1 if not provided
        $perPage = $request->query->getInt('perPage', 10); // Default to 10 items per page if not provided

        // Use the findByFieldsPaginated method from the repository to search for students with pagination
        $paginator = $studentRepository->findByFieldsPaginated($searchParams, $page, $perPage);

        $totalItems = $paginator->count();
        $totalPages = ceil($totalItems / $perPage);

        $data = [];
        foreach ($paginator as $student) {
            $studentData = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'phone' => $student->getPhone(),
                'address' => $student->getAddress(),
                'email' => $student->getEmail(),
                'dob' => $student->getDoB(),
                'gender' => $student->getGender(),
                'classes' => []
                // Include other student information as needed
            ];
            foreach ($student->getClasses() as $class){
                $studentData['classes'][] = [
                    'id' => $class->getId(),
                    'class_name' => $class->getClassName(),
                    'teacher' => $class->getTeacher()
                ];
            }

            $data[] = $studentData;
        }

        $paginationData = [
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'items_per_page' => $perPage,
        ];

        return $this->json(['data' => $data, 'pagination' => $paginationData]);
    }


}
