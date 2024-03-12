<?php

namespace App\Entity;

use App\Repository\IncidenciaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: IncidenciaRepository::class)]
class Incidencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_creacion = null;

    #[ORM\Column(length: 200)]
    private ?string $estado = null;

     #[ORM\ManyToOne(targetEntity: Cliente::class)]
     private ?Cliente $cliente = null;

     #[ORM\ManyToOne(targetEntity: Usuario::class)]
     private ?Usuario $usuario = null;

     public function __construct()
     {
         // Configurar la fecha de creación con la fecha actual
         $this->fecha_creacion = new \DateTime();
     }
 


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fecha_creacion): static
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }


    /**
     * @return Collection<int, Cliente>
     */
     public function getClientes(): Collection
     {
         return $this->clientes;
     }

     public function getCliente(): ?Cliente
     {
         return $this->cliente;
     }
     

     public function setCliente(?Cliente $cliente): self
{
    $this->cliente = $cliente;

    return $this;
}

public function getUsuario(): ?Usuario
{
    return $this->usuario;
}

public function setUsuario(?Usuario $usuario): self
{
    $this->usuario = $usuario;
    return $this;
}

}
