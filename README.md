# tree-parser

[![Latest Version on Packagist][ico-version]](https://packagist.org/packages/baopham/tree-parser)
[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/baopham/php-tree-parser.svg?branch=master)](https://travis-ci.org/baopham/php-tree-parser)
[![Code Coverage](https://scrutinizer-ci.com/g/baopham/php-tree-parser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/baopham/php-tree-parser/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/baopham/php-tree-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/baopham/php-tree-parser/?branch=master)

Parse this:

```
   Root
     |- Level 1.1
       |- Level 2.1
     |- Level 1.2
       |- Level 2.2
         |- Level 3
           |- Level 4
```

to:

```php
foreach ($root->children as $child) {
    print $child->name;

    print $child->order;

    print $child->level;

    print_r($child->children);

    print_r($child->children[0]->children);

    print $child->children[0]->parent === $child;
}
```

## Install

Via Composer

``` bash
$ composer require baopham/tree-parser
```

## Usage

``` php
$tree = <<<TREE
  Root
    |- Level 1 - Order 1
      |- Level 2 - Order 2
        |- Level 3 - Order 3
        |- Level 3 - Order 4
      |- Level 2 - Order 5
    |- Level 1 - Order 6
      |- Level 2 - Order 7
        |- Level 3 - Order 8
          |- Level 4 - Order 9
TREE;

$parser = new BaoPham\TreeParser($tree);

$root = $parser->parse();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [Bao Pham](https://github.com/baopham)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/baopham/tree-parser.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/baopham/tree-parser/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/baopham/tree-parser.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/baopham/tree-parser.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/baopham/tree-parser.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/baopham/tree-parser
[link-travis]: https://travis-ci.org/baopham/tree-parser
[link-scrutinizer]: https://scrutinizer-ci.com/g/baopham/tree-parser/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/baopham/tree-parser
[link-downloads]: https://packagist.org/packages/baopham/tree-parser
[link-author]: https://github.com/baopham
[link-contributors]: ../../contributors
