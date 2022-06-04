<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApiContentAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Action\NotFoundAction;


#[ORM\Entity(repositoryClass: ApiContentAddressRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'method' => 'get',
            'controller' => NotFoundAction::class,
            'read' => false,
            'output' => false,
        ],
    ],
    itemOperations: [
        'get' => [
            'controller' => NotFoundAction::class,
            'read' => false,
            'output' => false,
        ],
    ],
)]
class ApiContentAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $address;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $postalCode;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $country;

    #[ORM\OneToOne(targetEntity: ApiContentUser::class, inversedBy: "address")]
    private $apiContentUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getApiContentUser(): ?ApiContentUser
    {
        return $this->apiContentUser;
    }

    public function setApiContentUser(?ApiContentUser $apiContentUser): self
    {
        $this->apiContentUser = $apiContentUser;

        return $this;
    }

    public function __clone() {
        if ($this->id) {
            $this->id = null;
        }
    }
}
