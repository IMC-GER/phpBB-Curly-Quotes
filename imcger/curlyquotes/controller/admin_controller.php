<?php
/**
 *
 * Curly Quotes
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\curlyquotes\controller;

class admin_controller
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var language */
	protected $language;

	/** @var request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 *
	 * @param config				$config
	 * @param template				$template
	 * @param language				$language
	 * @param request				$request
	 * @param \phpbb\db\driver\driver_interface	$db	Driver interface object
	 *
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db
	)
	{
		$this->config	= $config;
		$this->template	= $template;
		$this->language	= $language;
		$this->request	= $request;
		$this->db		= $db;
	}

	/**
	 * Display the options a user can configure for this extension
	 *
	 * @return null
	 * @access public
	 */
	public function display_options()
	{
		/* Initial variable */
		$install_lang = '';

		/* Add ACP lang file */
		$this->language->add_lang('common', 'imcger/curlyquotes');

		add_form_key('imcger/curlyquotes');

		/* Is the form being submitted to us? */
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('imcger/curlyquotes'))
			{
				trigger_error('FORM_INVALID' . adm_back_link($this->u_action), E_USER_WARNING);
			}

			/* Store the variable to the db */
			$this->set_variable();

			trigger_error($this->language->lang('ACP_CURLYQUOTES_SETTING_SAVED') . adm_back_link($this->u_action));
		}

		$sql = 'SELECT lang_iso, lang_local_name FROM ' . LANG_TABLE;
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$install_lang .= $row['lang_iso'] . ',' . $row['lang_local_name'] . ';';
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			'U_ACTION'							=> $this->u_action,
			'IMCGER_CURLYQUOTES_INSTALL_LANG'	=> trim($install_lang, ';'),
			'IMCGER_CURLYQUOTES_LANG_SETTINGS'	=> $this->config['imcger_curlyquotes_lang_settings'],
			'IMCGER_CURLYQUOTES_SETS_PRIME'		=> $this->config['imcger_curlyquotes_sets_prime'],
		]);
	}

	/**
	 * Store the variable to the db
	 *
	 * @return null
	 * @access protected
	 */
	protected function set_variable()
	{
		$this->config->set('imcger_curlyquotes_lang_settings', trim($this->request->variable('imcger_curlyquotes_lang_settings', ''), ";"));
		$this->config->set('imcger_curlyquotes_sets_prime', $this->request->variable('imcger_curlyquotes_sets_prime', ''));
	}

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 * @return null
	 * @access public
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
