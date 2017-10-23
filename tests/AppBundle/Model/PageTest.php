<?php
namespace Tests\AppBundle\Model;

use AppBundle\Model\Page;

class PageTest extends \PHPUnit_Framework_TestCase
{
	public function testGeneral()
	{
		$position = 5;
		$link = 'some other';
		$title = 'some';
		$active = FALSE;

		$page = new Page($position, $link, $title, $active);

		$this->assertEquals($position, $page->getPosition());
		$this->assertEquals($link, $page->getLink());
		$this->assertEquals($title, $page->getTitle());
		$this->assertFalse($page->getActive());
	}
}