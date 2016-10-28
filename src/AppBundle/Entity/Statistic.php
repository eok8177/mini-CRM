<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistic
 *
 * @ORM\Table(name="statistic")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatisticRepository")
 */
class Statistic
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $idClub;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_start;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $sum_in;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $sum_out;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $saldo;


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
     * Set idClub
     *
     * @param integer $idClub
     *
     * @return Statistic
     */
    public function setIdClub($idClub)
    {
        $this->idClub = $idClub;

        return $this;
    }

    /**
     * Get idClub
     *
     * @return integer
     */
    public function getIdClub()
    {
        return $this->idClub;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Statistic
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set sumIn
     *
     * @param float $sumIn
     *
     * @return Statistic
     */
    public function setSumIn($sumIn)
    {
        $this->sum_in = $sumIn;

        return $this;
    }

    /**
     * Get sumIn
     *
     * @return float
     */
    public function getSumIn()
    {
        return $this->sum_in;
    }

    /**
     * Set sumOut
     *
     * @param float $sumOut
     *
     * @return Statistic
     */
    public function setSumOut($sumOut)
    {
        $this->sum_out = $sumOut;

        return $this;
    }

    /**
     * Get sumOut
     *
     * @return float
     */
    public function getSumOut()
    {
        return $this->sum_out;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return Statistic
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float
     */
    public function getSaldo()
    {
        return $this->saldo;
    }
}
