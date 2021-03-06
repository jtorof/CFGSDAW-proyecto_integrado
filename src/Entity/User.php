<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank(
        message: 'Email must not be blank',
    )]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Groups(['userInfo'])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[Groups(['userInfo'])]
    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[Assert\Length(
        min: 2,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
    )]
    #[ORM\Column(type: 'string')]
    private $password;

    #[Groups(['userInfo'])]
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: ApiToken::class, orphanRemoval: true)]
    private $apiTokens;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ApiContentUser::class)]
    private $apiContentUsers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ContentApiRequestLog::class)]
    private $contentApiRequestLogs;

    #[ORM\Column(type: 'boolean')]
    private $hasApiDataCopy;

    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
        $this->apiContentUsers = new ArrayCollection();
        $this->contentApiRequestLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setOwner($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getOwner() === $this) {
                $apiToken->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApiContentUser>
     */
    public function getApiContentUsers(): Collection
    {
        return $this->apiContentUsers;
    }

    public function addApiContentUser(ApiContentUser $apiContentUser): self
    {
        if (!$this->apiContentUsers->contains($apiContentUser)) {
            $this->apiContentUsers[] = $apiContentUser;
            $apiContentUser->setUser($this);
        }

        return $this;
    }

    public function removeApiContentUser(ApiContentUser $apiContentUser): self
    {
        if ($this->apiContentUsers->removeElement($apiContentUser)) {
            // set the owning side to null (unless already changed)
            if ($apiContentUser->getUser() === $this) {
                $apiContentUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContentApiRequestLog>
     */
    public function getContentApiRequestLogs(): Collection
    {
        return $this->contentApiRequestLogs;
    }

    public function addContentApiRequestLog(ContentApiRequestLog $contentApiRequestLog): self
    {
        if (!$this->contentApiRequestLogs->contains($contentApiRequestLog)) {
            $this->contentApiRequestLogs[] = $contentApiRequestLog;
            $contentApiRequestLog->setUser($this);
        }

        return $this;
    }

    public function removeContentApiRequestLog(ContentApiRequestLog $contentApiRequestLog): self
    {
        if ($this->contentApiRequestLogs->removeElement($contentApiRequestLog)) {
            // set the owning side to null (unless already changed)
            if ($contentApiRequestLog->getUser() === $this) {
                $contentApiRequestLog->setUser(null);
            }
        }

        return $this;
    }

    public function getHasApiDataCopy(): ?bool
    {
        return $this->hasApiDataCopy;
    }

    public function setHasApiDataCopy(bool $hasApiDataCopy): self
    {
        $this->hasApiDataCopy = $hasApiDataCopy;

        return $this;
    }

    #[ORM\PrePersist]
    public function setHasApiDataCopyValue()
    {
        $this->hasApiDataCopy = false;
    }
}
