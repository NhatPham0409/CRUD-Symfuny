<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use DateTimeInterface;
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

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    //#[Assert\Regex(pattern: '#^[a-zA-Z ]+$#', message: 'Only letters and spaces are allowed')]
    private ?string $first_name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    //#[Assert\Regex(pattern: '#^[a-zA-Z ]+$#', message: 'Only letters and spaces are allowed')]
    private ?string $last_name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ["Nam", "Nữ"], message: "Choose a valid gender: Nam or Nữ")]
    private ?string $gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?DateTimeInterface $dob = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '#^(0[0-9]{9,}+)$#', message: 'Invalid phone number')]
    private ?string $phone = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    //#[Assert\Regex(pattern: '#^[a-zA-Z0-9,/ ]+$#', message: 'Only letters, numbers, spaces and commas are allowed')]
    private ?string $address = null;

    #[ORM\ManyToMany(targetEntity: ClassRoom::class, mappedBy: 'studentList')]
    private Collection $classList;

    public function __construct()
    {
        $this->classList = new ArrayCollection();
    }

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

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

    /**
     * @return Collection<int, ClassRoom>
     */
    public function getClassList(): Collection
    {
        return $this->classList;
    }

    public function addClassList(ClassRoom $classList): static
    {
        if (!$this->classList->contains($classList)) {
            $this->classList->add($classList);
            $classList->addStudent($this);
        }

        return $this;
    }

    public function removeClassList(ClassRoom $classList): static
    {
        if ($this->classList->removeElement($classList)) {
            $classList->removeStudent($this);
        }

        return $this;
    }

    public function toArrayForClass(): array
    {
        return [
            'id' => $this->getId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'gender' => $this->getGender(),
            'dob' => $this->getDob()->format('d-m-Y'),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'address' => $this->getAddress()
        ];
    }

    public function toArrayForStudent(): array
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
            'classList' => $this->getClassList()->map(fn(ClassRoom $classRoom) => $classRoom->toArrayForStudent())->toArray()
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
        ];
    }
}
