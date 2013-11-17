<?php

namespace TDS\InflectorBundle\Definition;

use Symfony\Component\DependencyInjection\ContainerAware;
use TDS\InflectorBundle\Definition\InflectionStrategyInterface;
use TDS\InflectorBundle\Inflector\Word;

abstract class InflectionStrategyAbstract extends ContainerAware implements InflectionStrategyInterface {

}