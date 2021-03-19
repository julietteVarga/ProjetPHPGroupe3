<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

//use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

//use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
//#[UniqueEntity('username',
//message: "Ce nom d'utilisateur existe déjà")]

class User implements UserInterface
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
    private $password;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    //#[Assert\username]
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

     /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

        /**
         * @ORM\Column(type="json")
         */
    private $roles = [];
/*
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Outing", inversedBy="participants", cascade={"persist", "remove"})
     *
    private Outing $outingsParticipants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Outing", mappedBy= "organizer")
     *
    private Outing $outingsOrganizer;
*/
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy= "students")
     */
    private Campus $campus;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return $this
     */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTel(): ?string
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     * @return $this
     */
    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
       return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole($role)
    {
        if (!in_array($role, $this->roles)) {
            array_push($this->roles, $role);
        }

        return $this;
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @return Outing
     */
    public function getOutingsParticipants(): ?Outing
    {
        return $this->outingsParticipants;
    }

    /**
     * @param Outing $outingsParticipants
     */
    public function setOutingsParticipants(Outing $outingsParticipants): void
    {
        $this->outingsParticipants = $outingsParticipants;
    }

    /**
     * @return Outing
     */
    public function getOutingsOrganizer(): Outing
    {
        return $this->outingsOrganizer;
    }

    /**
     * @param Outing $outingsOrganizer
     */
    public function setOutingsOrganizer(Outing $outingsOrganizer): void
    {
        $this->outingsOrganizer = $outingsOrganizer;
    }

    /**
     * @return Campus
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): self
    {
        $this->campus = $campus;
    }





    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }



    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {    }

}
