<?php

namespace AppBundle\Controller\Manager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\User;
use AppBundle\Form\UserAdminType;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/madmin")
 */
class UserController extends Controller
{
	private $URL_USER_AVATAR = "/uploads/avatars";

	/**
	 * @Route("/users", name="manager_users_list")
	 */
	public function listAction(Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$users = $this->getDoctrine()
			->getRepository('AppBundle:User')
			->getAdmins($club_id);

		return $this->render('AppBundle:manager:user-list.html.twig', [
			'users' => $users,
			'current' => ['controller' => 'user', 'action' => 'list'],
		]);
	}


	/**
	 * @Route("/user/add", name="manager_user_add")
	 */
	public function addAction(Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$club = $this->getDoctrine()
			->getRepository('AppBundle:Club')
			->findOneById($club_id);

		$user = new User();
		$action = 'create';

		$form = $this->createForm(UserAdminType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$password = $form['plainPassword']->getData();

			if ($password) {
				$password = $this->get('security.password_encoder')
					->encodePassword($user, $password);
				$user->setPassword($password);
			}

			// $file stores the uploaded file
			/** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
			$file = $user->getPhoto();
			if ($file) {
				// Generate a unique name for the file before saving it
				$ext = $file->getClientOriginalExtension();
				$name = preg_replace("/$ext/", '', $file->getClientOriginalName());

				$fileName = $name.md5(uniqid()).'.'.$file->guessExtension();

				$urlDir = $this->URL_USER_AVATAR;

				$rootDir = $this->container->getParameter('kernel.root_dir').'/../web';

				$photosDir = $rootDir.$urlDir;

				$file->move($photosDir, $fileName);

				//delete old file
				if ($photo AND file_exists($rootDir.'/'.$photo)) unlink($rootDir.'/'.$photo);

				// Update the 'photo' property to store the file name instead of its contents
				$user->setPhoto($urlDir.'/'.$fileName);
			}

			$user->setClub($club);
			$user->setRole('ROLE_ADMIN');

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			$this->addFlash(
				'notice',
				'User saved!'
			);
			return $this->redirectToRoute('manager_users_list');
		}

		return $this->render('AppBundle:manager:user-edit.html.twig', [
			'form' => $form->createView(),
			'photo' => false,
			'user' => $user,
			'current' => ['controller' => 'user', 'action' => 'add'],
		]);
	}


	/**
	 * @Route("/user/edit/{id}", name="manager_user_edit", defaults={"id" = 0})
	 */
	public function editAction($id, Request $request)
	{
		$session = new Session();
		$club_id = $session->get('club_id');

		$user = $this->getDoctrine()
			->getRepository('AppBundle:User')
			->find($id);

		if (!$user OR $user->getClub()->getId() != $club_id OR $user->getRole() != 'ROLE_ADMIN') {throw $this->createNotFoundException('No users found');}


		$photo = $user->getPhoto();
		$user->setPhoto('');

		$form = $this->createForm(UserAdminType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$password = $form['plainPassword']->getData();

			if ($password) {
				$password = $this->get('security.password_encoder')
					->encodePassword($user, $password);
				$user->setPassword($password);
			}

			// $file stores the uploaded file
			/** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
			$file = $user->getPhoto();
			if ($file) {
				// Generate a unique name for the file before saving it
				$ext = $file->getClientOriginalExtension();
				$name = preg_replace("/$ext/", '', $file->getClientOriginalName());

				$fileName = $name.md5(uniqid()).'.'.$file->guessExtension();

				$urlDir = $this->URL_USER_AVATAR;

				$rootDir = $this->container->getParameter('kernel.root_dir').'/../web';

				$photosDir = $rootDir.$urlDir;

				$file->move($photosDir, $fileName);

				//delete old file
				if ($photo AND file_exists($rootDir.'/'.$photo)) unlink($rootDir.'/'.$photo);

				// Update the 'photo' property to store the file name instead of its contents
				$user->setPhoto($urlDir.'/'.$fileName);
			} else {
				$user->setPhoto($photo);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			$this->addFlash(
				'notice',
				'User saved!'
			);
			return $this->redirectToRoute('manager_users_list');
		}

		return $this->render('AppBundle:manager:user-edit.html.twig', [
			'form' => $form->createView(),
			'photo' => $photo,
			'user' => $user,
			'current' => ['controller' => 'user', 'action' => 'edit'],
		]);
	}

	/**
	 * @Route("/user/delete/{id}", name="manager_user_delete")
	 */
	public function deleteAction($id, Request $request)
	{
		$user = $this->getDoctrine()
			->getRepository('AppBundle:User')
			->find($id);

		if ($this->getUser()->getId() == $id OR $user->getRole() != 'ROLE_ADMIN') {
			$this->addFlash(
				'warning',
				'You can`t delete this user!'
			);
			return $this->redirectToRoute('manager_users_list');
		}

		$photo = $user->getPhoto();
		$rootDir = $this->container->getParameter('kernel.root_dir').'/../web';
		if ($photo AND file_exists($rootDir.'/'.$photo)) unlink($rootDir.'/'.$photo);
		$cacheManager = $this->get('liip_imagine.cache.manager');
		$cacheManager->remove($photo);

		$em = $this->getDoctrine()->getManager();
		$em->remove($user);
		$em->flush();

		$this->addFlash(
			'warning',
			'User deleted!'
		);

		return $this->redirectToRoute('manager_users_list');
	}

	/**
	 * @Route("/avatar/delete/{id}", name="manager_user_delete_avatar")
	 */
	public function deleteAvatarAction ($id, Request $request) 
	{
		$user = $this->getDoctrine()
		->getRepository('AppBundle:User')
		->find($id);

		if (!$user) {
			throw $this->createNotFoundException(
				'No users found for id '.$id
				);
		}

		$photo = $user->getPhoto();
		$rootDir = $this->container->getParameter('kernel.root_dir').'/../web';
		if ($photo AND file_exists($rootDir.'/'.$photo)) unlink($rootDir.'/'.$photo);
		$cacheManager = $this->get('liip_imagine.cache.manager');
		$cacheManager->remove($photo);

		$user->setPhoto('');

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		$this->addFlash(
			'warning',
			'Foto deleted'
			);

		return $this->redirectToRoute('manager_user_edit', [
			'id' =>$id,
			]);
	}

}
