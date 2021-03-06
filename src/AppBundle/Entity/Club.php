<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Club
 *
 * @ORM\Table(name="clubs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClubRepository")
 */
class Club
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $contacts;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $fimage;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allowDelete = true;


    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled = false;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="club")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="club")
     */
    private $visits;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->visits = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Club
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Club
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Club
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Club
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set fimage
     *
     * @param string $fimage
     *
     * @return Club
     */
    public function setFimage($fimage)
    {
        $this->fimage = $fimage;

        return $this;
    }

    /**
     * Get fimage
     *
     * @return string
     */
    public function getFimage()
    {
        return $this->fimage;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Club
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set allowDelete
     *
     * @param boolean $allowDelete
     *
     * @return Club
     */
    public function setAllowDelete($allowDelete)
    {
        $this->allowDelete = $allowDelete;

        return $this;
    }

    /**
     * Get allowDelete
     *
     * @return boolean
     */
    public function getAllowDelete()
    {
        return $this->allowDelete;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Club
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set contacts
     *
     * @param string $contacts
     *
     * @return Club
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Club
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add visit
     *
     * @param \AppBundle\Entity\Visit $visit
     *
     * @return Club
     */
    public function addVisit(\AppBundle\Entity\Visit $visit)
    {
        $this->visits[] = $visit;

        return $this;
    }

    /**
     * Remove visit
     *
     * @param \AppBundle\Entity\Visit $visit
     */
    public function removeVisit(\AppBundle\Entity\Visit $visit)
    {
        $this->visits->removeElement($visit);
    }

    /**
     * Get visits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisits()
    {
        return $this->visits;
    }
}
