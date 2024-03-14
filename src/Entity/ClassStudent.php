<?php

namespace App\Entity;

use App\Repository\ClassStudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassStudentRepository::class)]
class ClassStudent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ClassName = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $totalStudent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassName(): ?string
    {
        return $this->ClassName;
    }

    public function setClassName(string $ClassName): static
    {
        $this->ClassName = $ClassName;

        return $this;
    }

    public function getTotalStudent(): ?int
    {
        return $this->totalStudent;
    }

    public function setTotalStudent(int $totalStudent): static
    {
        $this->totalStudent = $totalStudent;

        return $this;
    }
}
