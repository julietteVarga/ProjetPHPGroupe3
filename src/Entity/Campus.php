<?php

namespace App\Entity;

use App\Repository\CampusRepository;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Outing", mappedBy= "campusOrganizer")
     *
    private Outing $outingsCampus;
*/
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
