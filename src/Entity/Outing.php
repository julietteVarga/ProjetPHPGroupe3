<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 */
class Outing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startingDateTime;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDeadLine;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxNumberRegistration;

    /**
     * @ORM\Column(type="text")
     */
    private $outingInfos;

    /**
     * @ORM\Column(type="array")
     */
    private $state = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartingDateTime(): ?\DateTimeInterface
    {
        return $this->startingDateTime;
    }

    public function setStartingDateTime(\DateTimeInterface $startingDateTime): self
    {
        $this->startingDateTime = $startingDateTime;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationDeadLine(): ?\DateTimeInterface
    {
        return $this->registrationDeadLine;
    }

    public function setRegistrationDeadLine(\DateTimeInterface $registrationDeadLine): self
    {
        $this->registrationDeadLine = $registrationDeadLine;

        return $this;
    }

    public function getMaxNumberRegistration(): ?int
    {
        return $this->maxNumberRegistration;
    }

    public function setMaxNumberRegistration(int $maxNumberRegistration): self
    {
        $this->maxNumberRegistration = $maxNumberRegistration;

        return $this;
    }

    public function getOutingInfos(): ?string
    {
        return $this->outingInfos;
    }

    public function setOutingInfos(string $outingInfos): self
    {
        $this->outingInfos = $outingInfos;

        return $this;
    }

    public function getState(): ?array
    {
        return $this->state;
    }

    public function setState(array $state): self
    {
        $this->state = $state;

        return $this;
    }
}
