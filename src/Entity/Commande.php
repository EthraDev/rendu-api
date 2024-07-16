<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    /**
     * @var Collection<int, Boisson>
     */
    #[ORM\ManyToMany(targetEntity: Boisson::class, inversedBy: 'commandes')]
    private Collection $listesBoissons;

    #[ORM\Column]
    private ?int $tableNumber = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $serveur = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $barman = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    public function __construct()
    {
        $this->listesBoissons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return Collection<int, Boisson>
     */
    public function getListesBoissons(): Collection
    {
        return $this->listesBoissons;
    }

    public function addListesBoisson(Boisson $listesBoisson): static
    {
        if (!$this->listesBoissons->contains($listesBoisson)) {
            $this->listesBoissons->add($listesBoisson);
        }

        return $this;
    }

    public function removeListesBoisson(Boisson $listesBoisson): static
    {
        $this->listesBoissons->removeElement($listesBoisson);

        return $this;
    }

    public function getTableNumber(): ?int
    {
        return $this->tableNumber;
    }

    public function setTableNumber(int $tableNumber): static
    {
        $this->tableNumber = $tableNumber;

        return $this;
    }

    public function getServeur(): ?User
    {
        return $this->serveur;
    }

    public function setServeur(?User $serveur): static
    {
        $this->serveur = $serveur;

        return $this;
    }

    public function getBarman(): ?User
    {
        return $this->barman;
    }

    public function setBarman(?User $barman): static
    {
        $this->barman = $barman;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
