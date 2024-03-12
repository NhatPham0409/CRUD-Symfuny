<?php

namespace App\Service;

use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface IStudentService
{
    public static function createStudent(ManagerRegistry $doctrine, Request $raw): JsonResponse;
    public static function getAllStudents(ManagerRegistry $doctrine): JsonResponse;
    public function getStudentById(ManagerRegistry $doctrine, int $id): JsonResponse;
    public static function updateStudentInfo(ManagerRegistry $doctrine, int $id, Request $raw): JsonResponse;
    public static function delete(ManagerRegistry $doctrine, int $id): JsonResponse;
}