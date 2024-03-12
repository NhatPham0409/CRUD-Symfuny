<?php

namespace App\Service;

use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface IStudentService
{
    public function createStudent(ManagerRegistry $doctrine, Request $raw): JsonResponse;
    public function getAllStudents(ManagerRegistry $doctrine): JsonResponse;
    public function getStudentById(ManagerRegistry $doctrine, int $id): JsonResponse;
    public function updateStudentInfo(ManagerRegistry $doctrine, int $id, Request $raw): JsonResponse;
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse;
}