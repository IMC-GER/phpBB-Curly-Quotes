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

	'CURLYQUOTES_SETTINGS' => 'Ländereinstellungen',

));
