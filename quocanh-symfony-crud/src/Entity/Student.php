<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use DateTimeInterface;
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

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $first_name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $last_name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?DateTimeInterface $dob = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank]
    private ?string $phone = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $address = null;

    #[ORM\ManyToOne(inversedBy: 'student')]
    private ?ClassRoom $classRoom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getDob(): ?DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(DateTimeInterface $dob): static
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getClassRoom(): ?ClassRoom
    {
        return $this->classRoom;
    }

    public function setClassRoom(?ClassRoom $classRoom): static
    {
        $this->classRoom = $classRoom;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'gender' => $this->getGender(),
            'dob' => $this->getDob()->format('d-m-Y'),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'address' => $this->getAddress(),
            'classRoom' => $this->getClassRoom()->getRoomName()
        ];
    }

    public static function fieldSetterMap(): array
    {
        return [
            'first_name' => 'setFirstName',
            'last_name' => 'setLastName',
            'dob' => 'setDob',
            'gender' => 'setGender',
            'phone' => 'setPhone',
            'email' => 'setEmail',
            'address' => 'setAddress',
            'classRoom' => 'setClassRoom'
        ];
    }
}
