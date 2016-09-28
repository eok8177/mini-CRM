<?php
namespace AppBundle\Controller\Sadmin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Club;
use AppBundle\Form\ClubType;

/**
 * @Route("/sadmin/club")
 */
class ClubController extends Controller
{
	private $URL_PAGE_IMAGE = "/uploads/clubs";

	/**
	 * @Route("/list", name="sadmin_club_list")
	 */
	public function listAction(Request $request)
	{
		$clubs = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->getList();

		return $this->render('sadmin/clubs/list.html.twig', [
			'clubs' => $clubs,
			'current' => ['controller' => 'club', 'action' => 'list'],
		]);
	}

	/**
	 * @Route("/edit/{id}", name="sadmin_club_edit", defaults={"id" = 0})
	 */
	public function EditAction($id, Request $request)
	{
		if ($id == 0) {
			$club = new Club();
			$action = 'create';
		} else {
			$club = $this->getDoctrine()
				->getRepository('AppBundle:Club')
				->find($id);

			if (!$club) {throw $this->createNotFoundException('No clubs found');}
			$action = 'edit';
		}

		$image = $club->getFimage();
		$club->setFimage('');

		$form = $this->createForm(ClubType::class, $club);

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

			return $this->redirectToRoute('sadmin_club_edit', ['id' => $club->getId()]);
		}

		return $this->render('sadmin/clubs/edit.html.twig', [
			'form' => $form->createView(),
			'current' => ['controller' => 'club', 'action' => $action],
			'image' => $image,
			'club' => $club,
		]);
	}

	/**
	 * @Route("/delete/{id}", name="sadmin_club_delete")
	 */
	public function DeleteAction($id, Request $request)
	{
		$club = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->find($id);

		$image = $club->getFimage();
		$rootDir = $this->container->getParameter('kernel.root_dir').'/../web';
		if ($image AND file_exists($rootDir.'/'.$image)) unlink($rootDir.'/'.$image);
		$cacheManager = $this->get('liip_imagine.cache.manager');
		$cacheManager->remove($image);

		$em = $this->getDoctrine()->getManager();
		$em->remove($club);
		$em->flush();

		$this->addFlash(
			'warning',
			'Club deleted!'
		);

		return $this->redirectToRoute('sadmin_club_list');
	}

	/**
	 * @Route("/image/delete/{id}", name="sadmin_club_delete_image")
	 */
	public function deleteImageAction ($id, Request $request) 
	{
		$club = $this->getDoctrine()
		->getRepository('AppBundle:Club')
		->find($id);

		if (!$club) {
			throw $this->createNotFoundException(
				'No clubs found for id '.$id
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

		return $this->redirectToRoute('sadmin_club_edit', [
			'id' =>$id,
			]);
	}
}