<?php

namespace App\Controller;

use App\Service\IStudentService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/student', name: 'api_')]
class StudentController extends AbstractController
{
    private IStudentService $studentService;

    public function __construct(IStudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    //CRUD operations for student
    #[Route(name: 'student_create', methods: ['POST'])]
    public function create(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse
    {
        return $this->studentService->createStudent($doctrine, $raw, $validator);
    }

    #[Route(name: 'student_list', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        return $this->studentService->getAllStudents($doctrine);
    }

    #[Route('/{id}', name: 'student_get_info', methods: ['GET'])]
    public function getStudentInfoById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        return $this->studentService->getStudentInfoById($doctrine, $id);
    }

    #[Route('/{id}/class', name: 'student_class', methods: ['GET'])]
    public function getStudentClassInfo(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        return $this->studentService->getStudentClassInfo($doctrine, $id);
    }

    #[Route('/{id}', name: 'student_update', methods: ['PUT', 'PATCH'])]
    public function update(ManagerRegistry $doctrine, Request $raw, int $id, ValidatorInterface $validator): JsonResponse
    {
        return $this->studentService->updateStudentInfo($doctrine, $id, $raw, $validator);
    }

    #[Route('/enroll', name: 'student_enroll', methods: ['POST'])]
    public function enroll(ManagerRegistry $doctrine, #[MapQueryParameter] int $student_id, #[MapQueryParameter] int $class_id): JsonResponse
    {
        return $this->studentService->enrollStudent($doctrine, $student_id, $class_id);
    }

    #[Route('/unenroll', name: 'student_unenroll', methods: ['DELETE'])]
    public function unEnroll(ManagerRegistry $doctrine, #[MapQueryParameter] int $student_id, #[MapQueryParameter] int $class_id): JsonResponse
    {
        return $this->studentService->unenrollStudent($doctrine, $student_id, $class_id);
    }

    #[Route('/{id}', name: 'student_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        return $this->studentService->deleteStudent($doctrine, $id);
    }

    //Search operations for student
    #[Route('/search', name: 'student_search', methods: ['GET'])]
    public function search(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        return $this->studentService->findStudentByFields($doctrine, $request);
    }
}