<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="signin.unique_email"
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="signin.unique_username"
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     *
     * @Assert\NotBlank(
     *     message="signin.required_username"
     * )
     * @Assert\Length(
     *     min=3,
     *     minMessage="signin.min_char_username"
     * )
     * @Assert\Regex(
     *     pattern="/^[\pL\pM\pN_-]+$/u",
     *     match=true,
     *     message="signin.wrong_char_username"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="provider_id", type="integer", nullable=true)
     */
    private $providerId;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="signin.required_password"
     * )
     * @Assert\Length(
     *     min=6,
     *     max=4096,
     *     minMessage="signin.min_char_password"
     * )
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true)
     * @Assert\NotBlank(
     *     message="signin.required_email"
     * )
     * @Assert\Email(
     *     message="signin.wrong_format_email"
     * )
     */
    private $email;

    /**
     * @var array
     *
     * @ORM\Column(name="role", type="array")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="reset_password_token", type="string", unique=true, nullable=true)
     */
    private $resetPasswordToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="token_expiration_date", type="datetime", nullable=true)
     */
    private $tokenExpirationDate;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", cascade={"persist", "remove"})
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array
    {
        $role = $this->role;

        if (empty($role)) {
            $role[] = 'ROLE_USER';
        }

        return array_unique($role);
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->username,
            $this->password) = unserialize($serialized);
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken = null): self
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    public function getTokenExpirationDate(): \DateTime
    {
        return $this->tokenExpirationDate;
    }

    public function setTokenExpirationDate(): self
    {
        $date = new \DateTime();
        $date->add(new \DateInterval('PT1H'));
        $this->tokenExpirationDate = $date;

        return $this;
    }

    public function getProviderId(): ?int
    {
        return $this->providerId;
    }

    public function setProviderId(int $providerId): void
    {
        $this->providerId = $providerId;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}
