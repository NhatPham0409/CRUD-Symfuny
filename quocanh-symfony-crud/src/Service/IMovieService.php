<?php

namespace App\Service;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface IMovieService
{
    public function fetchMovieDataFromAPI(ManagerRegistry $doctrine, Request $request): JsonResponse;
    public function getAllMovies(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request): JsonResponse;
    public function getMovieInfoById(\Doctrine\Persistence\ManagerRegistry $doctrine, int $id): JsonResponse;
}