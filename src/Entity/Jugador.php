<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorRepository::class)]
class Jugador
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $jugador_nombre = null;

    #[ORM\Column]
    private ?int $edad = null;

    #[ORM\Column(length: 255)]
    private ?string $posicion = null;

    #[ORM\Column]
    private ?int $equipo_id = null;

    #[ORM\Column(length: 255)]
    private ?string $imagen_url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJugadorNombre(): ?string
    {
        return $this->jugador_nombre;
    }

    public function setJugadorNombre(string $jugador_nombre): static
    {
        $this->jugador_nombre = $jugador_nombre;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): static
    {
        $this->edad = $edad;

        return $this;
    }

    public function getPosicion(): ?string
    {
        return $this->posicion;
    }

    public function setPosicion(string $posicion): static
    {
        $this->posicion = $posicion;

        return $this;
    }

    public function getEquipoId(): ?int
    {
        return $this->equipo_id;
    }

    public function setEquipoId(int $equipo_id): static
    {
        $this->equipo_id = $equipo_id;

        return $this;
    }

    public function getImagenUrl(): ?string
    {
        return $this->imagen_url;
    }

    public function setImagenUrl(string $imagen_url): static
    {
        $this->imagen_url = $imagen_url;

        return $this;
    }
}
