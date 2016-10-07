<?php

namespace AppBundle\Controller\Sadmin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Visit;
use AppBundle\Form\VisitType;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/sadmin")
 */
class VisitController extends Controller
{
	/**
	 * @Route("/visits", name="sadmin_visits_list")
	 */
	public function listAction(Request $request)
	{

		$order = $request->query->get('order') ? $request->query->get('order') : 'ASC';
		$column = $request->query->get('column') ? $request->query->get('column') : 'id';

		$visits = $this->getDoctrine()
			->getRepository('AppBundle:Visit')
			->findBy(
				array(),
				array($column => $order));
// echo "<pre>".print_r($visits, true);exit();
		return $this->render('sadmin/visits/list.html.twig', [
			'visits' => $visits,
			'current' => ['controller' => 'visit', 'action' => 'list'],
			'order' => $order,
			'column' => $column,
		]);
	}

	/**
	 * @Route("/visit/edit/{id}", name="sadmin_visit_edit", defaults={"id" = 0})
	 */
	public function editAction($id, Request $request)
	{

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
			return $this->redirectToRoute('sadmin_visit_edit', [
				'id' => $visit->getId(),
				]);
		}

		return $this->render('sadmin/visits/edit.html.twig', [
			'form' => $form->createView(),
			'visit' => $visit,
			'current' => ['controller' => 'visit', 'action' => $action],
		]);
	}

	/**
	 * @Route("/visit/delete/{id}", name="sadmin_visit_delete")
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

		return $this->redirectToRoute('sadmin_visits_list');
	}


}
