<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTimeImmutable;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet e-mail est déjà utilisée')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(nullable: true)]
    private ?int $Participation = null;

    #[ORM\Column(length: 255)]
    private ?string $Region = null;

    #[ORM\ManyToMany(targetEntity: Club::class, mappedBy: 'Users')]
    private Collection $Clubs;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $avatar = null;
    
    #[Vich\UploadableField(mapping: 'avatars_user', fileNameProperty: 'avatar')]
    private ?File $avatarFile = null;

    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $backGroundAvatar = null;
    
    #[Vich\UploadableField(mapping: 'back_ground_avatars_user', fileNameProperty: 'backGroundAvatar')]
    private ?File $backGroundAvatarFile = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(targetEntity: ClubRequest::class, mappedBy: 'User', orphanRemoval: true)]
    private Collection $clubRequests;

    public function __construct()
    {
        $this->updatedAt = new DateTimeImmutable();
        $this->Clubs = new ArrayCollection();
        $this->clubRequests = new ArrayCollection();
    }

    public function setAvatarFile(?File $avatarFile = null): void
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    //

    public function setBackGroundAvatarFile(?File $backGroundAvatarFile = null): void
    {
        $this->backGroundAvatarFile = $backGroundAvatarFile;

        if (null !== $backGroundAvatarFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getBackGroundAvatarFile(): ?File
    {
        return $this->backGroundAvatarFile;
    }

    public function setBackGroundAvatar(?string $backGroundAvatar): void
    {
        $this->backGroundAvatar = $backGroundAvatar;
    }

    public function getBackGroundAvatar(): ?string
    {
        return $this->backGroundAvatar;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->backGroundAvatarFile = null;
        $this->avatarFile = null;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getParticipation(): ?int
    {
        return $this->Participation;
    }

    public function setParticipation(?int $Participation): static
    {
        $this->Participation = $Participation;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): static
    {
        $this->Region = $Region;

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->Clubs;
    }

    public function addClub(Club $club): static
    {
        if (!$this->Clubs->contains($club)) {
            $this->Clubs->add($club);
            $club->addUser($this);
        }

        return $this;
    }

    public function removeClub(Club $club): static
    {
        if ($this->Clubs->removeElement($club)) {
            $club->removeUser($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ClubRequest>
     */
    public function getClubRequests(): Collection
    {
        return $this->clubRequests;
    }

    public function addClubRequest(ClubRequest $clubRequest): static
    {
        if (!$this->clubRequests->contains($clubRequest)) {
            $this->clubRequests->add($clubRequest);
            $clubRequest->setUser($this);
        }

        return $this;
    }

    public function removeClubRequest(ClubRequest $clubRequest): static
    {
        if ($this->clubRequests->removeElement($clubRequest)) {
            // set the owning side to null (unless already changed)
            if ($clubRequest->getUser() === $this) {
                $clubRequest->setUser(null);
            }
        }

        return $this;
    }

    
}
