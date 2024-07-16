<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use App\State\UserPasswordHasherProcessor;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_PATRON')", securityMessage: 'You are not allowed to get users'),
        new Post(processor: UserPasswordHasherProcessor::class),
        new Get(security: "is_granted('ROLE_PATRON') or object == user", securityMessage: 'You are not allowed to get this user'),
        new Put(processor: UserPasswordHasherProcessor::class, security: "is_granted('ROLE_PATRON') or object == user", securityMessage: 'You are not allowed to edit this user'),
        new Patch(processor: UserPasswordHasherProcessor::class, security: "is_granted('ROLE_PATRON') or object == user", securityMessage: 'You are not allowed to edit this user'),
        new Delete(security: "is_granted('ROLE_PATRON') or object == user", securityMessage: 'You are not allowed to delete this user'),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[Get()]
#[Post()]
#[GetCollection()]
#[Patch()]
#[Delete()]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $uuid = null;

    #[ORM\Column(length: 100)]
    #[Groups('read')]
    private ?string $password = null;

    #[Groups('write')]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read', 'write'])]
    private ?string $role = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'serveur')]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    /**
     * @var Collection<int, Commande>
     */
    // #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'serveur')]
    // private Collection $commandes;

    // public function __construct()
    // {
    //     $this->commandes = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
 
        return $this;
    }

    // /**
    //  * @return Collection<int, Commande>
    //  */
    // public function getCommandes(): Collection
    // {
    //     return $this->commandes;
    // }

    // public function addCommande(Commande $commande): static
    // {
    //     if (!$this->commandes->contains($commande)) {
    //         $this->commandes->add($commande);
    //         $commande->setServeur($this);
    //     }

    //     return $this;
    // }

    // public function removeCommande(Commande $commande): static
    // {
    //     if ($this->commandes->removeElement($commande)) {
    //         // set the owning side to null (unless already changed)
    //         if ($commande->getServeur() === $this) {
    //             $commande->setServeur(null);
    //         }
    //     }

    //     return $this;
    // }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    public function getRoles(): array
    {
        $role = $this->role;

        return [$role];
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setServeur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getServeur() === $this) {
                $commande->setServeur(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    public function getRoles(): array
    {
        $role = $this->role;

        return [$role];
    }
}
