<?php
namespace AppBundle\Controller\Sadmin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Statistic;
use AppBundle\Entity\Club;

class DashboardController extends Controller
{
	/**
	 * @Route("/sadmin", name="sadmin_dashboard")
	 */
	public function indexAction()
	{
		$statistics = $this->getDoctrine()
			->getRepository('AppBundle:Statistic')
			->findAll();

		$clubs = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->getArrayClubs();

		return $this->render('sadmin/dashboard.html.twig', [
			'current' => ['controller' => 'dashboard', 'action' => 'index'],
			'statistics' => $statistics,
			'clubs' => $clubs,
			]);
	}
}