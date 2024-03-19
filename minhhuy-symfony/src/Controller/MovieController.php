<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[Route('api',name: 'api_')]
class MovieController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    #[Route('/movie/fetch-data', name: 'app_movie',methods:'POST')]
    public function fetchData(Request $request, HttpClientInterface $httpClient,ManagerRegistry $doctrine): JsonResponse
    {

        $data = json_decode($request->getContent(),true);
        if(!isset($data)){
            return new JsonResponse(['error' => 'URL parameter is missing.'],400);
        }

        $url = $data['url'];

        $movieId = $this->extractId($url);

        $apiMovie = "https://imdb-api.tienich.workers.dev/title/".$movieId;

        $response = $httpClient->request(
            'GET',
            $apiMovie
        );

        $content = $response->toArray();

        $entityManager = $doctrine->getManager();


        $movie = $entityManager->getRepository(Movie::class)->findOneBy(['movieId' => $movieId]);

        if ($movie != null) {
            return new JsonResponse(['error' => 'The movie already exists'], 409);
        }

        $movie = new Movie();
        $movie->setMovieId($movieId);
        $movie->setTitle($content['title']);
        $movie->setPlot($content['plot']);
        $movie->setYear($content['releaseDetailed']['year']);
        $movie->setStar($content['rating']['star']);


        $entityManager->persist($movie);
        $entityManager->flush();

        $movieData = [
            'id' => $movie->getMovieId(),
            'title' => $movie->getTitle(),
            'plot' => $movie->getPlot(),
            'year' => $movie->getYear(),
            'star' => $movie->getStar()
        ];


        return $this->json($movieData);
    }

    private function extractId(string $url): ?string
    {

        // Extract ID from the URL
        $pattern = '/tt\d{7,8}/';
        preg_match($pattern,$url,$matches);

        //if match found, return ID , else return null
        return !empty($matches) ? $matches[0] : null;


    }
}
