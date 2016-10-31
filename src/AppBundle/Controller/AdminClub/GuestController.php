<?php

namespace AppBundle\Controller\AdminClub;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\Guest;
use AppBundle\Form\GuestType;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/adminclub")
 */
class GuestController extends Controller
{
	/**
	 * @Route("/list_active", name="admin_guests_list")
	 */
	public function listAction(Request $request)
	{
		$user  = $this->get('security.token_storage')->getToken()->getUser();
                $club_id = $user->getClub();

		$order = $request->query->get('order') ? $request->query->get('order') : 'ASC';
		$column = $request->query->get('column') ? $request->query->get('column') : 'id';

		$visits = $this->getDoctrine()
			->getRepository('AppBundle:Visit')
			->getClientsInClub($club_id);

		$guests = $this->getDoctrine()
			->getRepository('AppBundle:Guest')
			->getClientsOther($visits);
				// array(),
				// array($column => $order));



		return $this->render('AppBundle:adminclub:guest-list.html.twig', [
			'visits' => $visits,
			'guests' => $guests,
			'current' => ['controller' => 'guest', 'action' => 'list'],
			'order' => $order,
			'column' => $column,
		]);
	}

	/**
	 * @Route("/guests/edit/{id}", name="admin_guest_edit", defaults={"id" = 0})
	 */
	public function editAction($id, Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

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
			return $this->redirectToRoute('admin_guest_new', [
				'id' => $guest->getId(),
				]);
		}

		return $this->render('AppBundle:adminclub:guest-edit.html.twig', [
			'form' => $form->createView(),
			'guest' => $guest,
			'current' => ['controller' => 'guest', 'action' => $action],
		]);
	}

	/**
	 * @Route("/delete/{id}", name="admin_guest_delete")
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

		return $this->redirectToRoute('admin_guests_list');
	}


}
