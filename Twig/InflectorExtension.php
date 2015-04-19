<?php
namespace TDS\InflectorBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class InflectorExtension extends \Twig_Extension
{
	/** @var ContainerInterface */
	protected $container;

	/**
	 * @param ContainerInterface $Container
	 */
	public function __construct( ContainerInterface $Container )
	{
		$this->container = $Container;
	}

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('prepositionalWithInPreposition', array($this, 'prepositionalWithInPrepositionFilter')),
		);
	}

	public function prepositionalWithInPrepositionFilter( $WordString )
	{
		$inflector = $this->container->get( 'tds.inflector' );
		$word = $inflector->createWord( $WordString );
		return $word->getPrepositionalWithInPreposition();
	}

	public function getName()
	{
		return 'tds.twig.inflector_extension';
	}
}
