<?php
namespace TDS\InflectorBundle\Twig;

/**
 *	Based on code from http://onedev.net/post/249
 */
class PluralizeExtension extends \Twig_Extension
{

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('plural', array($this, 'pluralFilter')),
		);
	}

	/**
	 * Detect & return the ending for the plural word
	 *
	 * @param  array $Endings  nouns or endings words for (1, 4, 5)
	 * @param  int   $Number   number rows to ending determine
	 *
	 * @return string
	 *
	 * @example:
	 * {{ ['Остался %d час', 'Осталось %d часа', 'Осталось %d часов']|plural(11) }}
	 * {{ count }} стат{{ ['ья','ьи','ей']|plural(count)
	 */
	public function pluralFilter( array $Endings, $Number )
	{
		$cases = [2, 0, 1, 1, 1, 2];
		$n = $Number;
		return sprintf($Endings[ ($n%100>4 && $n%100<20) ? 2 : $cases[min($n%10, 5)] ], $n);
	}

	public function getName()
	{
		return 'tds.twig.pluralize_extension';
	}
}