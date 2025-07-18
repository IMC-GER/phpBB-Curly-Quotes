<?php
/**
 * Curly Quotes
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\curlyquotes\acp;

/**
 * Curly Quotes ACP module info.
 */
class main_info
{
	public function module()
	{
		return [
			'filename'	=> '\imcger\curlyquotes\acp\main_module',
			'title'		=> 'ACP_CURLYQUOTES_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_CURLYQUOTES_SETTINGS',
					'auth'	=> 'ext_imcger/curlyquotes && acl_a_board',
					'cat'	=> ['ACP_CURLYQUOTES_TITLE',],
				],
			],
		];
	}
}
