<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoRepository::class)]
class Todo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column]
    private ?bool $completed = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    // public function setUpdateAt(\DateTimeImmutable $updateAt): static
    // {
    //     $this->updateAt = $updateAt;

    //     return $this;
    // }


    public function setUpdateAt(): self
    {
        $this->updateAt = new DateTimeImmutable("now");

        return $this;
    }


    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    // protected function getDefaults(): array
    // {
    //     return [
    //         'title' => self::faker()->text(255),
    //     ];
    // }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    // public function setCreateAt(\DateTimeImmutable $createAt): static
    // {
    //     $this->createAt = $createAt;

    //     return $this;
    // }

     public function setCreateAt(): self
    {
        $this->createAt = new DateTimeImmutable("now");

        return $this;
    }
}
