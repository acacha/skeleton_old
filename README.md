<<<<<<< HEAD
# skeleton1

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

**Note:** Replace ```Sergi Tur Badenas``` ```acacha``` ```https://github.com/acacha``` ```sergiturbadenas@gmail.com``` ```acacha``` ```skeleton1``` `````` with their correct values in [README.md](README.md), [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](CONTRIBUTING.md), [LICENSE.md](LICENSE.md) and [composer.json](composer.json) files, then delete this line. You can run `$ php prefill.php` in the command line to make all replacements at once. Delete the file prefill.php as well.

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Com funciona?

Hem creat dos projectes/carpetes:
- **skeleton**: Paquet que volem desenvolupar. Creat a partir https://github.com/thephpleague/skeleton
- **skeleton_test**: Projecte Laravel amb adminlte.

Comandes:

 $ git clone git@github.com:thephpleague/skeleton.git
 $ adminlte skeleton_test 

1)  Executar prefill al paquet

```bash
cd skeleton
php prefill.php
```

2) Restaurar git
        
```bash
cd skeleton
rm -rf .git
llum github:init
```

3) Studio

Instalar el paquet

```bash
composer global require franzl/studio
cd skeleton_test
studio load ../skeleton
composer require "acacha/skeleton1":"dev-master"
```

Comproveu fitxers studio.json i composer.json.
 
## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practises by being named the following.

```
bin/        
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require acacha/skeleton1
```

## Usage

``` php
$skeleton = new Acacha\Skeleton1();
echo $skeleton->echoPhrase('Hello, League!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email sergiturbadenas@gmail.com instead of using the issue tracker.

## Credits

- [Sergi Tur Badenas][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/acacha/skeleton1.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/acacha/skeleton1/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/acacha/skeleton1.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/acacha/skeleton1.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/acacha/skeleton1.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/acacha/skeleton1
[link-travis]: https://travis-ci.org/acacha/skeleton1
[link-scrutinizer]: https://scrutinizer-ci.com/g/acacha/skeleton1/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/acacha/skeleton1
[link-downloads]: https://packagist.org/packages/acacha/skeleton1
[link-author]: https://github.com/acacha
[link-contributors]: ../../contributors
=======
skeleton
========

skeleton
>>>>>>> e2535ae7aff1649f8cf0451de8a8e689ff903ac6
