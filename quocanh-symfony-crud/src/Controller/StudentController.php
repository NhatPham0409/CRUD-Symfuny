<?php

namespace App\Controller;

use App\Service\IStudentService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1', name: 'api_')]
class StudentController extends AbstractController
{
    private IStudentService $studentService;

    public function __construct(IStudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    #[Route('/student', name: 'student_create', methods: ['POST'])]
    public function create(ManagerRegistry $doctrine, Request $raw): JsonResponse
    {
        //return StudentServiceImpl::createStudent($doctrine, $raw);
        return $this->studentService->createStudent($doctrine, $raw);
    }

    #[Route('/student', name: 'student_list', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        //return StudentServiceImpl::getAllStudents($doctrine);
        return $this->studentService->getAllStudents($doctrine);
    }

    #[Route('/student/{id}', name: 'student_get', methods: ['GET'])]
    public function getOneStudent(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        //return StudentServiceImpl::getStudentById($doctrine, $id);
        return $this->studentService->getStudentById($doctrine, $id);
    }

    #[Route('/student/{id}', name: 'student_update', methods: ['PUT', 'PATCH'])]
    public function update(ManagerRegistry $doctrine, Request $raw, int $id): JsonResponse
    {
        //return StudentServiceImpl::updateStudentInfo($doctrine, $id, $raw);
        return $this->studentService->updateStudentInfo($doctrine, $id, $raw);
    }

    #[Route('/student/{id}', name: 'student_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        //return StudentServiceImpl::delete($doctrine, $id);
        return $this->studentService->delete($doctrine, $id);
    }
}