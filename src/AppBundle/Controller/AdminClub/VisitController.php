<?php

namespace AppBundle\Controller\AdminClub;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\Visit;
use AppBundle\Form\VisitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/adminclub")
 */
class VisitController extends Controller
{
	/**
	 * @Route("/visits", name="admin_visits_list")
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

		return $this->render('AppBundle:adminclub:visit-list.html.twig', [
			'visits' => $visits,
			'current' => ['controller' => 'visit', 'action' => 'list'],
			'order' => $order,
			'column' => $column,
		]);
	}

	/**
	 * @Route("/visit/edit/{id}", name="admin_visit_edit", defaults={"id" = 0})
	 */
	public function editAction($id, Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		if ($id == 0) {
			$visit = new Visit();
			$action = 'create';
		} else {
			$visit = $this->getDoctrine()
				->getRepository('AppBundle:Visit')
				->find($id);

			if (!$visit) {throw $this->createNotFoundException('No visits found');}
			$action = 'edit';
		}

		$form = $this->createForm(VisitType::class, $visit);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$em = $this->getDoctrine()->getManager();
			$em->persist($visit);
			$em->flush();

			$this->addFlash(
				'notice',
				'Visit saved!'
			);
			return $this->redirectToRoute('admin_visit_edit', [
				'id' => $visit->getId(),
				]);
		}

		$form->remove('club');
		$form->add('club', HiddenType::class, array(
				'data' => $club_id
				));

		return $this->render('AppBundle:adminclub:visit-edit.html.twig', [
			'form' => $form->createView(),
			'visit' => $visit,
			'current' => ['controller' => 'visit', 'action' => $action],
		]);
	}

	/**
	 * @Route("/visit/delete/{id}", name="madmin_visit_delete")
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

		return $this->redirectToRoute('admin_visits_list');
	}


}
