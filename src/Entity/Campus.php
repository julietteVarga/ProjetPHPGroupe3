<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
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
    private $campusName;
/*
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy= "campus")
     *
    private User $students;
*/

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Outing", mappedBy= "campusOrganizer")
     */
    private $outingsCampus;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCampusName()
    {
        return $this->campusName;
    }

    /**
     * @param mixed $campusName
     */
    public function setCampusName($campusName): void
    {
        $this->campusName = $campusName;
    }


/*
    /**
     * @return User
     *
    public function getStudents(): User
    {
        return $this->students;
    }

    /**
     * @param User $students
     *
    public function setStudents(User $students): void
    {
        $this->students = $students;
    }
*/

    public function getOutingsCampus(): ArrayCollection
    {
        return $this->outingsCampus;
    }

    public function setOutingsCampus(ArrayCollection $outingsCampus): void
    {
        $this->outingsCampus = $outingsCampus;
    }

    public function __toString(): string
    {
        return $this->getCampusName();
    }

}
