<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface IStudentService
{
    public function createStudent(ManagerRegistry $doctrine, Request $raw, ValidatorInterface $validator): JsonResponse;
    public function getAllStudents(ManagerRegistry $doctrine): JsonResponse;
    public function getStudentById(ManagerRegistry $doctrine, int $id): JsonResponse;
    public function updateStudentInfo(ManagerRegistry $doctrine, int $id, Request $raw, ValidatorInterface $validator): JsonResponse;
    public function deleteStudent(ManagerRegistry $doctrine, int $id): JsonResponse;
}