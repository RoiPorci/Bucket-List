<?php

namespace App\Entity;

use App\Repository\ReactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReactionRepository::class)
 */
class Reaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner un message!")
     * @Assert\Length(
     *     min=3,
     *     max=250,
     *     minMessage="3 caractères minimum svp!",
     *     maxMessage="250 caractères maximum svp!"
     * )
     * @ORM\Column(type="string", length=250)
     */
    private $message;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner un auteur!")
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="3 caractères minimum svp!",
     *     maxMessage="50 caractères maximum svp!"
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Wish::class, inversedBy="reactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wish;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getWish(): ?Wish
    {
        return $this->wish;
    }

    public function setWish(?Wish $wish): self
    {
        $this->wish = $wish;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

}
