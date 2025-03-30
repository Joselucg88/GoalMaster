<?php

namespace App\Entity;

use App\Repository\InscripcionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscripcionRepository::class)]
class Inscripcion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $equipo_id = null;

    #[ORM\Column]
    private ?int $competicion_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_inscripcion = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCompeticionId(): ?int
    {
        return $this->competicion_id;
    }

    public function setCompeticionId(int $competicion_id): static
    {
        $this->competicion_id = $competicion_id;

        return $this;
    }

    public function getFechaInscripcion(): ?\DateTimeInterface
    {
        return $this->fecha_inscripcion;
    }

    public function setFechaInscripcion(\DateTimeInterface $fecha_inscripcion): static
    {
        $this->fecha_inscripcion = $fecha_inscripcion;

        return $this;
    }
}
