<?php

namespace App\Controller;

use App\Service\IClassRoomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/v1/classroom', name: 'api_')]
class ClassRoomController extends AbstractController
{
    private IClassRoomService $classRoomService;

    public function __construct(IClassRoomService $classRoomService)
    {
        $this->classRoomService = $classRoomService;
    }

    #[Route(name: 'class_create', methods: ['POST'])]
    public function create(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse
    {
        return $this->classRoomService->createClassRoom($doctrine, $request, $validator);
    }

    #[Route(name: 'class_list', methods: ['GET'])]
    public function getAllClassRooms(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        return $this->classRoomService->getAllClassRooms($doctrine, $request);
    }

    #[Route('/get-info/{id}', name: 'class_get', methods: ['GET'])]
    public function getClassInfoById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        return $this->classRoomService->getClassById($doctrine, $id);
    }

    #[Route('/update/{id}', name: 'class_update', methods: ['PUT', 'PATCH'])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id, ValidatorInterface $validator): JsonResponse
    {
        return $this->classRoomService->updateClassInfo($doctrine, $id, $request, $validator);
    }

    #[Route('/add_student', name: 'class_student_add', methods: ['PUT', 'PATCH'])]
    public function addStudent(ManagerRegistry $doctrine, #[MapQueryParameter] int $classId, #[MapQueryParameter] int $studentId): JsonResponse
    {
        return $this->classRoomService->addStudent($doctrine, $classId, $studentId);
    }

    #[Route('/remove_student', name: 'class_student_remove', methods: ['DELETE'])]
    public function removeStudent(ManagerRegistry $doctrine, #[MapQueryParameter] int $classId, #[MapQueryParameter] int $studentId): JsonResponse
    {
        return $this->classRoomService->removeStudent($doctrine, $classId, $studentId);
    }

    #[Route('/delete/{id}', name: 'class_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        return $this->classRoomService->deleteClass($doctrine, $id);
    }

    #[Route('/search-by-fields', name: 'class_search', methods: ['GET'])]
    public function searchByFields(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        return $this->classRoomService->findClassByFields($doctrine, $request);
    }
}
