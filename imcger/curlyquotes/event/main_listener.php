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

		$lang_settings = strtolower($this->config['imcger_curlyquotes_lang_settings']);
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
			'core.modify_text_for_display_before'	 => 'modify_text_for_display', 	// Fix in post
			'core.modify_format_display_text_before' => 'modify_text_for_display',	// Fix in preview
		];
	}

	/**
	 * Fix the quotes before display in post
	 * Fix the quotes before display in preview
	 *
	 * @param	object	$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function modify_text_for_display($event)
	{
		$post_text = $event['text'];

		// Check if code in text
		if (str_contains($post_text, '<CODE>'))
		{
			$offset = 0;
			$new_post_text = '';

			// Find code in post
			while (preg_match('#(<CODE>)(.*?)(</CODE>)#i', $post_text, $match, PREG_OFFSET_CAPTURE, $offset))
			{
				// Fix string before code
				$str2fix = substr($post_text, $offset, $match[0][1] - $offset);
				$new_post_text .= $this->fixer->fix($str2fix, $this->config['imcger_curlyquotes_sets_prime']);

				// Add code
				$new_post_text .= $match[0][0];

				// New start posion to find next code
				$offset = $match[3][1] + 7;
			}

			// Fix string behind code
			$str2fix = substr($post_text, $offset);
			$new_post_text .= $this->fixer->fix($str2fix, $this->config['imcger_curlyquotes_sets_prime']);

			$event['text'] = $new_post_text;
		}
		else
		{
			$event['text'] = $this->fixer->fix($post_text, $this->config['imcger_curlyquotes_sets_prime']);
		}
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
