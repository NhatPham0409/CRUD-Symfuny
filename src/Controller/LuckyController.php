<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\InfoStudent;
use App\Repository\InfoStudentRepository;
use App\Service\ManagementStudent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class LuckyController extends AbstractController
{


    public function __construct()
    {
        $managementStudent = new ManagementStudent();
    }

    #[Route('/lucky/number')]
    public function number(): Response
    {
        $arr = getenv();
        foreach ($arr as $key => $val) {
            echo " $key -> $val,";
            echo "\n";
        }

        $number = 1;

        return new Response(
            '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }

    #[Route('/managementInfo')]
    public function ManagementInfo(InfoStudentRepository $entityManager): Response
    {
        $managementStudent = new ManagementStudent();
        $managementStudent->openConnection();
        $infoListStudent = $managementStudent->getInfoList($entityManager);
        return $this->render('ManagementInfoStudent.html.twig', [
            'infoListStudent' => $infoListStudent
        ]);
    }

    #[Route('/add-new-student')]
    public function addNewEmployee(LoggerInterface $logger, Request $request, EntityManagerInterface $entityManager)
    {
        $infoStudent = new InfoStudent($request->get("name"), $request->get("email"),
            $request->get("address"), $request->get("phone"));
        $entityManager->persist($infoStudent);
        $entityManager->flush();
        return $this->redirect('http://127.0.0.1:8000/managementInfo');
    }

    #[Route('/update-info-student')]
    public function updateInfoEmployee(LoggerInterface $logger, Request $request, EntityManagerInterface $entityManager)
    {
        $logger->info("Id student: " .$request->get("id"));
        $infoStudent = $entityManager->getRepository(InfoStudent::class)->find($request->get("id"));
        $infoStudent->setName($request->get("name"));
        $infoStudent->setEmail($request->get("email"));
        $infoStudent->setAddress($request->get("address"));
        $infoStudent->setPhone($request->get("phone"));
        $entityManager->flush();
        return $this->redirect('http://127.0.0.1:8000/managementInfo');
    }

    #[Route('/delete-info-student')]
    public function deleteInfoEmployee(LoggerInterface $logger, Request $request, EntityManagerInterface $entityManager)
    {
        $logger->info("Id student delete: " .$request->get("id"));
        $infoStudent = $entityManager->getRepository(InfoStudent::class)->find($request->get("id"));
        //$logger->info("Giang Nam Student Id: " .$request->get("id"));
        //$logger->info("Giang Nam Student: " .$infoStudent);
        $entityManager->remove($infoStudent);
        $entityManager->flush();
        return $this->redirect('http://127.0.0.1:8000/managementInfo');
    }

}