<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class Users implements UserInterface, \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $ip_address = null;

    #[ORM\Column(length: 25)]
    private ?string $username = null;

    #[ORM\Column(length: 25)]
    private ?string $firstname = null;

    #[ORM\Column(length: 25)]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?bool $email_verified = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private ?int $postal_code = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private ?array $role = null;

    #[ORM\Column(length: 1500, nullable: true)]
    private ?string $remember_token = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $terms_accepted = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $newsletter_subscribed = false;

    // New phone_number property
    #[ORM\Column(length: 20, nullable: true)] // Adjust length as needed
    private ?string $phone_number = null;

    /**
     * @var Collection<int, Attendees>
     */
    #[ORM\OneToMany(targetEntity: Attendees::class, mappedBy: 'users', orphanRemoval: true)]
    private Collection $attendee;

    public function __construct()
    {
        $this->attendee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailVerified(): ?bool
    {
        return $this->email_verified;
    }

    public function setEmailVerified(?bool $email_verified): self
    {
        $this->email_verified = $email_verified;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(?int $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        // Ensure the user always has at least the ROLE_USER
        $roles = $this->role;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRole(?array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    public function setRememberToken(?string $remember_token): self
    {
        $this->remember_token = $remember_token;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        $this->remember_token = null;
    }

    public function isTermsAccepted(): bool
    {
        return $this->terms_accepted;
    }

    public function setTermsAccepted(bool $terms_accepted): self
    {
        $this->terms_accepted = $terms_accepted;
        return $this;
    }

    public function isNewsletterSubscribed(): bool
    {
        return $this->newsletter_subscribed;
    }

    public function setNewsletterSubscribed(bool $newsletter_subscribed): self
    {
        $this->newsletter_subscribed = $newsletter_subscribed;
        return $this;
    }

    // Getter and Setter for phone_number
    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * @return Collection<int, Attendees>
     */
    public function getAttendee(): Collection
    {
        return $this->attendee;
    }

    public function addAttendee(Attendees $attendee): static
    {
        if (!$this->attendee->contains($attendee)) {
            $this->attendee->add($attendee);
            $attendee->setUsers($this);
        }

        return $this;
    }

    public function removeAttendee(Attendees $attendee): static
    {
        if ($this->attendee->removeElement($attendee)) {
            // set the owning side to null (unless already changed)
            if ($attendee->getUsers() === $this) {
                $attendee->setUsers(null);
            }
        }

        return $this;
    }
}
