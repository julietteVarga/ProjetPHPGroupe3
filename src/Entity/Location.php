<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
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
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;
/*
    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Outing", mappedBy="location")
     *
    private Outing $outings;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\City", inversedBy="locations")
     *
    private City $city;
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
/*
    /**
     * @return Outing
     *
    public function getOutings(): Outing
    {
        return $this->outings;
    }

    /**
     * @param Outing $outings
     *
    public function setOutings(Outing $outings): void
    {
        $this->outings = $outings;
    }

    /**
     * @return City
     *
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     *
    public function setCity(City $city): void
    {
        $this->city = $city;
    }
*/

}
