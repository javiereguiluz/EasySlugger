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

class Utf8SluggerTest extends BaseSluggerTest
{
    public function setUp()
    {
        if (!function_exists('transliterator_transliterate')) {
            $this->markTestSkipped('Utf8Slugger tests require PHP >= 5.4.0 and intl >= 2.0 extension.');
        }

        $this->sluggerClassName = 'Utf8Slugger';

        parent::setUp();
    }

    public function provideSlugFileNames()
    {
        return array(
            array('iso-8859-1.txt'),
            array('iso-8859-2.txt'),
            array('iso-8859-3.txt'),
            array('iso-8859-4.txt'),
            array('pangrams.txt'),
            array('arabic.txt'),
            array('hebrew.txt'),
            array('japanese.txt'),
        );
    }
}
