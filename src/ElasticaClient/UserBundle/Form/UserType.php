<?php

namespace ElasticaClient\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * User Form
 *
 * @author ahmet akbana
 */
class UserType extends AbstractType {

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, Array $options) {
		$builder -> add('name') -> add('email', 'email') -> add('password', 'password');
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver -> setDefaults(array('data_class' => 'ElasticaClient\UserBundle\Entity\User', 'csrf_protection' => false));
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'user';
	}

}
