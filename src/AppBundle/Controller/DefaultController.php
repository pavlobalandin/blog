<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Helper\PageLister;
use AppBundle\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
	/** @todo Move all constants to settings block in next iteration*/
	const PAGE_SIZE   = 2;
	const SCROLL_SIZE = 2;

	const DEFAULT_LENGTH = 30;

	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request)
	{
		/** @var PostRepository $postRepository */
		$postRepository = $repository = $this->getDoctrine()
			->getRepository(Post::class);

		$page = intval($request->query->get('page', 1));

		$pages = 0;
		$posts = $postRepository->findAllActivePaginated($pages, $page - 1, self::PAGE_SIZE);

		$pageLister = new PageLister($this->get('router'), 'homepage', self::SCROLL_SIZE);

		return $this->render('default/index.html.twig', [
			'posts'        => $posts,
			'paging_items' => $pageLister->getPagingItems($page, $pages),
			'maxLength'    => self::DEFAULT_LENGTH,
		]);
	}

	/**
	 * Finds and displays a product entity.
	 *
	 * @Route("view/{url}", name="view")
	 * @param string $url
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function viewAction($url)
	{
		$post = NULL;
		$tags = [];
		if ($url) {

			/** @var PostRepository $postRepository */
			$postRepository = $repository = $this->getDoctrine()
				->getRepository(Post::class);

			$postRepository->incrementViews($url);
			$post = $postRepository->findOneBy(['url' => $url]);
		}

		if ($post === NULL) {
			throw new NotFoundHttpException('Post not found.');
//			return $this->render('default/post_not_found.html.twig', [
//			]);
		}
		return $this->render('default/post.html.twig', [
			'post' => $post,
		]);
	}
}
