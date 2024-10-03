<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    /**
     * @var Collection<int, JobOffer>
     */
    #[ORM\OneToMany(targetEntity: JobOffer::class, mappedBy: 'app_user')]
    private Collection $jobOffers;

    /**
     * @var Collection<int, LinkedInMessage>
     */
    #[ORM\OneToMany(targetEntity: LinkedInMessage::class, mappedBy: 'app_user')]
    private Collection $linkedInMessages;

    /**
     * @var Collection<int, CoverLetter>
     */
    #[ORM\OneToMany(targetEntity: CoverLetter::class, mappedBy: 'app_user')]
    private Collection $coverLetters;

    public function __construct()
    {
        $this->jobOffers = new ArrayCollection();
        $this->linkedInMessages = new ArrayCollection();
        $this->coverLetters = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return Collection<int, JobOffer>
     */
    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
    }

    public function addJobOffer(JobOffer $jobOffer): static
    {
        if (!$this->jobOffers->contains($jobOffer)) {
            $this->jobOffers->add($jobOffer);
            $jobOffer->setAppUser($this);
        }
        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): static
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getAppUser() === $this) {
                $jobOffer->setAppUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, LinkedInMessage>
     */
    public function getLinkedInMessages(): Collection
    {
        return $this->linkedInMessages;
    }

    public function addLinkedInMessage(LinkedInMessage $linkedInMessage): static
    {
        if (!$this->linkedInMessages->contains($linkedInMessage)) {
            $this->linkedInMessages->add($linkedInMessage);
            $linkedInMessage->setAppUser($this);
        }
        return $this;
    }

    public function removeLinkedInMessage(LinkedInMessage $linkedInMessage): static
    {
        if ($this->linkedInMessages->removeElement($linkedInMessage)) {
            // set the owning side to null (unless already changed)
            if ($linkedInMessage->getAppUser() === $this) {
                $linkedInMessage->setAppUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, CoverLetter>
     */
    public function getCoverLetters(): Collection
    {
        return $this->coverLetters;
    }

    public function addCoverLetter(CoverLetter $coverLetter): static
    {
        if (!$this->coverLetters->contains($coverLetter)) {
            $this->coverLetters->add($coverLetter);
            $coverLetter->setAppUser($this);
        }
        return $this;
    }

    public function removeCoverLetter(CoverLetter $coverLetter): static
    {
        if ($this->coverLetters->removeElement($coverLetter)) {
            // set the owning side to null (unless already changed)
            if ($coverLetter->getAppUser() === $this) {
                $coverLetter->setAppUser(null);
            }
        }
        return $this;
    }
}