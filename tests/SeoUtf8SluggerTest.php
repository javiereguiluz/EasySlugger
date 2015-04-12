<?php

/*
 * * This file is part of the EasySlugger library.
 *
 * (c) Javier Eguiluz <javier.eguiluz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EasySlugger\tests;

class SeoUtf8SluggerTest extends BaseSluggerTest
{
    public function setUp()
    {
        if (!function_exists('transliterator_transliterate')) {
            $this->markTestSkipped('SeoUtf8SluggerTest tests require PHP >= 5.4.0 and intl >= 2.0 extension.');
        }

        $this->sluggerClassName = 'SeoUtf8Slugger';

        parent::setUp();
    }

    public function provideSlugFileNames()
    {
        return array(
            array('strings.txt'),
        );
    }
}
