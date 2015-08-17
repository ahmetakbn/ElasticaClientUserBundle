<?php

namespace ElasticaClient\UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Exception for when form validation failed
 *
 * @author ahmet akbana
 */
class InvalidFormException extends BadRequestHttpException {

	/**
	 * Form array
	 *
	 * @var array
	 */
	protected $form;

	/**
	 * @param string $message, array $form
	 */
	public function __construct($message, $form = null) {
		parent::__construct($message);
		$this -> form = $form;
	}

	/**
	 * Gets form array
	 *
	 * @return array|null $form
	 */
	public function getForm() {
		return $this -> form;
	}

}
