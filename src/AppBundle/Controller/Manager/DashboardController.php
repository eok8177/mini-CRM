<?php
namespace AppBundle\Controller\Manager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/madmin")
 */
class DashboardController extends Controller
{
	/**
	 * @Route("/", name="manager_dashboard")
	 */
	public function indexAction(Request $request)
	{
// TODO: create relations one Manager to many Clubs
// and get id of first club
		$session = new Session();
		if ($id = $request->query->get('id')) {
			$session->set('club_id', $id);
		} else {
			$id = $session->get('club_id');
		}
		if (!($id = $session->get('club_id'))) {
			$id = 1; // костыль
			$session->set('club_id', $id);
		}

		$user  = $this->getUser();

		$statistics = $this->getDoctrine()
			->getRepository('AppBundle:Statistic')
			->findById_club($id);

		$visits = $this->getDoctrine()
			->getRepository('AppBundle:Visit')
			->findBy(
				array('club' => $id));
		
		return $this->render('AppBundle:manager:dashboard.html.twig', [
			'current' => ['controller' => 'dashboard', 'action' => 'index'],
			'user' => $user,
			'statistics' => $statistics,
			'visits' => $visits,
		]);
	}

	/**
	 * @Route("/sidebar", name="manager_sidebar")
	 */
	public function sidebarAction($current = false)
	{
		$session = new Session();
		$id = $session->get('club_id');

		$clubs = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->findAll();

		$club = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->findOneById($id);

		$statistics = $this->getDoctrine()
			->getRepository('AppBundle:Visit')
			->getStatistic($id);

		return $this->render('AppBundle:manager:sidebar.html.twig', [
			'current' => $current,
			'user' => $this->getUser(),
			'clubs' => $clubs,
			'club' => $club,
			'statistics' => $statistics,
		]);
	}
}