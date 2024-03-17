<?php

namespace App\Entity;

use App\Repository\ClassesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClassesRepository::class)]
class Classes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'StudentId')]
    private Collection $ClassId;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Class name cannot be blank")]
    private ?string $ClassName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Teacher name cannot be blank")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]+$/",
        message: "Teacher name must contain only letters and spaces"
    )]
    private ?string $Teacher = null;

    public function __construct()
    {
        $this->ClassId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getClassId(): Collection
    {
        return $this->ClassId;
    }

    public function addClassId(Student $classId): static
    {
        if (!$this->ClassId->contains($classId)) {
            $this->ClassId->add($classId);
        }

        return $this;
    }

    public function removeClassId(Student $classId): static
    {
        $this->ClassId->removeElement($classId);

        return $this;
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

    public function getTeacher(): ?string
    {
        return $this->Teacher;
    }

    public function setTeacher(string $Teacher): static
    {
        $this->Teacher = $Teacher;

        return $this;
    }
}
