<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
	/**
	 * @Route("/api", name="api_get_all")
	 */
	public function indexAction(Request $request)
	{
		/** @var PostRepository $postRepository */
		$postRepository = $repository = $this->getDoctrine()
			->getRepository(Post::class);

		return new JsonResponse(['posts' => $postRepository->findAllForApi()]);
	}

	/**
	 * Finds and displays a product entity.
	 *
	 * @Route("api/get", name="api_get_one")
	 */
	public function viewAction(Request $request)
	{
		$post = NULL;
		$id = $request->query->get('id');

		if ($id) {
			/** @var PostRepository $postRepository */
			$postRepository = $repository = $this->getDoctrine()
				->getRepository(Post::class);

			/** @var Post $post */
			$post = $postRepository->findOneBy([
				'id'       => $id,
				'isActive' => 1,
			]);

			$postRepository->incrementViews($post->getUrl());
		}

		if ($post === NULL) {
			return new JsonResponse([]);
		}

		$response = ['post' => (array)$post];

		return new JsonResponse($response);
	}
}
