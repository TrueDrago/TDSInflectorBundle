<?php

namespace TDS\Bundle\InflectorBundle\Strategy;

use Lsw\ApiCallerBundle\Call\HttpGetHtml;
use Lsw\ApiCallerBundle\Call\HttpPost;
use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use TDS\Bundle\InflectorBundle\Definition\InflectionStrategyAbstract;
use TDS\Bundle\InflectorBundle\Definition\InflectionStrategyInterface;
use TDS\Bundle\InflectorBundle\Inflector\Word;

class Yandex extends InflectionStrategyAbstract implements InflectionStrategyInterface {

	const YANDEX_URL = 'http://export.yandex.ru/inflect.xml';

	protected static $avaliableCases = array(
		1 => Word::NOMINATIVE,
		2 => Word::GENITIVE,
		3 => Word::DATIVE,
		4 => Word::ACCUSATIVE,
		5 => Word::ABLATIVE,
		6 => Word::PREPOSITIONAL,
	);

/**
 *
 * @param Word $Word
 *
 * @return void
 */
	public function inflect( Word $Word )
	{
		/** @var LoggingApiCaller $caller */
		$caller = $this->container->get( 'api_caller' );

		$call = new HttpPost( self::YANDEX_URL, array('name' => (string) $Word) );
		$result = $caller->call( $call );

		$dom = new \DOMDocument();
		$dom->loadXML( $result );

		$elements = $dom->getElementsByTagName( 'inflection' );
		foreach( $elements as $element )
		{
			$case = $element->getAttribute( 'case' );
			if ( in_array( $case, array_keys( self::$avaliableCases ) ) )
			{
				$Word->set( self::$avaliableCases[$case], $element->nodeValue );
			}
		}
	}
}