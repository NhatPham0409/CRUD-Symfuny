<?php

namespace App\Controller;

use App\Service\IMovieService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/movie', name: 'api_')]
class MovieController extends AbstractController
{
    private IMovieService $movieService;

    public function __construct(IMovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    #[Route('/fetch-data', name: 'app_movie', methods: ['POST'])]
    public function fetchMovieDataFromAPI(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        return $this->movieService->fetchMovieDataFromAPI($doctrine, $request);
    }

    #[Route(name: 'movie_list', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        return $this->movieService->getAllMovies($doctrine, $request);
    }

    #[Route('/get-info/{id}', name: 'app_movie_list', methods: ['GET'])]
    public function list(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        return $this->movieService->getMovieInfoById($doctrine, $id);
    }
}
