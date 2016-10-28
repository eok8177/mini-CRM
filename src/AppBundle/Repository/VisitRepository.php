<?php

namespace AppBundle\Repository;

/**
 * VisitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VisitRepository extends \Doctrine\ORM\EntityRepository
{
	public function getStatistic($clubId, $date = false)
	{
		$date = $date ? $date : date('Y-m-d');

		$sqlResult = "SELECT SUM(sum_in) AS sum_in , SUM(sum_out) AS sum_out , COUNT(id) AS count 
			FROM visits 
			WHERE club_id = $clubId AND coming_time >= '$date' 
			";

		$stmt = $this->getEntityManager()->getConnection()->prepare($sqlResult);
		$stmt->execute();
		$result= $stmt->fetch();

		return $result;
	}

	public function getClientsInClub($club_id)
	{
		$query = $this->createQueryBuilder('p')
			->where("p.club = :club_id")
			->andWhere("p.leaveTime IS NULL")
			->setParameter("club_id", $club_id)
			;

		return $query->getQuery()->getResult();
	}
}
