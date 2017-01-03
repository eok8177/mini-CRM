<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Club;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Statistic;
use AppBundle\Entity\Guest;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request)
	{
		// replace this example code with whatever you need
		return $this->render('default/index.html.twig', [
				'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
		]);
	}

	/**
	 * @Route("/cron/{date}", name="cronstatistic", defaults={"date" = false})
	 */
	public function cronStatisticAction($date)
	{
		$date = $date ? $date : date('Y-m-d');
		$clubs = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->findAll();

		$visitsByClub = array();
		foreach ($clubs as $key => $value) {
			$visitsByClub[$value->getId()] = $this->getDoctrine()
				->getRepository('AppBundle:Visit')
				->getStatistic($value->getId(), $date);
			if ($visitsByClub[$value->getId()]['count'] == 0) {
				unset($visitsByClub[$value->getId()]);
			}
		}

		foreach ($visitsByClub as $key => $value) {
			$statistic = new Statistic();
			$statistic->setIdClub($key);
			$statistic->setDateStart(new \DateTime($date));
			$statistic->setSumIn($value['sum_in']);
			$statistic->setSumOut($value['sum_out']);
			$statistic->setSaldo($value['sum_in'] - $value['sum_out']);

			$em = $this->getDoctrine()->getManager();
			$em->persist($statistic);
		}

		$em->flush();

		return 'OK';
	}



	/**
	 * @Route("/sms/{id}", name="sms")
	 */
	public function smsAction($id, Request $request)
	{
		$guest = $this->getDoctrine()
			->getRepository('AppBundle:Guest')
			->find($id);

		if ($request->isMethod('POST')) {

			$phone = $request->request->get('phone');
			$message = $request->request->get('message');
			//send SMS to $id

			$this->addFlash('success',"SMS отправлено: $phone  $message");
			return $this->redirectToRoute('manager_guests_list');
		}

		return $this->render('AppBundle::send-sms.html.twig', [
			'current' => ['controller' => 'default', 'action' => 'sms'],
			'guest' => $guest,
			]);
	}

	/**
	 * @Route("/email/{id}", name="email")
	 */
	public function emailAction($id, Request $request)
	{
		$guest = $this->getDoctrine()
			->getRepository('AppBundle:Guest')
			->find($id);

		if ($request->isMethod('POST')) {

			$email = $request->request->get('email');
			$message = $request->request->get('message');
			//send Email to $id

			$this->addFlash('success',"Email отправлено: $email  $message");
			return $this->redirectToRoute('manager_guests_list');
		}

		return $this->render('AppBundle::send-email.html.twig', [
			'current' => ['controller' => 'default', 'action' => 'email'],
			'guest' => $guest,
			]);
	}
}

