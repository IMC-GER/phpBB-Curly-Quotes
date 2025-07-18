<?php
/**
 * Curly Quotes
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'CURLYQUOTES_TITLE' => 'Curly Quotes',
	'CURLYQUOTES_TITLE_EXPLAIN' => 'Replaces the traditional quotation marks with curly ones.',

	'CURLYQUOTES_SETTINGS_LANG' => 'Country settings',
	'CURLYQUOTES_LANGSET'		=> 'Country specific settings',
	'CURLYQUOTES_LANGSET_DESC'	=> 'In addition to the installed languages, additional language settings can also be defined with a country code. For this the browser language code must be used e.g. de-CH for German (Switzerland).<br><br>' .
								   'To delete a setting, simply remove the country code before submitting the form. Deleting languages installed in phpBB is not possible.',

	'CURLYQUOTES_SETTINGS'	 => 'Settings',
	'CURLYQUOTES_PRIME'		 => 'Replacement with Prime',
	'CURLYQUOTES_PRIME_DESC' => 'When Yes is selected, all the remaining quotation marks are replaced by the prime symbols (&Prime;, &prime;).',
));
