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
 * Represents the interface that all sluggers must implement.
 */
interface SluggerInterface
{
    /**
     * Returns the slug corresponding to the given string and separator.
     *
     * @param string $string    The string to transform into a slug
     * @param string $separator The character/string used to separate slug words
     *
     * @return string The slug that represents the string
     */
    public static function slugify($string, $separator = null);

    /**
     * Returns the slug corresponding to the given string and separator and
     * ensures its uniqueness by appending a random suffix.
     *
     * @param string $string    The string to transform into a slug
     * @param string $separator The character/string used to separate slug words
     *
     * @return string The unique slug that represents the string
     */
    public static function uniqueSlugify($string, $separator = null);
}
