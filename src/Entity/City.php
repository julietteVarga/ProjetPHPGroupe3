<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
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
     * @ORM\Column(type="string", length=5)
     */
    private $postalCode;



        /**
         * @var ArrayCollection
         * @ORM\OneToMany (targetEntity="App\Entity\Location", mappedBy="city")
         */
        private  $locations;

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

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLocations(): ArrayCollection
    {
        return $this->locations;
    }

    /**
     * @param ArrayCollection $locations
     */
    public function setLocations(ArrayCollection $locations): void
    {
        $this->locations = $locations;
    }


    public function __toString(): string
    {
        return $this->getName();
    }

}
