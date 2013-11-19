<?php

namespace TDS\InflectorBundle\Inflector;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TDS\InflectorBundle\Definition\InflectionStrategyInterface;
use TDS\InflectorBundle\Strategy\Yandex;

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
		$cacheTime = $this->container->getParameter( 'tds_inflector.cache_time' );
		return new Word( $WordString, $this->getInflectionStrategy(), $this->container, $cacheTime );
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