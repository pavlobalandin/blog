<?php

namespace AppBundle\Entity;

use AppBundle\Helper\Html;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * /**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @ORM\Table(name="post")
 */
class Post
{
	const DEFAULT_HTML_LENGHT = 25;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=250)
	 */
	private $title;

	/**
	 * @var string
	 * @ORM\Column(type="text", length=150)
	 */
	private $text;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	private $date;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=250)
	 */
	private $tags;

	/**
	 * @var string
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(name="url", type="string", length=128, nullable=false, unique=true)
	 */
	private $url;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	private $views;

	/**
	 * @var bool
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive;

	public function __construct()
	{
		$this->isActive = TRUE;
		$this->date = new \DateTime();
		$this->views = 0;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param int $maxLength
	 * @return string
	 */
	public function getTruncatedText($maxLength = self::DEFAULT_HTML_LENGHT)
	{
		return Html::trimPlainText($this->text, $maxLength);
	}

	/**
	 * @param string $text
	 */
	public function setText($text)
	{
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * @return array
	 */
	public function getTagsAsArray()
	{
		return explode(' ', preg_replace('/\s+/', ' ', $this->tags));
	}

	/**
	 * @param string $tags
	 */
	public function setTags($tags)
	{
		$this->tags = $tags;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @return int
	 */
	public function getViews()
	{
		return $this->views;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * @param bool $status
	 */
	public function setIsActive($status)
	{
		$this->isActive = $status;
	}
}