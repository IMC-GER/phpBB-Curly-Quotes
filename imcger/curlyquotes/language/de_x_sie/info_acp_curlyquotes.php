<?php
/**
 * Curly Quotes
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [
	'ACP_CURLYQUOTES_TITLE' => 'Curly Quotes',
	'ACP_CURLYQUOTES_SETTINGS' => 'Einstellungen',
	'ACP_CURLYQUOTES_SETTING_SAVED' => 'Curly Quotes Einstellungen erfolgreich gespeichert.',

	'IMCGER_REQUIRE_PHPBB' => 'Diese Erweiterung benötigt eine phpBB Version gleich oder grösser %1$s und kleiner %2$s. Deine Version ist %3$s.',
	'IMCGER_REQUIRE_PHP'   => 'Diese Erweiterung benötigt eine php Version gleich oder grösser %1$s und kleiner %2$s. Deine Version ist %3$s.',
]);
