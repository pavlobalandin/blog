<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface, \Serializable
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var string
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=25, unique=true)
	 * @var string
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=64)
	 * @var string
	 */
	private $password;

	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 * @var bool
	 */
	private $isActive;

	public function __construct()
	{
		$this->isActive = TRUE;
		// may not be needed, see section on salt below
		// $this->salt = md5(uniqid('', true));
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getSalt()
	{
		// you *may* need a real salt depending on your encoder
		// see section on salt below
		return NULL;
	}

	/**
	 * @return array
	 */
	public function getRoles()
	{
		return ['ROLE_USER'];
	}

	public function eraseCredentials()
	{
	}

	/** @see \Serializable::serialize() */
	public function serialize()
	{
		return serialize([
			$this->id,
			$this->username,
			$this->password,
			// see section on salt below
			// $this->salt,
		]);
	}

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->username,
			$this->password,
			// see section on salt below
			// $this->salt
			) = unserialize($serialized);
	}
}