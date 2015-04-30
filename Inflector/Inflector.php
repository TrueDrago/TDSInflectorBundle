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

    /** @var  array */
    protected $localCachedWords;

    /**
     * @param ContainerInterface $Container
     */
    public function __construct(ContainerInterface $Container)
    {
        $this->container = $Container;
    }

    /**
     *
     * @param string $WordString
     * @return Word
     */
    public function createWord($wordString)
    {
        if (! $word = $this->getCachedWord($wordString))
        {
            $cacheTime = $this->container->getParameter('tds_inflector.cache_time');
            $word = new Word($wordString, $this->getInflectionStrategy(), $this->container, $cacheTime);
            $this->addCachedWord($word);
        }
        return $word;
    }

    /**
     * @param $WordString
     * @return null
     */
    protected function getCachedWord($wordString)
    {
        return isset($this->localCachedWords[$wordString]) ? $this->localCachedWords[$wordString] : null;
    }

    /**
     * @param Word $word
     */
    protected function addCachedWord(Word $word)
    {
        $this->localCachedWords[(string)$word] = $word;
    }

    /**
     * @todo make parameters when there will be more than one inflector
     * @return InflectionStrategyInterface
     */
    private function getInflectionStrategy()
    {
        $strategy = new Yandex();
        $strategy->setContainer($this->container);
        return $strategy;
    }
}