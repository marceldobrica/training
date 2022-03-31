<?php

namespace App\Entity;

use App\Repository\UserResetPasswordTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserResetPasswordTokenRepository::class)
 * @ORM\Table(name="user_reset_password_token")
 */
class UserResetPasswordToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="uuid", unique=true, nullable=true)
     */
    private Uuid $resetToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getResetToken(): ?Uuid
    {
        return $this->resetToken;
    }

    public function setResetToken(Uuid $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
