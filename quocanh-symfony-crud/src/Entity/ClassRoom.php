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

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $room_name = null;

    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'classRoom')]
    private Collection $student;

    #[ORM\Column(length: 200)]
    private ?string $teacher_name = null;

    public function __construct()
    {
        $this->student = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomName(): ?string
    {
        return $this->room_name;
    }

    public function setRoomName(string $room_name): static
    {
        $this->room_name = $room_name;

        return $this;
    }

    public function getTeacherName(): ?string
    {
        return $this->teacher_name;
    }

    public function setTeacherName(string $teacher_name): static
    {
        $this->teacher_name = $teacher_name;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
            $student->setClassRoom($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->student->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClassRoom() === $this) {
                $student->setClassRoom(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'room_name' => $this->getRoomName(),
            'teacher_name' => $this->getTeacherName(),
            'students' => $this->getStudent()->map(fn(Student $student) => $student->toArray())->toArray()
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
