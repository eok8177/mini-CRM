<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visit
 *
 * @ORM\Table(name="visits")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VisitRepository")
 */
class Visit
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
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="visits")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id")
     */
    private $club;

    /**
     * @ORM\ManyToOne(targetEntity="Guest", inversedBy="visits")
     * @ORM\JoinColumn(name="guest_id", referencedColumnName="id")
     */
    private $guest;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $comingTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $leaveTime;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $sumIn;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $sumOut;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $sum_win;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $game;


 

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
     * Set comingTime
     *
     * @param \DateTime $comingTime
     *
     * @return Visit
     */
    public function setComingTime($comingTime)
    {
        $this->comingTime = $comingTime;

        return $this;
    }

    /**
     * Get comingTime
     *
     * @return \DateTime
     */
    public function getComingTime()
    {
        return $this->comingTime;
    }

    /**
     * Set leaveTime
     *
     * @param \DateTime $leaveTime
     *
     * @return Visit
     */
    public function setLeaveTime($leaveTime)
    {
        $this->leaveTime = $leaveTime;

        return $this;
    }

    /**
     * Get leaveTime
     *
     * @return \DateTime
     */
    public function getLeaveTime()
    {
        return $this->leaveTime;
    }

    /**
     * Set sumIn
     *
     * @param float $sumIn
     *
     * @return Visit
     */
    public function setSumIn($sumIn)
    {
        $this->sumIn = $sumIn;

        return $this;
    }

    /**
     * Get sumIn
     *
     * @return float
     */
    public function getSumIn()
    {
        return $this->sumIn;
    }

    /**
     * Set sumOut
     *
     * @param float $sumOut
     *
     * @return Visit
     */
    public function setSumOut($sumOut)
    {
        $this->sumOut = $sumOut;

        return $this;
    }

    /**
     * Get sumOut
     *
     * @return float
     */
    public function getSumOut()
    {
        return $this->sumOut;
    }

    /**
     * Set sumWin
     *
     * @param float $sumWin
     *
     * @return Visit
     */
    public function setSumWin($sumWin)
    {
        $this->sum_win = $sumWin;

        return $this;
    }

    /**
     * Get sumWin
     *
     * @return float
     */
    public function getSumWin()
    {
        return $this->sum_win;
    }

    /**
     * Set game
     *
     * @param string $game
     *
     * @return Visit
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return string
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set club
     *
     * @param \AppBundle\Entity\Club $club
     *
     * @return Visit
     */
    public function setClub(\AppBundle\Entity\Club $club = null)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get club
     *
     * @return \AppBundle\Entity\Club
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Set guest
     *
     * @param \AppBundle\Entity\Guest $guest
     *
     * @return Visit
     */
    public function setGuest(\AppBundle\Entity\Guest $guest = null)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * Get guest
     *
     * @return \AppBundle\Entity\Guest
     */
    public function getGuest()
    {
        return $this->guest;
    }
}
