<?php

namespace App\Service\Impl;

use App\Entity\Movie;
use App\Service\IMovieService;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MovieService implements IMovieService
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    private function extractMovieId(string $url): string
    {
        $url = str_replace('https://www.imdb.com/title/', '', $url);
        return strtok($url, '/?');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function fetchMovieDataFromAPI(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $url = json_decode($request->getContent(), true);

        if (empty($url)) {
            return new JsonResponse(['error' => 'No data found in the request.'], Response::HTTP_BAD_REQUEST);
        }

        $movieId = $this->extractMovieId($url['url']);

        $response = $this->client->request('GET', 'https://imdb-api.tienich.workers.dev/title/' . $movieId);
        try {
            $content = $response->toArray();

            $entityManager = $doctrine->getManager();
            $movie = $entityManager->getRepository(Movie::class)->findOneBy(['movieId' => $movieId]);

            if ($movie != null) {
                return new JsonResponse(['error' => 'The movie already exists'], Response::HTTP_CONFLICT);
            }

            $movie = new Movie();
            $movie->setMovieId($movieId);
            $movie->setTitle($content['title']);
            $movie->setPlot($content['plot']);
            $movie->setReleaseYear($content['releaseDetailed']['year']);
            $movie->setRatingStar($content['rating']['star']);

            $entityManager->persist($movie);
            $entityManager->flush();

        } catch (ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            return new JsonResponse(['error' => "An error occurred while trying to fetch the movie data:\n" . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($movie->toArrayMovie(), Response::HTTP_OK);
    }

    public function getAllMovies(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('limit') ?? 10;
        $page = $request->query->getInt('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $query = $doctrine->getRepository(Movie::class)->findAllMoviePagination();
        $paginator = new Paginator($query);
        $movieList = $paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getResult();
        $totalItems = $paginator->count();
        $totalPages = (int)ceil($totalItems / $perPage);

        if (!$movieList) {
            return new JsonResponse(['error' => 'No movie found'], Response::HTTP_NOT_FOUND);
        }

        $movies = [];

        foreach ($movieList as $movie) {
            $movies[] = $movie->toArrayMovie();
        }

        $data = [
            'movies' => $movies,
            'pagination' => [
                'totalItems' => $totalItems,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages
            ]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getMovieInfoById(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $movie = $entityManager->getRepository(Movie::class)->find($id);

        if ($movie == null) {
            return new JsonResponse(['error' => 'The movie does not exist'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($movie->toArrayMovie(), Response::HTTP_OK);
    }
}