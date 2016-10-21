<?php
namespace AppBundle\Controller\AdminClub;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

class AdminClubController extends Controller
{
	/**
	 * @Route("/adminclub", name="adminclub_dashboard")
	 */
	public function indexAction()
	{
            $user  = $this->get('security.token_storage')->getToken()->getUser();
            $id = $user->getClub();

            $club = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->getClubById($id);


            $statistics = $this->getDoctrine()
			->getRepository('AppBundle:Statistic')
			->getStatById($id);

            $guests = $this->getDoctrine()
                    ->getRepository('AppBundle:Guest')
                    ->findBy(
                            array());

            return $this->render('AppBundle:adminclub:dashboard.html.twig', [
			'current' => ['controller' => 'dashboard', 'action' => 'list'],
                        'club' => $club,
                        'guests' => $guests,
                        'statistics' => $statistics,
			]);
	}
}