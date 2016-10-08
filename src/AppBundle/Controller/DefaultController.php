<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Club;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Statistic;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request)
	{
		// replace this example code with whatever you need
		return $this->render('default/index.html.twig', [
				'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
		]);
	}

	/**
	 * @Route("/cron/{date}", name="cronstatistic", defaults={"date" = false})
	 */
	public function cronStatisticAction($date)
	{
		$date = $date ? $date : date('Y-m-d');
		$clubs = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->findAll();

		$visitsByClub = array();
		foreach ($clubs as $key => $value) {
			$visitsByClub[$value->getId()] = $this->getDoctrine()
				->getRepository('AppBundle:Visit')
				->getStatistic($value->getId(), $date);
			if ($visitsByClub[$value->getId()]['count'] == 0) {
				unset($visitsByClub[$value->getId()]);
			}
		}

		foreach ($visitsByClub as $key => $value) {
			$statistic = new Statistic();
			$statistic->setIdClub($key);
			$statistic->setDateStart(new \DateTime($date));
			$statistic->setSumIn($value['sum_in']);
			$statistic->setSumOut($value['sum_out']);
			$statistic->setSaldo($value['sum_in'] - $value['sum_out']);

			$em = $this->getDoctrine()->getManager();
			$em->persist($statistic);
		}

		$em->flush();

		return 'OK';
	}
}

