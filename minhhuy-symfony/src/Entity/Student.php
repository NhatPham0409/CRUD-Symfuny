<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "name cannot be blank")]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]+$/",
        message: "name must contain only letters and spaces"
    )]
    private ?string $Name = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Length(max: 10)]
    private ?string $Phone = null;

    #[ORM\ManyToMany(targetEntity: Classes::class, mappedBy: 'ClassId')]
    private Collection $StudentId;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email]
    private ?string $Email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
//    #[Assert\Date]
    private ?\DateTimeInterface $DoB = null;

    #[ORM\Column(length: 3)]
    #[Assert\Choice(
        choices: ['NAM', 'NU'],message: 'Choose NAM or NU'
    )]
    private ?string $Gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $Address = null;

    public function __construct()
    {
        $this->StudentId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(?string $Phone): static
    {
        $this->Phone = $Phone;

        return $this;
    }

    /**
     * @return Collection<int, Classes>
     */
    public function getStudentId(): Collection
    {
        return $this->StudentId;
    }

    public function addStudentId(Classes $studentId): static
    {
        if (!$this->StudentId->contains($studentId)) {
            $this->StudentId->add($studentId);
            $studentId->addClassId($this);
        }

        return $this;
    }

    public function removeStudentId(Classes $studentId): static
    {
        if ($this->StudentId->removeElement($studentId)) {
            $studentId->removeClassId($this);
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getDoB(): ?string
    {
        if($this->DoB instanceof \DateTimeInterface){
            return $this->DoB->format('d-m-Y'); //Fomat as YYYY-MM-DD
        }
        return null;
    }

    public function setDoB(?\DateTimeInterface $DoB): static
    {
        $this->DoB = $DoB;

        return $this;
    }


    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(?string $Address): static
    {
        $this->Address = $Address;

        return $this;
    }
}
