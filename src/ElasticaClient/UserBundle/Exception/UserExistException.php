<?php

namespace ElasticaClient\UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Exception for when use by sending email exist
 *
 * @author ahmet akbana
 */
class UserExistException extends HttpException {

	/**
	 * Error text
	 *
	 * @var string
	 */
	protected $error;

	/**
	 * @param string $message, string $error
	 */
	public function __construct($message, $error = '') {
		parent::__construct(200, $message);
		$this -> error = $error;
	}

	/**
	 * Gets the error message
	 *
	 * @return string $error
	 */
	public function getError() {
		return $this -> error;
	}

}
