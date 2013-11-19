<?php

namespace TDS\InflectorBundle\Inflector;

use Symfony\Component\DependencyInjection\ContainerInterface;
use \TDS\InflectorBundle\Definition\InflectionStrategyInterface;

/**
 *
 * @method string getNominative
 * @method string getGenitive
 * @method string getDative
 * @method string getAccusative
 * @method string getAblative
 * @method string getPrepositional
 */
class Word
{
/** Cases constants */
	const NOMINATIVE 	= 'Nominative';
	const GENITIVE 		= 'Genitive';
	const DATIVE 		= 'Dative';
	const ACCUSATIVE 	= 'Accusative';
	const ABLATIVE 		= 'Ablative';
	const PREPOSITIONAL	= 'Prepositional';

	const CONSONANT = 'бвгджзйклмнпрстфхцчшщ';
/** @var int */
	protected $cacheTime = 0;

/** @var string $word */
	protected $word;
/** @var InflectionStrategyInterface $inflectionStrategy */
	protected $inflectionStrategy;
/** @var array|null */
	protected $cases = null;
/** @var \Symfony\Component\DependencyInjection\ContainerInterface */
	protected $container;
/** @var array */
	protected static $avaliableCases = array(
		self::NOMINATIVE,
		self::GENITIVE,
		self::DATIVE,
		self::ACCUSATIVE,
		self::ABLATIVE,
		self::PREPOSITIONAL,
	);

/**
 * @param                                                           $Word
 * @param InflectionStrategyInterface                               $InflectionStrategy
 * @param ContainerInterface                                        $Container
 * @param int                                                       $CacheTime
 */
	public function __construct( $Word, InflectionStrategyInterface $InflectionStrategy, ContainerInterface $Container, $CacheTime = 0 )
	{
		$this->word = $Word;
		$this->inflectionStrategy = $InflectionStrategy;
		$this->container = $Container;
		$this->cacheTime = $CacheTime;
	}

/**
 * @return string
 */
	public function getPrepositionalWithInPreposition()
	{
		$word = $this->getPrepositional();
		$firstLetter = mb_strtolower( mb_substr( $word, 0, 1, 'UTF-8' ), 'UTF-8' );
		$preposition = ( in_array( $firstLetter, ['в', 'ф'] ) && preg_match( '!^[' . self::CONSONANT . ']{2,}.+$!ui', $word ) ) ? 'во' : 'в';
		return $preposition . ' ' . $word;
	}

/**
 *
 * @param string $Name
 * @param mixed $Arguments
 *
 * @throws \BadFunctionCallException
 */
	public function __call( $Name, $Arguments )
	{
		if ( 'get' === substr( $Name, 0, 3 ) ) {
			$Name = substr( $Name, 3 );
		}
		else {
			throw new \BadFunctionCallException( sprintf( 'Unsupported method call!' ) );
		}
		$this->checkValidCase( $Name );

		if ( $this->cacheTime )
		{
			$memcahed = $this->container->get( 'memcache.default' );
			if ( $cases = $memcahed->get( $this->getCacheKey() ) ) {
				$this->cases = $cases;
			}
		}

		if ( null === $this->cases )
		{
			$this->inflectionStrategy->inflect( $this );
			if ( $this->cacheTime ) {
				$memcahed->set( $this->getCacheKey(), $this->cases, $this->cacheTime );
			}
		}

		return $this->cases[$Name];
	}

/**
 *
 * @param string $Name
 * @param string $Value
 *
 * @throws \BadFunctionCallException
 */
	public function set( $Name, $Value )
	{
		$this->checkValidCase( $Name );
		$this->cases[$Name] = $Value;
	}

/**
 *
 * @param string $Name
 *
 * @throws \BadFunctionCallException
 */
	public function checkValidCase( $Name )
	{
		if ( ! in_array( $Name, self::$avaliableCases ) ) {
			throw new \BadFunctionCallException( sprintf( 'Word doesn\'t know about %s case. Please, use one of the following: %s', $Name, implode( ', ', self::$avaliableCases ) ) );
		}
	}

/**
 * @return string
 */
	public function __toString()
	{
		return $this->word;
	}

/**
 * @return string
 */
	private function getCacheKey()
	{
		return 'inflect.' . $this->word;
	}
}