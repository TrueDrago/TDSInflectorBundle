<?php

namespace TDS\Bundle\InflectorBundle\Definition;

use Symfony\Component\DependencyInjection\ContainerAware;
use TDS\Bundle\InflectorBundle\Definition\InflectionStrategyInterface;
use TDS\Bundle\InflectorBundle\Inflector\Word;

abstract class InflectionStrategyAbstract extends ContainerAware implements InflectionStrategyInterface {

}