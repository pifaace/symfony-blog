<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NotificationType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notificationType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserNotification", mappedBy="notification")
     */
    private $userNotifications;

    /**
     * @ORM\Column(name="target_link", type="string")
     * @ORM\JoinColumn(nullable=false)
     */
    private $targetLink;

    public function __construct()
    {
        $this->userNotifications = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getNotificationType(): NotificationType
    {
        return $this->notificationType;
    }

    public function setNotificationType(NotificationType $notificationType): self
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(UserInterface $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getTargetLink(): string
    {
        return $this->targetLink;
    }

    public function setTargetLink(string $targetLink): self
    {
        $this->targetLink = $targetLink;

        return $this;
    }

    public function addUserNotification(UserNotification $userNotification): void
    {
        $userNotification->setNotification($this);
        if (!$this->userNotifications->contains($userNotification)) {
            $this->userNotifications->add($userNotification);
        }
    }

    public function removeUserNotification(UserNotification $userNotification): void
    {
        $this->userNotifications->removeElement($userNotification);
    }

    public function getComments(): ?Collection
    {
        return $this->userNotifications;
    }
}
