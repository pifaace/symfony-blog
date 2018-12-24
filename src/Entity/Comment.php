<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
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
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createAt", type="datetime")
     */
    private $createAt;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChildComment", mappedBy="comment")
     * @ORM\JoinColumn(nullable=true)
     */
    private $childComments;

    public function __construct()
    {
        $this->childComments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreateAt()
    {
        $this->createAt = new \DateTime();
    }

    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|ChildComment[]
     */
    public function getChildComments(): Collection
    {
        return $this->childComments;
    }

    public function addChildComment(ChildComment $childComment): self
    {
        if (!$this->childComments->contains($childComment)) {
            $this->childComments[] = $childComment;
            $childComment->setComment($this);
        }

        return $this;
    }

    public function removeChildComment(ChildComment $childComment): self
    {
        if ($this->childComments->contains($childComment)) {
            $this->childComments->removeElement($childComment);
            // set the owning side to null (unless already changed)
            if ($childComment->getComment() === $this) {
                $childComment->setComment(null);
            }
        }

        return $this;
    }
}
