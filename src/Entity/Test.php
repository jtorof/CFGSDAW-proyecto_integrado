<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Action\NotFoundAction;


#[ORM\Entity(repositoryClass: TestRepository::class)]
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
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['api:read', 'api:write'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\ManyToOne(targetEntity: ApiContentUser::class, inversedBy: 'tests')]
    private $apiContentUser;

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
