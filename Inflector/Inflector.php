<?php

namespace TDS\Bundle\InflectorBundle\Inflector;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TDS\Bundle\InflectorBundle\Definition\InflectionStrategyInterface;
use TDS\Bundle\InflectorBundle\Strategy\Yandex;

class Inflector
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
/**
 *
 * @param string $WordString
 * @return Word
 */
	public function createWord( $WordString )
	{
		return new Word( $WordString, $this->getInflectionStrategy() );
	}

/**
 * @todo make parameters when there will be more than one inflector
 * @return InflectionStrategyInterface
 */
	private function getInflectionStrategy()
	{
		$strategy = new Yandex();
		$strategy->setContainer( $this->container );
		return $strategy;
	}
}