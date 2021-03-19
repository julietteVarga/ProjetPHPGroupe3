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
     * @ORM\Column(type="date")
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
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="outingsParticipants")
     */
    private User $participants;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="outingsOrganizer")
     */
    private User $organizer;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="outingsCampus")
     */
    private Campus $campusOrganizer;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="outings")
     */
    private State $state;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="outings")
     */
    private Location $location;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartingDateTime(): ?\DateTimeInterface
    {
        return $this->startingDateTime;
    }

    /**
     * @param \DateTimeInterface $startingDateTime
     * @return $this
     */
    public function setStartingDateTime(\DateTimeInterface $startingDateTime): self
    {
        $this->startingDateTime = $startingDateTime;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    /**
     * @param \DateTimeInterface $duration
     * @return $this
     */
    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getRegistrationDeadLine(): ?\DateTimeInterface
    {
        return $this->registrationDeadLine;
    }

    /**
     * @param \DateTimeInterface $registrationDeadLine
     * @return $this
     */
    public function setRegistrationDeadLine(\DateTimeInterface $registrationDeadLine): self
    {
        $this->registrationDeadLine = $registrationDeadLine;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxNumberRegistration(): ?int
    {
        return $this->maxNumberRegistration;
    }

    /**
     * @param int $maxNumberRegistration
     * @return $this
     */
    public function setMaxNumberRegistration(int $maxNumberRegistration): self
    {
        $this->maxNumberRegistration = $maxNumberRegistration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOutingInfos(): ?string
    {
        return $this->outingInfos;
    }

    /**
     * @param string $outingInfos
     * @return $this
     */
    public function setOutingInfos(string $outingInfos): self
    {
        $this->outingInfos = $outingInfos;

        return $this;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param State $state
     * @return $this
     */
    public function setState(State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Campus
     */
    public function getCampusOrganizer(): Campus
    {
    return $this->campusOrganizer;
    }

    /**
     * @param Campus $campusOrganizer
     */
    public function setCampusOrganizer(Campus $campusOrganizer): void
    {
    $this->campusOrganizer = $campusOrganizer;
    }


        /**
         * @return User
         */
        public function getParticipants(): User
        {
            return $this->participants;
        }

        /**
         * @param User $participants
         */
        public function setParticipants(User $participants): void
        {
            $this->participants = $participants;
        }

        /**
         * @return User
         */
        public function getOrganizer(): User
        {
            return $this->organizer;
        }

        /**
         * @param User $organizer
         */
        public function setOrganizer(User $organizer): void
        {
            $this->organizer = $organizer;
        }



        /**
         * @return Location
         */
        public function getLocation(): Location
        {
            return $this->location;
        }

        /**
         * @param Location $location
         */
        public function setLocation(Location $location): void
        {
            $this->location = $location;
        }



}
