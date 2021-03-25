<?php


namespace App\Data;


use App\Entity\Campus;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;


class SearchData
{
    /**
     * @var string|null
     * Chaine de caractere qui va représenter le mot-clé pour la barre de recherche
     * par défaut : la chaine est vide
     */
    public ?string $q = null;


    /**
     * @var string
     * Tableau qui va contenir les campus avec lesquels on va filtrer le résultat
     */
    public $campus;



    /**
     *
     * Pour le filtre de recherche entre date min et date max
     */
    public $mindate;

    /**
     *
     * Pour le filtre de recherche entre date min et date max
     */
    public $maxdate;

    /**
     * @var boolean
     * Pour le filtre de recherche checkbox "sorties dont je suis organisateur"
     */
    public $organizer = false;

    /**
     * @var boolean
     * Pour le filtre de recherche checkbox "sorties auxquelles je participe"
     */
    public $participant = false;

    /**
     * @var boolean
     * Pour le filtre de recherche checkbox "sorties auxquelles je ne participe pas"
     */
     public $notParticipant = false;

     /**
      * @var boolean
      * Pour le filtre de recherche checkbox "sorties passées"
      */
     public $pastOutings = false;

    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }

    /**
     * @param string $q
     */
    public function setQ(string $q): void
    {
        $this->q = $q;
    }

    /**
     * @return string
     */
    public function getCampus(): string
    {
        return $this->campus;
    }

    /**
     * @param string $campus
     */
    public function setCampus(string $campus): void
    {
        $this->campus = $campus;
    }

    /**
     *
     */
    public function getMindate()
    {
        return $this->mindate;
    }

    /**
     *
     */
    public function setMindate($mindate)
    {
        $this->mindate = $mindate;
    }

    /**
     *
     */
    public function getMaxdate()
    {
        return $this->maxdate;
    }

    /**
     *
     */
    public function setMaxdate($maxdate)
    {
        $this->maxdate = $maxdate;
    }

    /**
     * @return bool
     */
    public function isOrganizer(): bool
    {
        return $this->organizer;
    }

    /**
     * @param bool $organizer
     */
    public function setOrganizer(bool $organizer): void
    {
        $this->organizer = $organizer;
    }

    /**
     * @return bool
     */
    public function isParticipant(): bool
    {
        return $this->participant;
    }

    /**
     * @param bool $participant
     */
    public function setParticipant(bool $participant): void
    {
        $this->participant = $participant;
    }

    /**
     * @return bool
     */
    public function isNotParticipant(): bool
    {
        return $this->notParticipant;
    }

    /**
     * @param bool $notParticipant
     */
    public function setNotParticipant(bool $notParticipant): void
    {
        $this->notParticipant = $notParticipant;
    }

    /**
     * @return bool
     */
    public function isPastOutings(): bool
    {
        return $this->pastOutings;
    }

    /**
     * @param bool $pastOutings
     */
    public function setPastOutings(bool $pastOutings): void
    {
        $this->pastOutings = $pastOutings;
    }


}