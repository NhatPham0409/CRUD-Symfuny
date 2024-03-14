<?php

namespace App\Entity;

use App\Repository\InfoStudentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: InfoStudentRepository::class)]
#[ORM\Table(name: "info_student")]
class InfoStudent
{


    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    /**
     * @param string|null $name
     * @param string|null $email
     * @param string|null $address
     * @param string|null $phone
     */
    public function __construct(?string $name, ?string $email, ?string $address, ?string $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->phone = $phone;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
