<?php
/**
 * Curly Quotes
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\curlyquotes\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['imcger_curlyquotes_lang_settings']);
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('imcger_curlyquotes_lang_settings', 'en,1,1;de,3,3')),
			array('config.add', array('imcger_curlyquotes_sets_prime', '')),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_CURLYQUOTES_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_CURLYQUOTES_TITLE',
				array(
					'module_basename'	=> '\imcger\curlyquotes\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
