<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApiContentPhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Action\NotFoundAction;


#[ORM\Entity(repositoryClass: ApiContentPhoneRepository::class)]
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
class ApiContentPhone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $number;

    #[ORM\ManyToOne(targetEntity: ApiContentUser::class, inversedBy: 'phones')]
    private $apiContentUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

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
}
