<?php
namespace AppBundle\Controller\Manager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

class ManagerController extends Controller
{
	/**
	 * @Route("/madmin", name="manager_dashboard")
	 */
	public function indexAction()
	{
            $user  = $this->get('security.token_storage')->getToken()->getUser();
            $id = $user->getClub();

            $club = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->getClubById($id);
            
            return $this->render('AppBundle:madmin:dashboard.html.twig', [
			'current' => ['controller' => 'dashboard', 'action' => 'index'],
                        'club' => $club,
			]);
	}
}