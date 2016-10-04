<?php

namespace AppBundle\Controller\Sadmin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Guest;
use AppBundle\Form\GuestType;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/sadmin")
 */
class GuestController extends Controller
{
	/**
	 * @Route("/guests", name="sadmin_guests_list")
	 */
	public function listAction(Request $request)
	{

		$order = $request->query->get('order') ? $request->query->get('order') : 'ASC';
		$column = $request->query->get('column') ? $request->query->get('column') : 'id';

		$guests = $this->getDoctrine()
			->getRepository('AppBundle:Guest')
			->findBy(
				array(),
				array($column => $order));

		return $this->render('sadmin/guests/list.html.twig', [
			'guests' => $guests,
			'current' => ['controller' => 'guest', 'action' => 'list'],
			'order' => $order,
			'column' => $column,
		]);
	}

	/**
	 * @Route("/guest/edit/{id}", name="sadmin_guest_edit", defaults={"id" = 0})
	 */
	public function editAction($id, Request $request)
	{

		if ($id == 0) {
			$guest = new Guest();
			$action = 'create';
		} else {
			$guest = $this->getDoctrine()
				->getRepository('AppBundle:Guest')
				->find($id);

			if (!$guest) {throw $this->createNotFoundException('No guests found');}
			$action = 'edit';
		}

		$form = $this->createForm(GuestType::class, $guest);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$em = $this->getDoctrine()->getManager();
			$em->persist($guest);
			$em->flush();

			$this->addFlash(
				'notice',
				'Guest saved!'
			);
			return $this->redirectToRoute('sadmin_guest_edit', [
				'id' => $guest->getId(),
				]);
		}

		return $this->render('sadmin/guests/edit.html.twig', [
			'form' => $form->createView(),
			'guest' => $guest,
			'current' => ['controller' => 'guest', 'action' => $action],
		]);
	}

	/**
	 * @Route("/guest/delete/{id}", name="sadmin_guest_delete")
	 */
	public function deleteAction($id, Request $request)
	{
		$guest = $this->getDoctrine()
			->getRepository('AppBundle:Guest')
			->find($id);

		$em = $this->getDoctrine()->getManager();
		$em->remove($guest);
		$em->flush();

		$this->addFlash(
			'warning',
			'Guest deleted!'
		);

		return $this->redirectToRoute('sadmin_guests_list');
	}


}
