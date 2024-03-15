<?php

namespace App\Entity;

use App\Repository\ClassRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClassRoomRepository::class)]
class ClassRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $class_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $teacher_name = null;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'classList')]
    private Collection $studentList;

    public function __construct()
    {
        $this->studentList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassName(): ?string
    {
        return $this->class_name;
    }

    public function setClassName(string $class_name): static
    {
        $this->class_name = $class_name;

        return $this;
    }

    public function getTeacherName(): ?string
    {
        return $this->teacher_name;
    }

    public function setTeacherName(?string $teacher_name): static
    {
        $this->teacher_name = $teacher_name;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudentList(): Collection
    {
        return $this->studentList;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->studentList->contains($student)) {
            $this->studentList->add($student);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        $this->studentList->removeElement($student);

        return $this;
    }

    public function toArrayForClass(): array
    {
        return [
            'id' => $this->getId(),
            'room_name' => $this->getClassName(),
            'teacher_name' => $this->getTeacherName(),
            'studentList' => $this->getStudentList()->map(fn(Student $student) => $student->toArrayForClass())->toArray()
        ];
    }

    public function toArrayForStudent(): array
    {
        return [
            'id' => $this->getId(),
            'room_name' => $this->getClassName(),
            'teacher_name' => $this->getTeacherName()
        ];
    }

    public static function fieldSetterMap(): array
    {
        return [
            'room_name' => 'setRoomName',
            'teacher_name' => 'setTeacherName'
        ];
    }
}
