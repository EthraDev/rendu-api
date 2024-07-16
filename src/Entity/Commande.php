<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN')"),
        new Get(security: "is_granted('ROLE_SERVEUR') or is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN')"),
        new Post(security: "is_granted('ROLE_SERVEUR') or is_granted('ROLE_BARMAN') or is_granted('ROLE_PATRON')"),
        new Patch(security: "is_granted('ROLE_SERVEUR') and object.getStatus() != 'payée'"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    forceEager: false,
)]
#[Get()]
#[GetCollection()]
#[Patch()]
#[Delete()]
#[Post()]

#[ApiFilter(SearchFilter::class, properties
    : ['status' => 'exact']
)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read'])]
    private ?\DateTimeInterface $createdDate = null;

    /**
     * @var Collection<int, Boisson>
     */
    #[ORM\ManyToMany(targetEntity: Boisson::class, inversedBy: 'commandes')]
    #[Groups(['read', 'write'])]
    private Collection $listeBoissons;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private ?int $tableNumber = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?User $serveur = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?User $barman = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $status = null;

    public function __construct()
    {
        $this->listeBoissons = new ArrayCollection();
        $this->createdDate = new \DateTime();
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
    public function getListeBoissons(): Collection
    {
        return $this->listeBoissons;
    }

    public function addListeBoisson(Boisson $listeBoisson): static
    {
        if (!$this->listeBoissons->contains($listeBoisson)) {
            $this->listeBoissons->add($listeBoisson);
        }

        return $this;
    }

    public function removeListeBoisson(Boisson $listeBoisson): static
    {
        $this->listeBoissons->removeElement($listeBoisson);

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
