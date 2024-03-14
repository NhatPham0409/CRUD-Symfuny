<?php

namespace App\Controller;

use App\Entity\Sinhvien;
use App\Form\SinhvienType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em2)
    {
        $this->em = $em2;
    }


    #[Route('/', name: 'app_main')]
    public function index(Request $request): Response
    {
        $listSv = $this->em->getRepository(Sinhvien::class)->findAll();

        $sinhvien = new Sinhvien();

        $formCreate = $this->createForm(SinhvienType::class, $sinhvien);
        $formCreate->handleRequest($request);
        if($formCreate->isSubmitted()&& $formCreate->isValid()) {
            $this->em->persist($sinhvien);
            $this->em->flush();
            return
             $this->redirectToRoute('app_main');
        }
    

        return $this->render('main/index.html.twig', [
            'listSv' => $listSv,
            'formCreate' => $formCreate,
        ]);

    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(Request $request,$id): Response
    {

        $sinhvien = $this->em->getRepository(Sinhvien::class)->find($id);

        $formUpdate = $this->createForm(SinhvienType::class, $sinhvien);
        $formUpdate->handleRequest($request);

        if($formUpdate->isSubmitted()&& $formUpdate->isValid()) {
            $this->em->persist($sinhvien);
            $this->em->flush();
            return
             $this->redirectToRoute('app_main');
        }
    

        return $this->render('main/detail.html.twig', [
            'formUpdate' => $formUpdate->createView()
        ]);

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request,$id): Response
    {

        $sinhvien = $this->em->getRepository(Sinhvien::class)->find($id);
        $this->em->remove($sinhvien);
        $this->em->flush();
        // $formUpdate = $this->createForm(SinhvienType::class, $sinhvien);
        // $formUpdate->handleRequest($request);

        // if($formUpdate->isSubmitted()&& $formUpdate->isValid()) {
        //     $this->em->persist($sinhvien);
        //     $this->em->flush();
        //     return
        //      $this->redirectToRoute('app_main');
        // }
    

        // return $this->render('main/detail.html.twig', [
        //     'formUpdate' => $formUpdate->createView()
        // ]);
        return $this->redirectToRoute('app_main');

    }

    #[Route('/deleteTest/{id}', name: 'deleteTest')]
    public function deleteTest(Request $request,$id): Response
    {

        $sinhvien = $this->em->getRepository(Sinhvien::class)->find($id);

        $formUpdate = $this->createForm(SinhvienType::class, $sinhvien);
        $formUpdate->handleRequest($request);

        if($formUpdate->isSubmitted()&& $formUpdate->isValid()) {
            $this->em->persist($sinhvien);
            $this->em->flush();
            return
             $this->redirectToRoute('app_main');
        }
    

        return $this->render('main/deleteTest.html.twig', [
            'formUpdate' => $formUpdate->createView()
        ]);

    }

}
