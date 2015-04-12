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
 * UTF-8-compliant slugger suitable for any alphabet (including
 * japanese, arabic and hebrew languages). If you don't need to slugify
 * strings that make use of those languages, use instead the much faster
 * Slugger class.
 */
class Utf8Slugger implements SluggerInterface
{
    protected static $separator;

    public function __construct($separator = null)
    {
        if (!function_exists('transliterator_transliterate')) {
            throw new \RuntimeException('Unable to use Utf8Slugger (it requires PHP >= 5.4.0 and intl >= 2.0 extension).');
        }

        self::$separator = (null !== $separator) ? $separator : '-';
    }

    /**
     * {@inheritdoc}
     */
    public static function slugify($string, $separator = null)
    {
        $separator = (null !== $separator) ? $separator : ((null != self::$separator) ? self::$separator : '-');

        $slug = trim(strip_tags($string));
        $slug = transliterator_transliterate(
            'NFD; [:Nonspacing Mark:] Remove; NFC; Any-Latin; Latin-ASCII; Lower();',
            $slug
        );
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = preg_replace("/[\/_|+ -]+/", $separator, $slug);
        $slug = trim($slug, $separator);

        return $slug;
    }

    /**
     * {@inheritdoc}
     */
    public static function uniqueSlugify($string, $separator = null)
    {
        $separator = (null !== $separator) ? $separator : ((null != self::$separator) ? self::$separator : '-');

        $slug = self::slugify($string, $separator);
        $slug .= $separator.substr(md5(mt_rand()), 0, 7);

        return $slug;
    }
}
