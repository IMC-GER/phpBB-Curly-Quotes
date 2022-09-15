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
    protected $sopening;
    protected $openingSuffix = '';
    protected $dclosing;
    protected $sclosing;
    protected $closingPrefix = '';

    public function fix($content, $set_prime)
    {
		// Break when locale not set
        if (!$this->dopening || !$this->dclosing)
		{
             return $content;
        }

		$pattern = [
			'@([^\s][a-z0-9])\'([a-z])@im',		// Apostrophe
			'@(^|\s|\>|\()"([^"]+)"@im',		// Double Quotes
			'@(^|\s|\>|\()\'([^\']+)\'@im',		// Single Quotes
			'@([^\d\s]+)[' . self::ALL_SPACES . ']*(,)[' . self::ALL_SPACES . ']*@mu', // No space before comma (,)
		];

		$replacement = [
			'$1' . self::RSQUO . '$2',
			'$1' . $this->dopening . $this->openingSuffix . '$2' . $this->closingPrefix . $this->dclosing,
			'$1' . $this->sopening . $this->openingSuffix . '$2' . $this->closingPrefix . $this->sclosing,
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
		// Handle from locale + country
		switch (strtolower($locale[0])) {
			// “…”
			case 'pt-br':
				$this->dopening = self::LDQUO;
				$this->sopening = self::LSQUO;
				$this->openingSuffix = '';
				$this->dclosing = self::RDQUO;
				$this->sclosing = self::RSQUO;
				$this->closingPrefix = '';

				return;
			// «…»
			case 'de-ch':
				$this->dopening = self::LAQUO;
				$this->sopening = self::LSAQUO;
				$this->openingSuffix = '';
				$this->dclosing = self::RAQUO;
				$this->sclosing = self::RSAQUO;
				$this->closingPrefix = '';

				return;
		}

		// Handle from locale only
		$short = $this->getLanguageFromLocale($locale[0]);

		switch ($short) {
			// « … »
			case 'fr':
				$this->dopening = self::LAQUO;
				$this->sopening = self::LSAQUO;
				$this->openingSuffix = self::NO_BREAK_THIN_SPACE;
				$this->dclosing = self::RAQUO;
				$this->sclosing = self::RSAQUO;
				$this->closingPrefix = self::NO_BREAK_THIN_SPACE;

				break;
			// «…»
			case 'hy':
			case 'az':
			case 'hz':
			case 'eu':
			case 'be':
			case 'ca':
			case 'el':
			case 'it':
			case 'no':
			case 'fa':
			case 'lv':
			case 'pt':
			case 'ru':
			case 'es':
			case 'uk':
				$this->dopening = self::LAQUO;
				$this->sopening = self::LSAQUO;
				$this->openingSuffix = '';
				$this->dclosing = self::RAQUO;
				$this->sclosing = self::RSAQUO;
				$this->closingPrefix = '';

				break;
			// „…“
			case 'de':
			case 'ka':
			case 'cs':
			case 'et':
			case 'is':
			case 'lt':
			case 'mk':
			case 'ro':
			case 'sk':
			case 'sl':
			case 'wen':
				$this->dopening = self::BDQUO;
				$this->sopening = self::SBQUO;
				$this->openingSuffix = '';
				$this->dclosing = self::LDQUO;
				$this->sclosing = self::LSQUO;
				$this->closingPrefix = '';

				break;
			// “…”
			case 'en':
			case 'us':
			case 'gb':
			case 'af':
			case 'ar':
			case 'eo':
			case 'id':
			case 'ga':
			case 'ko':
			case 'br':
			case 'th':
			case 'tr':
			case 'vi':
				$this->dopening = self::LDQUO;
				$this->sopening = self::LSQUO;
				$this->openingSuffix = '';
				$this->dclosing = self::RDQUO;
				$this->sclosing = self::RSQUO;
				$this->closingPrefix = '';

				break;
			// ”…”
			case 'fi':
			case 'sv':
			case 'bs':
				$this->dopening = self::RDQUO;
				$this->sopening = self::RSQUO;
				$this->openingSuffix = '';
				$this->dclosing = self::RDQUO;
				$this->sclosing = self::RSQUO;
				$this->closingPrefix = '';

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
        if (strpos($locale, '-')) {
            $parts = explode('-', $locale);

            return strtolower($parts[0]);
        }

        return $locale;
    }

}
