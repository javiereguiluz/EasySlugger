EasySlugger
===========

**EasySlugger** is a fast PHP library to generate slugs, which allow to
safely include any string as part of an URL. Slugs are commonly used for CMS,
blogs and other content-related platforms.

Installation
------------

Add this library as a dependency of your project using [Composer](https://getcomposer.org/):

```bash
$ cd your-project/
$ composer require javiereguiluz/slugger
```

Generating slugs
----------------

Most slugger libraries include a lot of settings to configure how the slugs are
generated. **EasySlugger** uses a different approach to provide both a great 
performance and flexibility: it includes four different slugger classes!

  * `Slugger`, fast slugs suited for most European languages.
  * `Utf8Slugger`, UTF-8-compliant slugger suitable for any alphabet (including
    Japanese, Arabic and Hebrew languages). It requires PHP 5.4.0 or higher.
  * `SeoSlugger`, advanced slugger that augments the strings before turning
    them into slugs. For instance, the string `The product #3 costs $9.99` is
    turned into `the-product-number-3-costs-9-dollars-99-cents`.
  * `SeoUtf8Slugger`, combines the `Utf8Slugger` and the `SeoSlugger` to
    augment and slugify any UTF-8 string.

All sluggers implement the `SluggerInterface`, which allows you to safely
switch from one slugger to another in your projects.

Generating basic slugs
----------------------

The easiest way to generate slugs is to use the `slugify()` method of the
`Slugger` class:

```php
use EasySlugger\Slugger;

$slug = Slugger::slugify('Lorem Ipsum');  // slug = lorem-ipsum
```

You can also instantiate the `Slugger` class to use it non-statically:

```php
// PHP 5.4 or lower
use EasySlugger\Slugger;

$slugger = new Slugger();
$slug = $slugger->slugify('Lorem Ipsum'); // slug = lorem-ipsum

// PHP 5.5 or higher
$slugger = (new \EasySlugger\Slugger())->slugify('Lorem Ipsum');
```

### Generating unique slugs

If you need to ensure the uniqueness of the slugs generated during the
execution of your application, use the `uniqueSlugify()` method, which appends
a random suffix to the slug:

```php
use EasySlugger\Slugger;

$slug = Slugger::uniqueSlugify('Lorem Ipsum'); // slug = lorem-ipsum-a2b342f
```

Unique slugs are non-deterministic, meaning that the appended suffix is random
and it will change in each application execution, even when using the same
input string. If you want to append an autoincremental numeric suffix to the
slugs, you'll need to develop your own custom solution.

Generating slugs for non-latin alphabets
----------------------------------------

If the strings contain characters belonging to non-latin alphabets such as
Arabic, Hebrew and Japanese, you should use the `Utf8Slugger` class. This
slugger requires PHP 5.4.0 or higher because it uses the built-in PHP
transliterator: 

```php
use EasySlugger\Utf8Slugger;

$slug = Utf8Slugger::slugify('日本語');  // slug = ri-ben-yu
$slug = Utf8Slugger::slugify('العَرَبِيةُ‎‎');    // slug = alrbyt
$slug = Utf8Slugger::slugify('עברית');  // slug = bryt
```

`Utf8Slugger` also defines the `uniqueSlugify()` to generate unique slugs.

Generating SEO slugs
--------------------

The `SeoSlugger` (and the related `SeoUtf8Slugger`) augments the strings
before turning them into slugs. The conversions are related to numbers,
currencies, email addresses and other common symbols:

```php
use EasySlugger\SeoSlugger;

$slug = SeoSlugger::slugify('The price is $5.99');
// slug = the-price-is-5-dollars-99-cents

$slug = SeoSlugger::slugify('Use lorem@ipsum.com to get a 10% discount');
// slug = use-lorem-at-ipsum-dot-com-to-get-a-10-percent-discount

$slug = SeoSlugger::slugify('Gravity = 9.81 m/s2');
// slug = gravity-equals-9-dot-81-m-s2
```

`SeoSlugger` and `SeoUtf8Slugger` also define the `uniqueSlugify()` to 
generate unique slugs.

Configuration options
---------------------

The only configuration option available is the `separator` character (or 
string) used to separate each of the slug parts. First, you can
set this parameter globally using the class constructor:

```php
use EasySlugger\Slugger;

$slugger = new Slugger();
$slug = $slugger::slugify('Lorem Ipsum'); // slug = lorem-ipsum

$slugger = new Slugger('_');
$slug = $slugger::slugify('Lorem Ipsum'); // slug = lorem_ipsum

$slugger = new Slugger('');
$slug = $slugger::slugify('Lorem Ipsum'); // slug = loremipsum
```

You can also set this parameter as the second optional argument of the
`slugify()` and `uniqueSlugify()` methods. This parameter always overrides
any global parameter set by the class:

```php
use EasySlugger\Slugger;

$slugger = new Slugger();
$slug = Slugger::slugify('Lorem Ipsum', '_'); // slug = lorem_ipsum
$slug = Slugger::slugify('Lorem Ipsum', '');  // slug = loremipsum

$slugger = new Slugger('+');
$slug = $slugger::slugify('Lorem Ipsum', '_'); // slug = lorem_ipsum
```

License
-------

This library is licensed under the [MIT license](LICENSE.md).

Tests
-----

The library is fully unit tested. If you have [PHPUnit](http://phpunit.de/) 
installed, execute `phpunit` command to run the complete test suite:

```bash
$ cd easyslugger/
$ composer install
$ phpunit
```
