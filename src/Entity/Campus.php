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

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy= "campus")
     */
    private $students;


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



    /**
     * @return ArrayCollection
     */
    public function getStudents(): ArrayCollection
    {
        return $this->students;
    }

    /**
     * @param ArrayCollection $students
     */
    public function setStudents(ArrayCollection $students): void
    {
        $this->students = $students;
    }


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
