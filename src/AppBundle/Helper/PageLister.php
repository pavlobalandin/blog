<?php
namespace AppBundle\Helper;

use AppBundle\Model\Page;
use Symfony\Component\Routing\Router;

class PageLister
{
	const PREV_LABEL  = '<<';
	const NEXT_LABEL  = '>>';

	/** @var Router */
	private $router;

	/** @var string */
	private $actionName;

	/** @var int */
	private $scrollSize;

	public function __construct(Router $router, $actionName, $scrollSize)
	{
		$this->router = $router;
		$this->actionName = $actionName;
		$this->scrollSize = $scrollSize;
	}

	/**
	 * @param int $page
	 * @param int $total
	 * @return Page[]
	 */
	public function getPagingItems($page, $total)
	{
		$pages = [];

		if ($total == 1) {
			return $pages;
		}

		for ($i = $page - $this->scrollSize; $i < $page + $this->scrollSize; $i++) {
			if ($i < 1) {
				continue;
			}
			if ($i > $total) {
				break;
			}

			$pages[] = new Page(
				$i,
				$this->router->generate($this->actionName, ['page' => $i]),
				$i,
				$i == $page
			);
		}

		if ($page - $this->scrollSize > 1) {
			$pageNum = $page - $this->scrollSize - 1;
			array_unshift($pages, new Page(
				$pageNum,
				$this->router->generate($this->actionName, ['page' => $pageNum]),
				self::PREV_LABEL,
				FALSE
			));
		}

		if ($page + $this->scrollSize <= $total) {
			$pageNum = $page + $this->scrollSize;
			$pages[] = new Page(
				$pageNum,
				$this->router->generate($this->actionName, ['page' => $pageNum]),
				self::NEXT_LABEL,
				FALSE
			);
		}
		return $pages;
	}
}