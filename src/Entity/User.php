<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("mobile")
 */
class User
{
    use TimestampableEntity;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="full_name", type="string", length=255)
     * @Assert\NotBlank
     */
    private $pip;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="InvitationCode", mappedBy="owner")
     */
    private $invitation_codes;

    /**
     * @ORM\OneToOne(targetEntity="InvitationCode", mappedBy="invitee")
     */
    private $invited_by_code;

    public function __construct()
    {
        $this->invitation_codes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->pip;
    }

    public function setFullName(string $full_name): self
    {
        $this->pip = $full_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|InvitationCode[]
     */
    public function getInvitationCodes(): Collection
    {
        return $this->invitation_codes;
    }

    public function addInvitationCode(InvitationCode $invitationCode): self
    {
        if (!$this->invitation_codes->contains($invitationCode)) {
            $this->invitation_codes[] = $invitationCode;
            $invitationCode->setOwner($this);
        }

        return $this;
    }

    public function removeInvitationCode(InvitationCode $invitationCode): self
    {
        if ($this->invitation_codes->contains($invitationCode)) {
            $this->invitation_codes->removeElement($invitationCode);
            // set the owning side to null (unless already changed)
            if ($invitationCode->getOwner() === $this) {
                $invitationCode->setOwner(null);
            }
        }

        return $this;
    }

    public function getInvitedByCode(): ?InvitationCode
    {
        return $this->invited_by_code;
    }

    public function setInvitedByCode(?InvitationCode $invited_by_code): self
    {
        $this->invited_by_code = $invited_by_code;

        // set (or unset) the owning side of the relation if necessary
        $newInvitee = $invited_by_code === null ? null : $this;
        if ($newInvitee !== $invited_by_code->getInvitee()) {
            $invited_by_code->setInvitee($newInvitee);
        }

        return $this;
    }

    public function getPip(): ?string
    {
        return $this->pip;
    }

    public function setPip(string $pip): self
    {
        $this->pip = $pip;

        return $this;
    }
}
