<?php
namespace AppBundle\Controller\Manager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Club;
use AppBundle\Form\ClubType;

/**
 * @Route("/madmin/club")
 */
class ClubController extends Controller
{
	private $URL_PAGE_IMAGE = "/uploads/clubs";

	/**
	 * @Route("/edit", name="manager_club_edit")
	 */
	public function EditAction(Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$club = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->find($club_id);

		if (!$club) {throw $this->createNotFoundException('No clubs found');}

		$image = $club->getFimage();
		$club->setFimage('');

		$form = $this->createForm(ClubType::class, $club);

		// $form->remove('enabled');

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$file = $club->getFimage();
			if ($file) {
				$ext = $file->getClientOriginalExtension();
				$name = preg_replace("/$ext/", '', $file->getClientOriginalName());

				$fileName = $name.md5(uniqid()).'.'.$file->guessExtension();

				$urlDir = $this->URL_PAGE_IMAGE;

				$rootDir = $this->container->getParameter('kernel.root_dir').'/../web';

				$imagesDir = $rootDir.$urlDir;

				$file->move($imagesDir, $fileName);

				if ($image AND file_exists($rootDir.'/'.$image)) unlink($rootDir.'/'.$image);

				$club->setFimage($urlDir.'/'.$fileName);
			} else {
				$club->setFimage($image);
			}

			$status = $this->getDoctrine()
				->getRepository('AppBundle:Club')
				->saveClub($club);

			$this->addFlash('notice','Saved!');

			return $this->redirectToRoute('manager_club_edit');
		}

		return $this->render('AppBundle:manager:club-edit.html.twig', [
			'form' => $form->createView(),
			'current' => ['controller' => 'club', 'action' => 'edit'],
			'image' => $image,
			'club' => $club,
		]);
	}

	/**
	 * @Route("/image/delete", name="manager_club_delete_image")
	 */
	public function deleteImageAction (Request $request) 
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$club = $this->getDoctrine()
		->getRepository('AppBundle:Club')
		->find($club_id);

		if (!$club) {
			throw $this->createNotFoundException(
				'No clubs found for id '.$club_id
				);
		}

		$image = $club->getFimage();
		$rootDir = $this->container->getParameter('kernel.root_dir').'/../web';
		if ($image AND file_exists($rootDir.'/'.$image)) unlink($rootDir.'/'.$image);
		$cacheManager = $this->get('liip_imagine.cache.manager');
		$cacheManager->remove($image);

		$club->setFimage('');

		$em = $this->getDoctrine()->getManager();
		$em->persist($club);
		$em->flush();

		$this->addFlash(
			'warning',
			'Image deleted'
			);

		return $this->redirectToRoute('manager_club_edit');
	}
}