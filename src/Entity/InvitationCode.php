<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationCodeRepository")
 */
class InvitationCode
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $owner_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $invitee_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getOwnerId(): ?int
    {
        return $this->owner_id;
    }

    public function setOwnerId(?int $owner_id): self
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    public function getInviteeId(): ?int
    {
        return $this->invitee_id;
    }

    public function setInviteeId(?int $invitee_id): self
    {
        $this->invitee_id = $invitee_id;

        return $this;
    }
}
