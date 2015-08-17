<?php

namespace ElasticaClient\UserBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

/**
 * User Entity
 */
class User {
	/**
	 * User id
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * User name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * User email
	 *
	 * @var string
	 */
	private $email;

	/**
	 * User password
	 *
	 * @var string
	 */
	private $password;

	/**
	 * Set id
	 *
	 * @param string $id
	 * @return User
	 */
	public function setId($id) {
		$this -> id = $id;

		return $this;
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this -> id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 * @return User
	 */
	public function setName($name) {
		$this -> name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this -> name;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return User
	 */
	public function setEmail($email) {
		$this -> email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this -> email;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 * @return User
	 */
	public function setPassword($password) {
		$this -> password = $password;

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this -> password;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getHashPassword() {
		return md5($this -> password);
	}

	/**
	 * User form validator
	 * @param ClassMetadata $metadata
	 */
	public static function loadValidatorMetadata(ClassMetadata $metadata) {
		$metadata -> addPropertyConstraint('email', new Email());
		$metadata -> addPropertyConstraint('name', new NotBlank());
		$metadata -> addPropertyConstraint('name', new Length( array('min' => 2)));
		$metadata -> addPropertyConstraint('name', new Length( array('max' => 25)));
	}

}
