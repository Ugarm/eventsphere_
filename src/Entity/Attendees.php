<?php

namespace App\Entity;

use App\Repository\AttendeesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendeesRepository::class)]
#[ORM\Table(name: "attendees")]
class Attendees
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "event_id", nullable: true)]
    private ?int $eventId = null;

    #[ORM\Column(name: "meetup_id", nullable: true)]
    private ?int $meetupId = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $updated_at;

    #[ORM\ManyToOne(inversedBy: 'attendee')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): static
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function touch(): static
    {
        $this->updated_at = new \DateTimeImmutable();
        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }
    public function getMeetupId(): ?int
    {
        return $this->meetupId;
    }

    public function setMeetupId(?int $meetupId): self
    {
        $this->meetupId = $meetupId;

        return $this;
    }
}
