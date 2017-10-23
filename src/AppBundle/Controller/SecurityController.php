<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
	/** @todo Move to configuration / setup tools */
	const ADMIN_USERNAME         = 'admin';
	const ADMIN_DEFAULT_PASSWORD = 'admin';

	/**
	 * @Route("/login", name="login")
	 */
	public function loginAction(Request $request, AuthenticationUtils $authUtils)
	{
		$this->generateDefaultUser();

		// get the login error if there is one
		$error = $authUtils->getLastAuthenticationError();


		// last username entered by the user
		$lastUsername = $authUtils->getLastUsername();

		return $this->render('security/login.html.twig', array(
			'last_username' => $lastUsername,
			'error'         => $error,
		));
	}

	/**
	 * Creates admin user if not present in DB.
	 */
	private function generateDefaultUser()
	{
		$repository = $this->getDoctrine()
			->getRepository(User::class);

		$user = $repository->findOneByUsername(self::ADMIN_USERNAME);

		if (!$user) {
			$em = $this->getDoctrine()->getManager();

			if (!$user) {
				$user = new User();
			}

			$encoder = $this->get('security.password_encoder');
			$password = $encoder->encodePassword($user, self::ADMIN_DEFAULT_PASSWORD);

			$user->setUsername(self::ADMIN_USERNAME);
			$user->setPassword($password);

			$em->persist($user);
			$em->flush();
		}
	}
}