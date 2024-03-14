<?php

namespace App\Service;

use App\Entity\InfoStudent;
use App\Model\ConnectToDatabase;
use App\Repository\InfoStudentRepository;
use Doctrine\DBAL\Cache\ArrayResult;
use Doctrine\ORM\EntityManagerInterface;

class ManagementStudent
{

    private $connectToDatabase;

    public function __construct()
    {
        $this->connectToDatabase = new ConnectToDatabase();
        $this->openConnection();
    }

    public function openConnection(){
        $this->connectToDatabase->open_database_connection();
    }
    public function getInfoList(InfoStudentRepository $entityManager) : array
    {
        $result = $entityManager->findAll();
        return $result;
    }
}