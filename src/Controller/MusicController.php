<?php

namespace App\Controller;

use App\Entity\Music;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class MusicController extends AbstractController
{
    #[Route('/music', name: 'music_index', methods: ['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $musics = $doctrine->getRepository(Music::class)->findAll();
        $data = [];
        foreach ($musics as $music) {

            $data[] = [
                'id' => $music->getId(),
                'songName' => $music->getSongName(),
                'author' => $music->getAuthor(),
                'album' => $music->getAlbum(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/music/{id}', name: 'music_show', methods: ['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $music = $doctrine->getRepository(Music::class)->find($id);

        if (!$music) {
            return $this->json('No music found for id ' . $id, 404);
        }

        $data = [
            'id' => $music->getId(),
            'songName' => $music->getSongName(),
            'author' => $music->getAuthor(),
            'album' => $music->getAlbum(),
        ];
        return $this->json($data);
    }

    #[Route('/music', name: 'music_create', methods: ['post'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $music = new Music();

        $songName = $request->request->get('songName');
        $author = $request->request->get('author');

        if (empty($songName) && empty($author))
            return $this->json('SongName and Author are required', 400);
        if (empty($songName))
            return $this->json('SongName are required', 400);
        if (empty($author))
            return $this->json('Author are required', 400);

        $music->setSongName($request->request->get('songName'));
        $music->setAuthor($request->request->get('author'));
        $music->setAlbum($request->request->get('album'));

        $entityManager->persist($music);
        $entityManager->flush();

        $data = [
            'id' => $music->getId(),
            'songName' => $music->getSongName(),
            'author' => $music->getAuthor(),
            'album' => $music->getAlbum(),
        ];
        return $this->json($data);
    }

    #[Route('/music/{id}', name: 'music_update', methods: 'put')]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $music = $entityManager->getRepository(Music::class)->find($id);

        if (!$music) {
            return $this->json('No music found for id=' . $id, 404);
        }

        $songName = $request->request->get('songName');
        $author = $request->request->get('author');

        if (empty($songName) && empty($author))
            return $this->json('SongName and Author are required', 400);
        if (empty($songName))
            return $this->json('SongName are required', 400);
        if (empty($author))
            return $this->json('Author are required', 400);

        $music->setSongName($request->request->get('songName'));
        $music->setAuthor($request->request->get('author'));
        $music->setAlbum($request->request->get('album'));

        $entityManager->flush();

        $data = [
            'id' => $music->getId(),
            'songName' => $music->getSongName(),
            'author' => $music->getAuthor(),
            'album' => $music->getAlbum(),
        ];
        return $this->json($data);
    }

    #[Route('/music/{id}', name: 'music_delete', methods: ['delete'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $music = $entityManager->getRepository(Music::class)->find($id);

        if (!$music) {
            return $this->json('No music found for id' . $id, 404);
        }

        $entityManager->remove($music);
        $entityManager->flush();
        return $this->json('Deleted a music successfully with id ' . $id);
    }

    #[Route('/music/filter/{field}', name: 'music_filter', methods: ['get'])]
    public function filter(ManagerRegistry $doctrine, string $field): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $fields = ['songName', 'author', 'album'];
        if (empty($field))
            return $this->json('FieldName are required', 405);
        if (in_array($field, $fields)) {
            $data = $entityManager->getRepository(Music::class)->findField($field);
            return $this->json($data);
        } else
            return $this->json('No data with fieldname:' . $field, 400);
    }

    #[Route('/music/search/{field}', name: 'music_search', methods: ['get'])]
    public function search(ManagerRegistry $doctrine, string $field): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $fields = ['songName', 'author', 'album'];
        if (empty($field))
            return $this->json('FieldName are required', 405);
        if (isset($field, $fields)) {
            $data = $entityManager->getRepository(Music::class)->searchBySongName($field);
            return $this->json($data);
        } else
            return $this->json('No data with fieldname:' . $field, 400);
    }

}
