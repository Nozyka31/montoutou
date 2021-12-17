<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationsRepository::class)
 */
class Reservations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gardien_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client_id;

    /**
     * @ORM\ManyToOne(targetEntity=Announces::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $announce_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getGardienId(): ?User
    {
        return $this->gardien_id;
    }

    public function setGardienId(?User $gardien_id): self
    {
        $this->gardien_id = $gardien_id;

        return $this;
    }

    public function getClientId(): ?User
    {
        return $this->client_id;
    }

    public function setClientId(?User $client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getAnnounceId(): ?Announces
    {
        return $this->announce_id;
    }

    public function setAnnounceId(?Announces $announce_id): self
    {
        $this->announce_id = $announce_id;

        return $this;
    }
}
