<?php

namespace AppBundle\Helper;

class Html
{

	const BODY_TAG = 'body';
	const DEFAULT_LENGTH = 25;

	protected $reachedLimit = FALSE;
	protected $totalLen = 0;
	protected $maxLen = self::DEFAULT_LENGTH;
	protected $toRemove = [];

	public static function trimPlainText($html, $maxLen = self::DEFAULT_LENGTH)
	{
		$openTag = '<' . self::BODY_TAG . '>';
		$closeTag = '</' . self::BODY_TAG . '>';
		return preg_replace([
						'/^' . preg_quote($openTag, '/') . '/',
						'/' . preg_quote($closeTag, '/') . '$/',
					],
					['', ''],
					self::trim($openTag . $html . $closeTag, $maxLen)
				);
	}

	public static function trim($html, $maxLen = self::DEFAULT_LENGTH)
	{

		$dom = new \DOMDocument();
		$dom->loadHTML($html);

		$html = new static();
		$toRemove = $html->walk($dom, $maxLen);

		// remove any nodes that passed our limit
		foreach ($toRemove as $child)
			$child->parentNode->removeChild($child);

		// remove wrapper tags added by DD (doctype, html...)
		if (version_compare(PHP_VERSION, '5.3.6') < 0) {
			// http://stackoverflow.com/a/6953808/1058140
			$dom->removeChild($dom->firstChild);
			$dom->replaceChild($dom->firstChild->firstChild->firstChild, $dom->firstChild);
			return $dom->saveHTML();
		}

		return $dom->saveHTML($dom->getElementsByTagName(self::BODY_TAG)->item(0));
	}

	/**
	 * @param \DOMNode $node
	 * @param int $maxLen
	 * @return array
	 */
	protected function walk(\DOMNode $node, $maxLen)
	{
		if ($this->reachedLimit) {
			$this->toRemove[] = $node;
		} else {
			// only text nodes should have text,
			// so do the splitting here
			if ($node instanceof \DOMText) {
				$this->totalLen += $nodeLen = strlen($node->nodeValue);

				// use mb_strlen / mb_substr for UTF-8 support
				if ($this->totalLen > $maxLen) {
					$node->nodeValue = substr($node->nodeValue, 0, $nodeLen - ($this->totalLen - $maxLen)) . '...';
					$this->reachedLimit = TRUE;
				}
			}

			// if node has children, walk its child elements
			if (isset($node->childNodes))
				foreach ($node->childNodes as $child)
					$this->walk($child, $maxLen);
		}

		return $this->toRemove;
	}
}