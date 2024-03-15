<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface IClassRoomService
{
    public function createClassRoom(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse;
    public function getAllClassRooms(ManagerRegistry $doctrine): JsonResponse;
    public function getClassById(ManagerRegistry $doctrine, int $id): JsonResponse;
    public function updateClassInfo(ManagerRegistry $doctrine, int $id, Request $raw, ValidatorInterface $validator): JsonResponse;
    public function addStudent(ManagerRegistry $doctrine, int $classId, int $studentId): JsonResponse;
    public function removeStudent(ManagerRegistry $doctrine, int $classId, int $studentId): JsonResponse;
    public function deleteClass(ManagerRegistry $doctrine, int $id): JsonResponse;

}