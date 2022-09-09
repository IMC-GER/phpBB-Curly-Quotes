<?php
/*
 * This code is part of JoliTypo - a project by JoliCode.
 *
 * Copyright (c) 2013 Damien Alexandre (http://jolicode.com)
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * https://en.wikipedia.org/wiki/Quotation_mark
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
	public const TIMES	= '×'; // &times;
	public const NDASH	= '–'; // &ndash; or &#x2013;
	public const MDASH	= '—'; // &mdash; or &#x2014;
	public const TRADE	= '™'; // &trade;
	public const REG	= '®'; // &reg;
	public const COPY	= '©'; // &copy;

    protected $opening;
    protected $openingSuffix = '';
    protected $closing;
    protected $closingPrefix = '';

    public function fix($content)
    {
        if (!$this->opening || !$this->closing)
		{
             return $content;
        }

        // Fix simple cases
        return preg_replace(
            '@(^|\s|\()"([^"]+)"@im',
            '$1' . $this->opening . $this->openingSuffix . '$2' . $this->closingPrefix . $this->closing,
            $content
        );
    }

	/**
	 * Default configuration for supported lang.
	 *
	 * @param string $locale
	 */
	public function setLocale($locale)
	{
		// Handle from locale + country
		switch (strtolower($locale)) {
			// “…”
			case 'pt-br':
				$this->opening = self::LDQUO;
				$this->openingSuffix = '';
				$this->closing = self::RDQUO;
				$this->closingPrefix = '';

				return;
			// «…»
			case 'de-ch':
				$this->opening = self::LAQUO;
				$this->openingSuffix = '';
				$this->closing = self::RAQUO;
				$this->closingPrefix = '';

				return;
		}

		// Handle from locale only
		$short = $this->getLanguageFromLocale($locale);

		switch ($short) {
			// « … »
			case 'fr':
				$this->opening = self::LAQUO;
				$this->openingSuffix = self::NO_BREAK_THIN_SPACE;
				$this->closing = self::RAQUO;
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
				$this->opening = self::LAQUO;
				$this->openingSuffix = '';
				$this->closing = self::RAQUO;
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
				$this->opening = self::BDQUO;
				$this->openingSuffix = '';
				$this->closing = self::LDQUO;
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
				$this->opening = self::LDQUO;
				$this->openingSuffix = '';
				$this->closing = self::RDQUO;
				$this->closingPrefix = '';

				break;
			// ”…”
			case 'fi':
			case 'sv':
			case 'bs':
				$this->opening = self::RDQUO;
				$this->openingSuffix = '';
				$this->closing = self::RDQUO;
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
