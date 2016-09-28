<?php
namespace AppBundle\Controller\Sadmin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
	/**
	 * @Route("/sadmin", name="sadmin_dashboard")
	 */
	public function indexAction()
	{
		return $this->render('sadmin/dashboard.html.twig', [
			'current' => ['controller' => 'dashboard', 'action' => 'index'],
			]);
	}
}