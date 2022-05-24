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
    normalizationContext: ['groups' => ['api:read']],
    denormalizationContext: ['groups' => ['api:write']],
    collectionOperations: [
        'get' => [
            'path' => '/user',
        ],
        'post' => [
            'path' => '/user',
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

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\OneToOne(targetEntity: ApiContentAddress::class)]
    // #[ORM\OneToOne(targetEntity: ApiContentAddress::class, cascade: ['persist', 'remove'])]
    private $address;

    #[Groups(['api:read', 'api:write'])]
    // #[ORM\OneToMany(mappedBy: 'apiContentUser', targetEntity: ApiContentPhone::class, cascade: ['persist', 'remove'])]
    #[ORM\OneToMany(mappedBy: 'apiContentUser', targetEntity: ApiContentPhone::class)]
    private $phones;

    #[Groups(['api:read', 'api:write'])]
    #[ApiProperty(identifier: true)]
    #[ORM\Column(type: 'integer')]
    private $publicId;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'apiContentUsers')]
    private $user;
    
    // #[Groups(['api:read', 'api:write'])]
    // #[ORM\OneToMany(mappedBy: 'apiContentUser', targetEntity: Test::class, cascade: ['persist'])]
    #[ORM\OneToMany(mappedBy: 'apiContentUser', targetEntity: Test::class)]
    private $tests;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
        $this->tests = new ArrayCollection();
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
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * Custom method so API Platform write operations work
     */
    // public function getPhones()
    // {
    //     return $this->phones->getValues();
    // }

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

    /**
     * @return Collection<int, Test>
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }
    // public function getTests()
    // {
    //     return $this->tests->getValues();
    // }

    public function addTest(Test $test): self
    {
        if (!$this->tests->contains($test)) {
            $this->tests[] = $test;
            $test->setApiContentUser($this);
        }

        return $this;
    }

    public function removeTest(Test $test): self
    {
        if ($this->tests->removeElement($test)) {
            // set the owning side to null (unless already changed)
            if ($test->getApiContentUser() === $this) {
                $test->setApiContentUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->tests;
    }
}
