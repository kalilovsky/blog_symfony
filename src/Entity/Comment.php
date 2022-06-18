<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer" , name="idcomment")
     * @Groups("comment")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups("comment")
     */
    private $contentcomment;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("comment")
     */
    private $datecreated;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("comment")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups("comment")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="comments")
     * @ORM\JoinColumn(name="idusers",referencedColumnName="idusers",nullable=false)
     * @Groups("comment")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     * @ORM\JoinColumn(name="idarticle",referencedColumnName="idarticle",nullable=false)
     * @Groups("comment")
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentcomment(): ?string
    {
        return $this->contentcomment;
    }

    public function setContentcomment(string $contentcomment): self
    {
        $this->contentcomment = $contentcomment;

        return $this;
    }

    public function getDatecreated(): ?\DateTimeInterface
    {
        return $this->datecreated;
    }

    public function setDatecreated(\DateTimeInterface $datecreated): self
    {
        $this->datecreated = $datecreated;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
