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
    private ?string $name = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Length(max: 10)]
    #[Assert\Regex(
        pattern: "/^[0-9]/",
        message: "name must contain only numbers"
    )]
    private ?string $phone = null;

    #[ORM\ManyToMany(targetEntity: Classes::class, mappedBy: 'ClassId')]
    private Collection $classes;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
//    #[Assert\Date]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 3)]
    #[Assert\Choice(
        choices: ['NAM', 'NU'],message: 'Choose NAM or NU'
    )]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $address = null;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Classes>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classes $class): static
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
//            $class->addStudent($this);// Ensure bidirectional consistency
        }

        return $this;
    }

    public function removeClass(Classes $class): static
    {
        $this->classes->removeElement($class);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDoB(): ?string
    {
        if($this->dob instanceof \DateTimeInterface){
            return $this->dob->format('d-m-Y'); //Fomat as YYYY-MM-DD
        }
        return null;
    }

    public function setDoB(?\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }


    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }
}
