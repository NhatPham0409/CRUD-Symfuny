<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface IStudentService
{
    //CRUD operations for student
    public function createStudent(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse;
    public function getAllStudents(ManagerRegistry $doctrine): JsonResponse;
    public function getStudentInfoById(ManagerRegistry $doctrine, int $id): JsonResponse;
    public function getStudentClassInfo(ManagerRegistry $doctrine, int $id): JsonResponse;
    public function updateStudentInfo(ManagerRegistry $doctrine, int $id, Request $raw, ValidatorInterface $validator): JsonResponse;
    public function enrollStudent(ManagerRegistry $doctrine, int $studentId, int $classId): JsonResponse;
    public function unenrollStudent(ManagerRegistry $doctrine, int $studentId, int $classId): JsonResponse;
    public function deleteStudent(ManagerRegistry $doctrine, int $id): JsonResponse;

    //Search operations for student
    public function findStudentByFields(ManagerRegistry $doctrine, Request $request): JsonResponse;
}