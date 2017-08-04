<?php

namespace AppBundle\Entity;

use AppBundle\Entity\UnlockBadge;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Badge
 *
 * @ORM\Table(name="badge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BadgeRepository")
 */
class Badge
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="actionName", type="string", length=255)
     */
    private $actionName;

    /**
     * @var integer
     *
     * @ORM\Column(name="actionCount", type="integer")
     */
    private $actionCount;

    /**
     * @var Badge
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UnlockBadge", mappedBy="badge")
     */
    private $unlockBadge;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->unlockBadge = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Badge
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Badge
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set actionName
     *
     * @param string $actionName
     *
     * @return Badge
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;

        return $this;
    }

    /**
     * Get actionName
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return int
     */
    public function getActionCount()
    {
        return $this->actionCount;
    }

    /**
     * @param int $actionCount
     */
    public function setActionCount($actionCount)
    {
        $this->actionCount = $actionCount;
    }

    /**
     * Add unlockBadge
     *
     * @param UnlockBadge $unlockBadge
     *
     * @return Badge
     */
    public function addUnlockBadge(UnlockBadge $unlockBadge)
    {
        $this->unlockBadge[] = $unlockBadge;

        return $this;
    }

    /**
     * Remove unlockBadge
     *
     * @param UnlockBadge $unlockBadge
     */
    public function removeUnlockBadge(UnlockBadge $unlockBadge)
    {
        $this->unlockBadge->removeElement($unlockBadge);
    }

    /**
     * Get unlockBadge
     *
     * @return Collection
     */
    public function getUnlockBadge()
    {
        return $this->unlockBadge;
    }
}
