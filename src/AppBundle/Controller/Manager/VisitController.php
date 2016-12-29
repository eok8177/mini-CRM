<?php

namespace AppBundle\Controller\Manager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\Visit;
use AppBundle\Form\VisitForManagerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/madmin")
 */
class VisitController extends Controller
{
	/**
	 * @Route("/visits", name="manager_visits_list")
	 */
	public function listAction(Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$order = $request->query->get('order') ? $request->query->get('order') : 'ASC';
		$column = $request->query->get('column') ? $request->query->get('column') : 'id';

		$visits = $this->getDoctrine()
			->getRepository('AppBundle:Visit')
			->findBy(
				array('club' => $club_id),
				array($column => $order));

		return $this->render('AppBundle:manager:visit-list.html.twig', [
			'visits' => $visits,
			'current' => ['controller' => 'visit', 'action' => 'list'],
			'order' => $order,
			'column' => $column,
		]);
	}

	/**
	 * @Route("/visit/new/{guest}", name="manager_visit_new")
	 */
	public function newAction($guest, Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$club = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->findOneById($club_id);

		$guestObj = $this->getDoctrine()
			->getRepository('AppBundle:Guest')
			->findOneById($guest);

		$visit = new Visit();

		$visit->setComingTime(new \DateTime('now'));
		$visit->setClub($club);
		$visit->setGuest($guestObj);

		$em = $this->getDoctrine()->getManager();
		$em->persist($visit);
		$em->flush();

		$this->addFlash(
			'notice',
			'Visit saved!'
		);
		return $this->redirectToRoute('manager_guests_list');
// ------------ OLD can delete
		$form = $this->createForm(VisitForManagerType::class, $visit);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$visit->setComingTime(new \DateTime('now'));
			$visit->setClub($club);
			$visit->setGuest($guestObj);

			$em = $this->getDoctrine()->getManager();
			$em->persist($visit);
			$em->flush();

			$this->addFlash(
				'notice',
				'Visit saved!'
			);
			return $this->redirectToRoute('manager_guests_list');
		}

		return $this->render('AppBundle:manager:visit-edit.html.twig', [
			'form' => $form->createView(),
			'visit' => $visit,
			'current' => ['controller' => 'visit', 'action' => 'create'],
		]);
	}

	/**
	 * @Route("/visit/edit/{id}/{leave}", name="manager_visit_edit", defaults={"id" = 0, "leave" = 0})
	 */
	public function editAction($id, $leave, Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$visit = $this->getDoctrine()
			->getRepository('AppBundle:Visit')
			->find($id);

		if (!$visit) {throw $this->createNotFoundException('No visits found');}

		$form = $this->createForm(VisitForManagerType::class, $visit);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			if ($leave == 1) $visit->setLeaveTime(new \DateTime('now'));

			$em = $this->getDoctrine()->getManager();
			$em->persist($visit);
			$em->flush();

			$this->addFlash(
				'notice',
				'Visit saved!'
			);
			return $this->redirectToRoute('manager_guests_list');
		}

		return $this->render('AppBundle:manager:visit-edit.html.twig', [
			'form' => $form->createView(),
			'visit' => $visit,
			'current' => ['controller' => 'visit', 'action' => 'edit'],
		]);
	}

	/**
	 * @Route("/visit/delete/{id}", name="manager_visit_delete")
	 */
	public function deleteAction($id, Request $request)
	{
		$visit = $this->getDoctrine()
			->getRepository('AppBundle:Visit')
			->find($id);

		$em = $this->getDoctrine()->getManager();
		$em->remove($visit);
		$em->flush();

		$this->addFlash(
			'warning',
			'Visit deleted!'
		);

		return $this->redirectToRoute('manager_visits_list');
	}


}
