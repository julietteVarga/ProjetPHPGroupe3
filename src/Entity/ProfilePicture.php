<?php

namespace App\Entity;

use App\Repository\ProfilePictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilePictureRepository::class)
 */
class ProfilePicture
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User",cascade={"persist", "remove"})
     */
    private $userPic;


    /**
     * @ORM\Column(name="path", type="string")
     * @Gedmo\UploadableFilePath
     */
    protected $path;

    /**
     * @ORM\Column(name="name", type="string")
     * @Gedmo\UploadableFileName
     */
    protected $name;

    /**
     * @ORM\Column(name="mime_type", type="string",nullable=True)
     * @Gedmo\UploadableFileMimeType
     */
    protected $mimeType;

    /**
     * @ORM\Column(name="size", type="decimal",nullable=True)
     * @Gedmo\UploadableFileSize
     */
    protected $size;

    /**
     * @ORM\Column(type="string",nullable=True)
     */
    protected $publicPath;

    /**
     * @return string
     */
    public function getPublicPath()
    {
        return '/uploads/'.$this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     *
     * @return mixed
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
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
     *
     * @return ProfilePicture
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     *
     * @return ProfilePicture
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     *
     * @return ProfilePicture
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getUserPic()
    {
        return $this->userPic;
    }

    /**
     * @param mixed $userPic
     */
    public function setUserPic($userPic): void
    {
        $this->userPic = $userPic;
    }
}
