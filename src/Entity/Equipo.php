<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipoRepository::class)]
class Equipo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $equipo_nombre = null;

    #[ORM\Column]
    private ?int $manager_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipoNombre(): ?string
    {
        return $this->equipo_nombre;
    }

    public function setEquipoNombre(string $equipo_nombre): static
    {
        $this->equipo_nombre = $equipo_nombre;

        return $this;
    }

    public function getManagerId(): ?int
    {
        return $this->manager_id;
    }

    public function setManagerId(int $manager_id): static
    {
        $this->manager_id = $manager_id;

        return $this;
    }
}
