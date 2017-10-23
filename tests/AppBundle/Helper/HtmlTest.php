<?php
namespace Tests\AppBundle\Helper;

use AppBundle\Helper\Html;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * DOM parser adds default tags
	 */
	public function testDomParser()
	{
		$source = 'some text';
		$expected = '<body><p>some text</p></body>';
		$this->assertEquals($expected, Html::trim($source, 100));
	}

	/**
	 * @dataProvider providerTestTrim
	 *
	 * @param string $source
	 * @param int $size
	 * @param string $expected
	 */
	public function testTrimPlainText($source, $size, $expected)
	{
		$this->assertEquals($expected, Html::trimPlainText($source, $size));
	}

	/**
	 * @return array
	 */
	public function providerTestTrim()
	{
		$basicSize = 25;
		return [
			'plain - smaller than expected' => [
				'some text', $basicSize, 'some text',
			],
			'plain - bigger with nested' => [
				'some <div>text</div> <div>other <div>nested</div> text 123456789', $basicSize, "some <div>text</div> <div>other <div>nested</div> te...</div>\n",
			],
			'plain - bigger - without html' => [
				'some text other nested text 123456789', $basicSize, 'some text other nested te...',
			],
		];
	}
}