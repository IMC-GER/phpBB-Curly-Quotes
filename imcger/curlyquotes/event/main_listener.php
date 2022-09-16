<?php
/**
*
* Implements Curly Quotes in phpBB.
* An extension for the phpBB Forum Software package.
*
* @copyright (c) 2022, Thorsten Ahlers
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace imcger\curlyquotes\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Curly Quotes listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request */
	protected $request;

	/** @var \imcger\curlyquotes\core\fixer */
	protected $fixer;

	/**
	 * Constructor for listener
	 *
	 * @param \phpbb\config\config				$config		phpBB config
	 * @param \phpbb\request\request			$request	phpBB request
	 * @param \imcger\curlyquotes\core\fixer	$fixer
	 *
	 * @access public
	 */
	public function __construct
	(
		\phpbb\config\config $config,
		\phpbb\user $user,
		\phpbb\request\request $request,
		\imcger\curlyquotes\core\fixer $fixer
	)
	{
		$this->config	= $config;
		$this->user		= $user;
		$this->request	= $request;
		$this->fixer	= $fixer;

		// Get language for fixer
		$user_language	 = $this->fixer->getLanguageFromLocale($this->user->lang['USER_LANG']);
		$local_language = $this->get_browser_lang($user_language);

		$lang_settings = $this->config['imcger_curlyquotes_lang_settings'];
		$lang_set = explode(';', $lang_settings);

		// Browser language without country code if no parameters set
		if (!str_contains($lang_settings, $local_language))
		{
			$local_language = $this->fixer->getLanguageFromLocale($local_language);
		}

		foreach ($lang_set as $value)
		{
			$lang = explode(',', $value);

			if ($lang[0] == $local_language)
			{
				break;
			}
		}

		// Set local language in fixer
		$this->fixer->setLocale($lang);
	}

	/**
	 * Returns an array of events the object is subscribed to
	 *
	 * @param	null
	 * @return	array
	 * @access	public
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.modify_text_for_display_before' => 'modify_text_for_display_before',
		];
	}

	/**
	 * Fix the quotes before display in post
	 *
	 * @param	object	$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function modify_text_for_display_before($event)
	{
		$post_text = $event['text'];
		$event['text'] = $this->fixer->fix($post_text, $this->config['imcger_curlyquotes_sets_prime']);
	}

	/**
	 * Check if Browser has the board language with country code
	 *
	 * @param $default_lang	Board language
	 * @return				Browser language or default language
	 * @access	public
	 */
	public function get_browser_lang($default_lang = 'en')
	{
		$accept_language = $this->request->server('HTTP_ACCEPT_LANGUAGE', 'false');

		$accept_language = str_replace('_', '-', $accept_language);
		$accept_language = strtolower($accept_language);
		$lang = explode(',', $accept_language);

		foreach ($lang as $value)
		{
			$value = trim($value);

			if (str_contains($value, $default_lang))
			{
				$local = explode(';', $value);
				break;
			}
		}

		return !empty($local[0]) ? $local[0] : $default_lang;
	}
}
