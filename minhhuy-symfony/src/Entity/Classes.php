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

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'classes')]
    private Collection $students;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Class name cannot be blank")]
    private ?string $className = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Teacher name cannot be blank")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]+$/",
        message: "Teacher name must contain only letters and spaces"
    )]
    private ?string $teacher = null;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            //$student->addClass($this); // Ensure bidirectional consistency
        }
        return $this;
    }

    public function removeStudent(Student $student): static
    {
        $this->students->removeElement($student);

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function setClassName(string $className): static
    {
        $this->className = $className;

        return $this;
    }

    public function getTeacher(): ?string
    {
        return $this->teacher;
    }

    public function setTeacher(string $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public  function toArrayForStudent():array
    {
        return [
            'id' =>$this->getId(),
            'class_name' =>$this->getClassName(),
            'teacher' =>$this->getTeacher()
        ];
    }
}
