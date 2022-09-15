<?php
/**
 *
 * External Links
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
	'CURLYQUOTES_TITLE_EXPLAIN' => 'Ersetzt die herkömmlichen Anführungszeichen durch geschweiften Anführungszeichen',

	'CURLYQUOTES_SETTINGS_LANG' => 'Ländereinstellungen',
	'CURLYQUOTES_LANGSET'		=> 'Länderspezifische Einstellungen',
	'CURLYQUOTES_LANGSET_DESC'	=> 'Ergänzend zu den installierten Sprachen können auch zusätzliche Spracheinstellungen mit Ländercode definiert werden. Hierzu muss der Browsersprachen Code verwendet werden z.B. de-CH für Deutsch (Schweitz)<br>' .
								   'Zum löschen einer Einstellung muß vor dem senden des Formulars lediglich der Ländercode entfernt werden. Das Löschen von in phpBB installierten Sprachen ist nicht möglich.',

	'CURLYQUOTES_SETTINGS'	 => 'Einstellungen',
	'CURLYQUOTES_PRIME'		 => 'Ersetzung durch Prime',
	'CURLYQUOTES_PRIME_DESC' => 'Bei der Auswahl von Ja werden alle übrigen Anführungszeichen durch die Primesymbole (&Prime;, &prime;) ersetzt.',
));
