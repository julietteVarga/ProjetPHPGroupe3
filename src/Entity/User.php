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
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy= "students")
     */
    private Campus $campus;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


        /**
         * @ORM\Column(type="json")
         */
    private  $roles=[];
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
     * @inheritDoc
     */
    public function getName()
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
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
     * @return Role
     */
    public function getRoles():array
    {
        $roles = $this->roles;
        $roles[]='ROLE_USER';
       return array_unique($roles);

    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
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
