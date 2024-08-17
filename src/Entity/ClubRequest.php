<?php

namespace App\Entity;

use App\Repository\ClubRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRequestRepository::class)]
class ClubRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'clubRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\ManyToOne(inversedBy: 'clubRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $Club = null;

    #[ORM\Column(nullable: true)]
    private ?bool $approved = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->Club;
    }

    public function setClub(?Club $Club): static
    {
        $this->Club = $Club;

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }
}
