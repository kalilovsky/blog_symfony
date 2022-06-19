<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="idmessage")
     * @Groups("message")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("message")
     */
    private $senderemail;

    /**
     * @ORM\Column(type="text")
     * @Groups("message")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("message")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("message")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderemail(): ?string
    {
        return $this->senderemail;
    }

    public function setSenderemail(string $senderemail): self
    {
        $this->senderemail = $senderemail;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

}
