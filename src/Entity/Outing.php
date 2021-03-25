<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[Assert\GreaterThan("today")]
    private $startingDateTime;


    /**
     * @ORM\Column(type="time")
     */

    private  $duration;



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
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="outingsParticipants")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }


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
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", cascade={"persist"}, inversedBy="outings")
     */
    private Location $location;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cancelInfos;


    /**
     * @return mixed
     */
    public function getCancelInfos()
    {
        return $this->cancelInfos;
    }

    /**
     * @param mixed $cancelInfos
     * @return Outing
     */
    public function setCancelInfos($cancelInfos) : self
    {
        $this->cancelInfos = $cancelInfos;
        return $this;
    }


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
     * @return \DateTimeInterface
     */
    public function getDuration(): \DateTimeInterface
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
         * @return Collection|null
         */
        public function getParticipants(): ?Collection
        {
            return $this->participants;
        }

        /**
         * @param Collection $participants
         */
        public function setParticipants(Collection $participants): void
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
