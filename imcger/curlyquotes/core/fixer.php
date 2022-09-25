<?php
/*
 * This code is part of JoliTypo - a project by JoliCode.
 *
 * Copyright (c) 2013 Damien Alexandre (http://jolicode.com)
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * https://en.wikipedia.org/wiki/Quotation_mark
 * https://www.virtualsystem.de/browsersprachen/
 */

namespace imcger\curlyquotes\core;

class fixer
{
	/**
	 * DOMDocument does not like all the HTML entities; sometimes they are double encoded.
	 * So the entities here are plain utf8 and DOCDocument::saveHTML transform them to entity.
	 */
	public const ALL_SPACES			 = "\xE2\x80\xAF|\xC2\xAD|\xC2\xA0|\\s"; // All supported spaces, used in regexps. Better than \s
	public const NO_BREAK_THIN_SPACE = "\xE2\x80\xAF"; // &#8239;
	public const NO_BREAK_SPACE		 = "\xC2\xA0"; // &#160;
	public const SHY	  = "\xC2\xAD"; // &shy;
	public const ELLIPSIS = '…'; // &hellip;
	public const LDQUO	= '“'; // &ldquo; or &#8220;
	public const RDQUO	= '”'; // &rdquo; or &#8221;
	public const BDQUO	= '„'; // &bdquo; or &#8222;
	public const LSQUO	= '‘'; // &lsquo;
	public const RSQUO	= '’'; // &rsquo;
	public const SBQUO	= '‚'; // &sbquo;
	public const LAQUO	= '«'; // &laquo;
	public const RAQUO	= '»'; // &raquo;
	public const LSAQUO = '‹'; // &lsaquo;
	public const RSAQUO = '›'; // &rsaquo;
	public const PRIME	= '″'; // &Prime;
	public const SPRIME = '′'; // &prime;
	public const TIMES	= '×'; // &times;
	public const NDASH	= '–'; // &ndash; or &#x2013;
	public const MDASH	= '—'; // &mdash; or &#x2014;
	public const TRADE	= '™'; // &trade;
	public const REG	= '®'; // &reg;
	public const COPY	= '©'; // &copy;

	protected $dopening;
	protected $dclosing;
	protected $dopeningSuffix = '';
	protected $dclosingPrefix = '';
	protected $sopening;
	protected $sclosing;
	protected $sopeningSuffix = '';
	protected $sclosingPrefix = '';

	public function fix($content, $set_prime)
	{
		// Break when locale not set
		if (!$this->dopening || !$this->dclosing || !$this->sopening || !$this->sclosing)
		{
			 return $content;
		}

		$pattern = [
			'@([a-z0-9])\'([a-z])@im',			// Apostrophe
			'@(^|\s|\>|\()"([^"]+)"@im',		// Double Quotes
			'@(^|\s|\>|\()\'([^\']+)\'@im',		// Single Quotes
			'@([^\d\s]+)[' . self::ALL_SPACES . ']*(,)[' . self::ALL_SPACES . ']*@mu', // No space before comma (,)
		];

		$replacement = [
			'$1' . self::RSQUO . '$2',
			'$1' . $this->dopening . $this->dopeningSuffix . '$2' . $this->dclosingPrefix . $this->dclosing,
			'$1' . $this->sopening . $this->sopeningSuffix . '$2' . $this->sclosingPrefix . $this->sclosing,
			'$1$2 ',
		];

		// Fix simple cases
		$content = preg_replace($pattern, $replacement, $content);

		if ($set_prime)
		{
			// Replace single quotes with prime
			$content = str_replace("'", self::SPRIME, $content);

			// Replace double quotes with Prime
			$prime_pos = strpos($content, '"', 0);
			while ($prime_pos)
			{
				// Don't replace quotes between <…>
				$stag = strpos($content, '<', $prime_pos);
				$btag = strpos($content, '>', $prime_pos);

				if ($stag === false || ($stag < $btag))
				{
					$content = substr_replace($content, self::PRIME, $prime_pos, 1);
				}

				$prime_pos = strpos($content, '"', $prime_pos+1);
			}
		}

		return $content;
	}

	/**
	 * Default configuration for supported lang.
	 *
	 * @param string $locale
	 */
	public function setLocale($locale)
	{
		switch ($locale[1])
		{
			// “…”
			case 1:
				$this->dopening = self::LDQUO;
				$this->dclosing = self::RDQUO;
				$this->dopeningSuffix = '';
				$this->dclosingPrefix = '';

				break;
			// ”…”
			case 2:
				$this->dopening = self::RDQUO;
				$this->dclosing = self::RDQUO;
				$this->dopeningSuffix = '';
				$this->dclosingPrefix = '';

				break;
			// „…“
			case 3:
				$this->dopening = self::BDQUO;
				$this->dclosing = self::LDQUO;
				$this->dopeningSuffix = '';
				$this->dclosingPrefix = '';

				break;
			// «…»
			case 4:
				$this->dopening = self::LAQUO;
				$this->dclosing = self::RAQUO;
				$this->dopeningSuffix = '';
				$this->dclosingPrefix = '';

				break;
			// « … »
			case 5:
				$this->dopening = self::LAQUO;
				$this->dclosing = self::RAQUO;
				$this->dopeningSuffix = self::NO_BREAK_THIN_SPACE;
				$this->dclosingPrefix = self::NO_BREAK_THIN_SPACE;

				break;
			// »…«
			case 6:
				$this->dopening = self::RAQUO;
				$this->dclosing = self::LAQUO;
				$this->dopeningSuffix = '';
				$this->dclosingPrefix = '';

				break;
		}

		switch ($locale[2])
		{
			// ‘…’
			case 1:
				$this->sopening = self::LSQUO;
				$this->sclosing = self::RSQUO;
				$this->sopeningSuffix = '';
				$this->sclosingPrefix = '';

				break;
			// ’…’
			case 2:
				$this->sopening = self::RSQUO;
				$this->sclosing = self::RSQUO;
				$this->sopeningSuffix = '';
				$this->sclosingPrefix = '';

				break;
			// ‚…‘
			case 3:
				$this->sopening = self::SBQUO;
				$this->sclosing = self::LSQUO;
				$this->sopeningSuffix = '';
				$this->sclosingPrefix = '';

				break;
			// ‹…›
			case 4:
				$this->sopening = self::LSAQUO;
				$this->sclosing = self::RSAQUO;
				$this->sopeningSuffix = '';
				$this->sclosingPrefix = '';

				break;
			// ‹ … ›
			case 5:
				$this->sopening = self::LSAQUO;
				$this->sclosing = self::RSAQUO;
				$this->sopeningSuffix = self::NO_BREAK_THIN_SPACE;
				$this->sclosingPrefix = self::NO_BREAK_THIN_SPACE;

				break;
			// ›…‹
			case 6:
				$this->sopening = self::RSAQUO;
				$this->sclosing = self::LSAQUO;
				$this->sopeningSuffix = '';
				$this->sclosingPrefix = '';

				break;
		}
	}

	/**
	 * Get language part of a Locale string (fr_FR => fr).
	 *
	 * @param $locale
	 *
	 * @return string
	 */
	public static function getLanguageFromLocale($locale)
	{
		if (strpos($locale, '-'))
		{
			$parts = explode('-', $locale);

			return strtolower($parts[0]);
		}

		return $locale;
	}

}
