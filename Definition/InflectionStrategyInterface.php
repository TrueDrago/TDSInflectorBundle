<?php
namespace TDS\InflectorBundle\Definition;

use TDS\InflectorBundle\Inflector\Word;

interface InflectionStrategyInterface {

/**
 *
 * @param Word $Word
 *
 * @return void
 */
	public function inflect( Word $Word );
}