<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(nullable:true, length:255)]
    private ?string $apellidos = null;

    #[ORM\Column]
    private ?int $telefono = null;

    #[ORM\Column(nullable:true, length:255)]
    private ?string $direccion = null;


     #[ORM\OneToMany(targetEntity: Incidencia::class, mappedBy: 'cliente', orphanRemoval: true)]
     private Collection $incidencias;

    public function __construct()
    {
        $this->incidencias = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }


     /**
     * @return Collection<int, Incidencia>
     */
     public function getIncidencias(): Collection
     {
         return $this->incidencias;
     }

 
    //  public function removeIncidencia(Incidencia $incidencia): static
    //  {
    //      if ($this->incidencias->removeElement($incidencia)) {
    //          // set the owning side to null (unless already changed)
    //          if ($incidencia->getCliente() === $this) { // Corregir aquí
    //              $incidencia->setCliente(null); // Corregir aquí
    //          }
    //      }
 
    //      return $this;
    //  }

}
