<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visit
 *
 * @ORM\Table(name="visit_logs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VisitLogsRepository")
 */
class VisitLogs
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
     * @ORM\ManyToOne(targetEntity="Visit", inversedBy="logs")
     * @ORM\JoinColumn(name="visit_id", referencedColumnName="id")
     */
    private $visit;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $eventTime;

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
    private $sumWin;

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
     * Set eventTime
     *
     * @param \DateTime $eventTime
     *
     * @return VisitLogs
     */
    public function setEventTime($eventTime)
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    /**
     * Get eventTime
     *
     * @return \DateTime
     */
    public function getEventTime()
    {
        return $this->eventTime;
    }

    /**
     * Set sumIn
     *
     * @param float $sumIn
     *
     * @return VisitLogs
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
     * @return VisitLogs
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
     * @return VisitLogs
     */
    public function setSumWin($sumWin)
    {
        $this->sumWin = $sumWin;

        return $this;
    }

    /**
     * Get sumWin
     *
     * @return float
     */
    public function getSumWin()
    {
        return $this->sumWin;
    }

    /**
     * Set game
     *
     * @param string $game
     *
     * @return VisitLogs
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
     * Set visit
     *
     * @param \AppBundle\Entity\Visit $visit
     *
     * @return VisitLogs
     */
    public function setVisit(\AppBundle\Entity\Visit $visit = null)
    {
        $this->visit = $visit;

        return $this;
    }

    /**
     * Get visit
     *
     * @return \AppBundle\Entity\Visit
     */
    public function getVisit()
    {
        return $this->visit;
    }
}
