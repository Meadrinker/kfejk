<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(repositoryClass="App\Repository\ImageRepository")
 * @Table(name = "images")
 */
class Image {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;


    /**
     * Many Images have Many Tags.
     * @ManyToMany(targetEntity="Tag", inversedBy="images", cascade={"persist"})
     * @JoinTable(name="images_has_tags")
     */
    private $tags;

    /**
     * @Column(type="string")
     */
    private $title;

    /**
     * @Column(type="string")
     */
    private $picture;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

    /**
     * @Column(type="integer")
     */
    private $ratingPlus;

    /**
     * @Column(type="integer")
     */
    private $ratingMinus;

    /**
     * @Column(type="datetime")
     */
    private $time;

    /**
     * @Column(type="integer")
     */
    private $accepted;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="image")
     */
    private $comments;

    public function __construct() {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->time = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getRatingPlus()
    {
        return $this->ratingPlus;
    }

    /**
     * @param mixed $ratingPlus
     */
    public function setRatingPlus($ratingPlus)
    {
        $this->ratingPlus = $ratingPlus;
    }

    /**
     * @return mixed
     */
    public function getRatingMinus()
    {
        return $this->ratingMinus;
    }

    /**
     * @param mixed $ratingMinus
     */
    public function setRatingMinus($ratingMinus)
    {
        $this->ratingMinus = $ratingMinus;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * @param mixed $accepted
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }



}