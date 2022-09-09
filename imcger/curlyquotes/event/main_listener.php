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
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request */
	protected $request;

	/** @var \imcger\curlyquotes\core\fixer */
	protected $fixer;

	/** @var string user_language */
	protected $user_language;

	/** @var string local_language */
	protected $local_language;

	public function __construct
	(
		\phpbb\user $user,
		\phpbb\request\request $request,
		\imcger\curlyquotes\core\fixer $fixer
	)
	{
		$this->user		= $user;
		$this->request	= $request;
		$this->fixer	= $fixer;

		$this->user_language	 = substr($this->user->lang['USER_LANG'], 0, 2);
		$this->local_language = $this->get_browser_lang($this->user_language);

		$this->fixer->setLocale($this->local_language);
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.modify_text_for_display_before' => 'modify_text_for_display_before',
			'core.modify_text_for_display_after' => 'modify_text_for_display_after',
		];
	}

	public function modify_text_for_display_before($event)
	{
		$post_text = $event['text'];
		$event['text'] = $this->fixer->fix($post_text);
	}

	public function modify_text_for_display_after($event)
	{
		$post_text = $event['text'];
		$event['text'] = $post_text;
	}


	/**
	 * Check if Browser language has a country code
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

	/**
	 * Logger for debugging
	 *
	 * @param $err	Message to log
	 * @param $verbosity	0: log as-is, 1: use print_r(), 2: use var_dump()
	 */
	public function dbg_log($err, $verbosity = 0)
	{
		$log_file = 'a_log.txt';
		if ($verbosity == 1)
		{
			$err = print_r($err, true);
		}
		else if ($verbosity == 2)
		{
			ob_start();
			var_dump($err);
			$err = ob_get_clean();
		}
		error_log ($err . "\n", 3, $log_file);
	}

}
