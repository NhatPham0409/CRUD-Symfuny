<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api","api_")]
class TodoController extends AbstractController
{
    #[Route('/todos', name: 'app_todos',methods: ["GET"])]
    public function index(TodoRepository $todoRepository): JsonResponse
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/TodoController.php',
        // ]);

        $todos = $todoRepository->findAll();
        return $this->json($todos);
    }

    #[Route("/todos/{id}","get_todo",methods:["GET"])]
    public function getTodo(Todo $todo): JsonResponse
    {
        return $this->json($todo);
    }

    #[Route("/todos","create_todo",methods:["POST"])]
    public function createTodo(Request $request,EntityManagerInterface $entityManager) :JsonResponse
    {
        $requestBody = json_decode($request->getContent(),true);
        $todo = new Todo();
        $todo->setTitle($requestBody["title"]);
        $todo->setUpdateAt();
        $todo->setCompleted($requestBody["completed"]);
        $todo->setCreateAt();

        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->json($todo,status: Response::HTTP_CREATED);
    }

    #[Route("/todos/{id}","update_todo",methods:["PATCH"])]
    public function updateTodo(EntityManagerInterface $entityManager,Request $request, int $id)
    {
        $requestBody = json_decode($request->getContent(),true);
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        $todo->setTitle($requestBody["title"]);
        $entityManager->flush();

        return $this->json($todo);
    }

    #[Route("/todos/{id}","delete_todo",methods:["DELETE"])]
    public function deleteTodo(EntityManagerInterface $entityManager, Request $request, int $id)
    {
        $requestBody = json_decode($request->getContent(), true);
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        $entityManager->remove($todo);
        $entityManager->flush();

        return $this->json([
            'message' => 'Delete successfully',
        ]    
        );
    }
}
