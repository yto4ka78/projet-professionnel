<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTimeImmutable;
use DateTimeInterface;


#[ORM\Entity(repositoryClass: ClubRepository::class)]
#[Vich\Uploadable]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column(nullable: true)]
    private ?int $Owner = null;

    #[ORM\Column(length: 255)]
    private ?string $Region = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'Clubs')]
    private Collection $Users;

    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $avatar = null;
    
    #[Vich\UploadableField(mapping: 'avatars_club', fileNameProperty: 'avatar')]
    private ?File $avatarFile = null;

    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $backGroundAvatar = null;
    
    #[Vich\UploadableField(mapping: 'back_ground_avatars_club', fileNameProperty: 'backGroundAvatar')]
    private ?File $backGroundAvatarFile = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(targetEntity: ClubRequest::class, mappedBy: 'Club', orphanRemoval: true)]
    private Collection $clubRequests;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'club', orphanRemoval: true)]
    private Collection $posts;

    public function __construct()
    {
        $this->updatedAt = new DateTimeImmutable();
        $this->Users = new ArrayCollection();
        $this->clubRequests = new ArrayCollection();
        $this->posts = new ArrayCollection();
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

        /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->backGroundAvatarFile = null;
        $this->avatarFile = null;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getOwner(): ?int
    {
        return $this->Owner;
    }

    public function setOwner(?int $Owner): static
    {
        $this->Owner = $Owner;

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
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(User $user): static
    {
        if (!$this->Users->contains($user)) {
            $this->Users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->Users->removeElement($user);

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
            $clubRequest->setClub($this);
        }

        return $this;
    }

    public function removeClubRequest(ClubRequest $clubRequest): static
    {
        if ($this->clubRequests->removeElement($clubRequest)) {
            // set the owning side to null (unless already changed)
            if ($clubRequest->getClub() === $this) {
                $clubRequest->setClub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setClub($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getClub() === $this) {
                $post->setClub(null);
            }
        }

        return $this;
    }
}
