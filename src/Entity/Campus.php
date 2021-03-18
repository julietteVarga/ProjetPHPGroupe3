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
    private  $students;
/*
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Outing", mappedBy= "campusOrganizer")
     *
    private Outing $outingsCampus;
*/
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampusName(): ?string
    {
        return $this->campusName;
    }

    public function setCampusName(string $campusName): self
    {
        $this->campusName = $campusName;

        return $this;
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
/*
    /**
     * @return Outing
     *
    public function getOutingsCampus(): Outing
    {
        return $this->outingsCampus;
    }

    /**
     * @param Outing $outingsCampus
     *
    public function setOutingsCampus(Outing $outingsCampus): void
    {
        $this->outingsCampus = $outingsCampus;
    }

*/
}
