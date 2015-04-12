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

use EasySlugger\Slugger;

abstract class BaseSluggerTest extends \PHPUnit_Framework_TestCase
{
    protected $sluggerClassName;
    protected $slugger;
    protected $inputFixturesDir;
    protected $expectedFixturesDir;

    public function setUp()
    {
        $sluggerClassNamespace = '\\EasySlugger\\'.$this->sluggerClassName;
        $this->slugger = new $sluggerClassNamespace();

        $fixturesBaseDir = __DIR__.'/fixtures/'.strtolower($this->sluggerClassName);
        $this->inputFixturesDir    = $fixturesBaseDir.'/input';
        $this->expectedFixturesDir = $fixturesBaseDir.'/expected';
    }

    /**
     * @dataProvider provideSlugFileNames
     */
    public function testDefaultSlugify($fileName)
    {
        $inputStrings  = file($this->inputFixturesDir.'/'.$fileName, FILE_IGNORE_NEW_LINES);
        $expectedSlugs = file($this->expectedFixturesDir.'/'.$fileName, FILE_IGNORE_NEW_LINES);

        $slugger = $this->slugger;
        $slugs  = array_map(function ($string) use ($slugger) {
            return $slugger->slugify($string);
        }, $inputStrings);

        $this->assertSame($expectedSlugs, $slugs);
    }

    public function testUniqueSlugify()
    {
        $input = 'AbCdEf';
        $output = $this->slugger->uniqueSlugify($input);

        $this->assertRegexp('/abcdef\-(.*){7}/', $output);
    }

    public function testUniqueSlugifyRandomness()
    {
        $input = 'AbCdEf';

        $randomSlugs = array();
        foreach (range(1, 100) as $i) {
            $slug = $this->slugger->uniqueSlugify($input);
            $this->assertNotContains($slug, $randomSlugs);
            $randomSlugs[] = $slug;
        }
    }

    public function testSluggerOptions()
    {
        $slugger = new \EasySlugger\Slugger('_');
        $input = ' a+A+ - a+A_a _';
        $output = $slugger->slugify($input);

        $this->assertSame('a_a_a_a_a', $output);
    }

    public function testSlugifyOptions()
    {
        $input = ' a+A+ - a+A_a _';
        $output = $this->slugger->slugify($input, '_');

        $this->assertSame('a_a_a_a_a', $output);
    }

    public function testSlugifyOptionsOverrideSluggerOptions()
    {
        $slugger = new \EasySlugger\Slugger('+');
        $input = ' a+A+ - a+A_a _';
        $output = $slugger->slugify($input, '_');

        $this->assertSame('a_a_a_a_a', $output);
    }

    /**
     * @dataProvider provideSlugEdgeCases
     */
    public function testSlugifyEdgeCases($string, $expectedSlug)
    {
        $slug = $this->slugger->slugify($string);

        $this->assertSame($expectedSlug, $slug);
    }

    public function provideSlugEdgeCases()
    {
        return array(
            array('', ''),
            array('    ', ''),
            array('-', ''),
            array('-A', 'a'),
            array('A-', 'a'),
            array('-----', ''),
            array('-a-A-A-a-', 'a-a-a-a'),
            array('A-a-A-a-A-a', 'a-a-a-a-a-a'),
            array(' -- ', ''),
            array('a--A', 'a-a'),
            array(' - - ', ''),
            array(' -A- ', 'a'),
            array(' - A - ', 'a'),
            array(null, ''),
            array(true, '1'),
            array(false, ''),
            array(1, '1'),
        );
    }
}
