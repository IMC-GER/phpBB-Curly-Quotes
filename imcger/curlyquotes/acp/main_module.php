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
 * Collapse Quote ACP module.
 */
class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container, $language;

		/* Add ACP lang file */
		$language->add_lang('common', 'imcger/curlyquotes');

		/* Get an instance of the admin controller */
		$admin_controller = $phpbb_container->get('imcger.curlyquotes.admin.controller');

		/* Make the $u_action url available in the admin controller */
		$admin_controller->set_page_url($this->u_action);

		switch ($mode)
		{
			case 'settings':
				/* Load a template from adm/style for our ACP page */
				$this->tpl_name = 'acp_curlyquotes_body';

				/* Set the page title for our ACP page */
				$this->page_title = $language->lang('ACP_CURLYQUOTES_TITLE');

				/* Load the display options handle in the admin controller */
				$admin_controller->display_options();
			break;
		}
	}
}
