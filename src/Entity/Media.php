<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\ApiProperty;
use App\Controller\CreateMediaObjectAction;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    types: ['https://schema.org/MediaObject'],
    forceEager: false,
    operations: [
        new Get(),
        new GetCollection(),
        new Delete(),
        new Post(
            controller: CreateMediaObjectAction::class,
            deserialize: false,
            validationContext: ['groups' => ['Default', 'write']],
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            )
        ),
    ]
)]

class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Vich\UploadableField(mapping: 'media_object', fileNameProperty: 'filePath')]
    #[Assert\NotNull(groups: ['write'])]
    private ?string $file = null;

    #[ORM\OneToOne(mappedBy: 'photo', cascade: ['persist', 'remove'])]
    private ?Boisson $boisson = null;

    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    #[Groups(['read'])]
    public ?string $contentUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getBoisson(): ?Boisson
    {
        return $this->boisson;
    }

    public function setBoisson(?Boisson $boisson): static
    {
        // unset the owning side of the relation if necessary
        if ($boisson === null && $this->boisson !== null) {
            $this->boisson->setPhoto(null);
        }

        // set the owning side of the relation if necessary
        if ($boisson !== null && $boisson->getPhoto() !== $this) {
            $boisson->setPhoto($this);
        }

        $this->boisson = $boisson;

        return $this;
    }
}