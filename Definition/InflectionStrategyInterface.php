<?php
namespace TDS\Bundle\InflectorBundle\Definition;

use TDS\Bundle\InflectorBundle\Inflector\Word;

interface InflectionStrategyInterface {

/**
 *
 * @param Word $Word
 *
 * @return void
 */
	public function inflect( Word $Word );
}