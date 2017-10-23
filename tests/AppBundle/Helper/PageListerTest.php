<?php
namespace Tests\AppBundle\Helper;

use AppBundle\Helper\PageLister;

class PageListerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider providerGeneral
	 *
	 * @param int $page
	 * @param int $total
	 * @param int $itemsExpected
	 * @param int $activeItem
	 * @param string $firstTitle
	 * @param string $lastTitle
	 */
	public function testGeneral($page, $total, $itemsExpected, $activeItem, $firstTitle, $lastTitle)
	{
		$action = 'some';
		$pageLister = new PageLister($this->getRouter(), $action, 2);
		$pages = $pageLister->getPagingItems($page, $total);

		$this->assertEquals($itemsExpected, count($pages));
		$this->assertTrue($pages[$activeItem]->getActive());
		$this->assertEquals($firstTitle, $pages[0]->getTitle());
		$this->assertEquals($lastTitle, $pages[count($pages) - 1]->getTitle());

		foreach ($pages as $id => $page) {
			$this->assertInstanceOf('\AppBundle\Model\Page', $page);
			if ($id !== $activeItem) {
				$this->assertFalse($page->getActive());
			}
			$this->assertEquals($this->generateLink($action, ['page' => $page->getPosition()]), $page->getLink());
		}

	}

	/**
	 * @return array
	 */
	public function providerGeneral()
	{
		return [
			'small' => [
				1, 2, 2, 0, 1, 2,
			],

			'first page' => [
				1, 10, 3, 0, 1, PageLister::NEXT_LABEL,
			],
			'middle page' => [
				5, 10, 6, 3,  PageLister::PREV_LABEL, PageLister::NEXT_LABEL,
			],
		];
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	public function getRouter()
	{
		$mock = $this->getMockBuilder('\Symfony\Component\Routing\Router')
			->disableOriginalConstructor()
			->setMethods(['generate'])
			->getMock();

		$mock->expects($this->any())
			->method('generate')
			->will($this->returnCallback(function($action, $params) {
				return $this->generateLink($action, $params);
			}));

		return $mock;
	}

	/**
	 * @param string $action
	 * @param array $params
	 * @return string
	 */
	private function generateLink($action, array $params)
	{
		return $action . '-' . json_encode($params);
	}
}