<?php
namespace AppBundle\Model;

class Page
{
	/** @var int */
	private $position;

	/** @var string */
	private $link;

	/** @var string */
	private $title;

	/** @var bool */
	private $active;

	/**
	 * Page constructor.
	 * @param int $position
	 * @param string $link
	 * @param string $title
	 * @param bool $active
	 */
	public function __construct($position, $link, $title, $active)
	{
		$this->position = $position;
		$this->link = $link;
		$this->title = $title;
		$this->active = $active;
	}

	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @return string
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function getActive()
	{
		return $this->active;
	}}