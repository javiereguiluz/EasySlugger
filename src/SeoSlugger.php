<?php

/*
 * This file is part of the EasySlugger library.
 *
 * (c) Javier Eguiluz <javier.eguiluz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EasySlugger;

/**
 * Advanced slugger that transforms into words some special parts of the
 * string, such as email addresses, numbers and currencies.
 * E.g.: string: "The product #3 costs $9.99 with a 10% discount"
 *         slug: "the-product-number-3-costs-9-dollars-99-cents-with-a-10-percent-discount".
 *
 * If you don't need these fancy transformations, use the much simpler
 * and faster Slugger class.
 */
class SeoSlugger extends Slugger implements SluggerInterface
{
    public function __construct($separator = null)
    {
        parent::__construct($separator);
    }

    /**
     * {@inheritdoc}
     */
    public static function slugify($string, $separator = null)
    {
        $string = self::expandString($string);

        return parent::slugify($string, $separator);
    }

    /**
     * {@inheritdoc}
     */
    public static function uniqueSlugify($string, $separator = null)
    {
        $string = self::expandString($string);

        return parent::uniqueSlugify($string, $separator);
    }

    /**
     * Expands the given string replacing some special parts for words.
     * e.g. "lorem@ipsum.com" is replaced by "lorem at ipsum dot com".
     *
     * Most of these transformations have been inspired by the pelle/slugger
     * project, distributed under the Eclipse Public License.
     * Copyright 2012 Pelle Braendgaard
     *
     * @param string $string The string to expand
     *
     * @return string The result of expanding the string
     */
    protected static function expandString($string)
    {
        $string = self::expandCurrencies($string);
        $string = self::expandSymbols($string);

        return $string;
    }

    /**
     * Expands the numeric currencies in euros, dollars, pounds
     * and yens that the given string may include.
     */
    private static function expandCurrencies($string)
    {
        return preg_replace(
            array(
                '/(\s|^)(\d*)\€(\s|$)/',
                '/(\s|^)\$(\d*)(\s|$)/',
                '/(\s|^)\£(\d*)(\s|$)/',
                '/(\s|^)\¥(\d*)(\s|$)/',
                '/(\s|^)(\d+)\.(\d+)\€(\s|$)/',
                '/(\s|^)\$(\d+)\.(\d+)(\s|$)/',
                '/(\s|^)£(\d+)\.(\d+)(\s|$)/',
            ),
            array(
                ' \2 euros ',
                ' \2 dollars ',
                ' \2 pounds ',
                ' \2 yen ',
                ' \2 euros \3 cents ',
                ' \2 dollars \3 cents ',
                ' \2 pounds \3 pence ',
            ),
            $string
        );
    }

    /**
     * Expands the special symbols that the given string
     * may include, such as '@', '.', '#' and '%'.
     */
    private static function expandSymbols($string)
    {
        return preg_replace(
            array(
                '/\s*&\s*/',
                '/\s*#/',
                '/\s*@\s*/',
                '/(\S|^)\.(\S)/',
                '/\s*\*\s*/',
                '/\s*%\s*/',
                '/(\s*=\s*)/',
            ),
            array(
                ' and ',
                ' number ',
                ' at ',
                '$1 dot $2',
                ' star ',
                ' percent ',
                ' equals ',
            ),
            $string
        );
    }
}
