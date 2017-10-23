<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Helper\PageLister;
use AppBundle\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
	/**
	 * @todo Move to global settings
	 */
	const PAGE_SIZE   = 3;
	const SCROLL_SIZE = 2;

	/**
	 * @todo Create localizations stuff
	 */
	const EDIT_LABEL = 'Edit';
	const UPDATE_LABEL = 'Update';
	const CREATE_LABEL = 'Create';

	/**
	 * @Route("/admin", name="admin_home")
	 */
	public function indexAction(Request $request)
	{
		return $this->listAction($request);
	}

	/**
	 * @Route("/admin/list", name="admin_list")
	 */
	public function listAction(Request $request)
	{
		$info = $this->getInfoMessage();

		/** @var PostRepository $postRepository */
		$postRepository = $repository = $this->getDoctrine()
			->getRepository(Post::class);

		$page = intval($request->query->get('page', 1));
		$action = $request->query->get('activation');
		$postId = $request->query->get('id');

		if ($action && $postId) {
			$postRepository->changeState($postId);
		}

		$pages = 0;
		$posts = $postRepository->findAllPaginated($pages, $page - 1, self::PAGE_SIZE);

		$pageLister = new PageLister($this->get('router'), 'admin_list', self::SCROLL_SIZE);

		return $this->render('admin/index.html.twig', [
			'info'         => $info,
			'posts'        => $posts,
			'page'         => $page,
			'paging_items' => $pageLister->getPagingItems($page, $pages),
		]);

	}

	/**
	 * @Route("/admin/edit", name="edit_page")
	 */
	public function editAction(Request $request)
	{
		$id = $request->get('id');
		if ($id) {
			/** @var PostRepository $postRepository */
			$repository = $this->getDoctrine()
				->getRepository(Post::class);
			$post = $repository->find($id);
		} else {
			$post = new Post();
		}

		$form = $this->createForm(PostType::class, $post);
		$form->add('submit', SubmitType::class, array(
			'label' => $post->getId() ? self::UPDATE_LABEL : self::CREATE_LABEL,
			'attr'  => array('class' => 'button')
		));

		$form->handleRequest($request);

		$error = NULL;
		$info = $this->getInfoMessage();

		if ($form->isSubmitted() && $form->isValid()) {

			try {
				$em = $this->getDoctrine()->getManager();
				$em->persist($post);
				$em->flush();
			} catch (\Exception $exception) {
				$error = $exception->getMessage();
			}

			if ($error === NULL) {
				$this->get('session')->set('info', $id ? 'Post ' . $id . ' updated successfully' : 'Post has been created');

				return $this->redirect($this->generateUrl(
					'admin_list', []
				));
			}
		}

		return $this->render('admin/create.html.twig', [
			'form'  => $form->createView(),
			'error' => $error,
			'info'  => $info,
			'title' => ($id ? self::EDIT_LABEL : self::CREATE_LABEL) . ' post',
		]);

	}

	/**
	 * @return string
	 */
	private function getInfoMessage()
	{
		$info = $this->get('session')->get('info');
		$this->get('session')->set('info', NULL);
		return $info;
	}
}
