<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User.
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(name="role", type="array")
     */
    private $role;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set username.
     *
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * Get username.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Get password.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set email.
     *
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setRole(array $role): void
    {
        $this->role = $role;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $role = $this->role;

        if (empty($role)) {
            $role[] = 'ROLE_USER';
        }

        return array_unique($role);
    }

    public function serialize(): string
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

    /**
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
