<?php

namespace App\Entity;

use App\Repository\SinhvienRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SinhvienRepository::class)]
class Sinhvien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $masv = null;

    #[ORM\Column(length: 255)]
    private ?string $hoten = null;

    #[ORM\Column(length: 255)]
    private ?string $lop = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMasv(): ?string
    {
        return $this->masv;
    }

    public function setMasv(string $masv): static
    {
        $this->masv = $masv;

        return $this;
    }

    public function getHoten(): ?string
    {
        return $this->hoten;
    }

    public function setHoten(string $hoten): static
    {
        $this->hoten = $hoten;

        return $this;
    }

    public function getLop(): ?string
    {
        return $this->lop;
    }

    public function setLop(string $lop): static
    {
        $this->lop = $lop;

        return $this;
    }
}
