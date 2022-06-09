<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApiContentUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Annotation\ApiProperty;

#[ORM\Entity(repositoryClass: ApiContentUserRepository::class)]
#[ApiResource(
    formats: [
        'json'
    ],
    attributes: [
        "security" => "is_granted('IS_AUTHENTICATED_FULLY')",   //Likely redundant since it's being controlled in security.yaml
    ],
    normalizationContext: [
        'groups' => ['api:read'],
        'swagger_definition_name' => 'Read',
    ],
    denormalizationContext: [
        'groups' => ['api:write'],
        'swagger_definition_name' => 'Write',
    ],
    collectionOperations: [
        'get' => [
            'path' => '/users',
        ],
        'post' => [
            'path' => '/users',
        ],
    ],
    itemOperations: [
        'get' => [
            'path' => '/user/{publicId}',
        ],
        'put' => [
            'path' => '/user/{publicId}',
        ],
        'patch' => [
            'path' => '/user/{publicId}',
        ],
        'delete' => [
            'path' => '/user/{publicId}',
        ],
    ],
)]
class ApiContentUser
{
    #[ApiProperty(identifier: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[SerializedName('id')]
    #[Groups(['api:read'])]
    #[ApiProperty(identifier: true)]
    #[ORM\Column(type: 'integer')]
    private $publicId;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;
    
    #[SerializedName('last-name')]
    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastName;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\OneToOne(mappedBy: 'apiContentUser', targetEntity: ApiContentAddress::class, cascade: ['persist', 'remove'], fetch: 'EAGER', orphanRemoval: true)]
    private $address;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\OneToMany(mappedBy: 'apiContentUser', targetEntity: ApiContentPhone::class, cascade: ['persist', 'remove'], fetch: 'EAGER', orphanRemoval: true)]
    // #[ORM\OneToMany(mappedBy: 'apiContentUser', targetEntity: ApiContentPhone::class, cascade: ['persist', 'remove'])]
    // #[ORM\OneToMany(mappedBy: 'apiContentUser', targetEntity: ApiContentPhone::class)]
    private $phones;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'apiContentUsers')]
    private $user;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?ApiContentAddress
    {
        return $this->address;
    }

    public function setAddress(?ApiContentAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, ApiContentPhone>
     */
    // public function getPhones(): Collection
    // {
    //     return $this->phones;
    // }

    /**
     * Custom method so API Platform write operations work.
     */
    public function getPhones()
    {
        return $this->phones->getValues();
    }

    public function addPhone(ApiContentPhone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setApiContentUser($this);
        }

        return $this;
    }

    public function removePhone(ApiContentPhone $phone): self
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getApiContentUser() === $this) {
                $phone->setApiContentUser(null);
            }
        }

        return $this;
    }

    public function getPublicId(): ?int
    {
        return $this->publicId;
    }

    public function setPublicId(int $publicId): self
    {
        $this->publicId = $publicId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __clone() {
        if ($this->id) {
            $this->id = null;
            $this->address = (clone $this->address)->setApiContentUser($this);
            $this->phones = $this->phones->map(function ($item) { return (clone $item)->setApiContentUser($this);});
        }
    }
}
