# Omnipay: Paysafecard

**Paysafecard driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/dercoder/omnipay-paysafecard.png?branch=master)](https://travis-ci.org/dercoder/omnipay-paysafecard)
[![Coverage Status](https://coveralls.io/repos/dercoder/omnipay-paysafecard/badge.svg?branch=master&service=github)](https://coveralls.io/github/dercoder/omnipay-paysafecard?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/55e74aa7211c6b001f000619/badge.png)](https://www.versioneye.com/user/projects/55e74aa7211c6b001f000619)

[![Latest Stable Version](https://poser.pugx.org/dercoder/omnipay-paysafecard/v/stable.png)](https://packagist.org/packages/dercoder/omnipay-paysafecard)
[![Total Downloads](https://poser.pugx.org/dercoder/omnipay-paysafecard/downloads.png)](https://packagist.org/packages/dercoder/omnipay-paysafecard)
[![Latest Unstable Version](https://poser.pugx.org/dercoder/omnipay-paysafecard/v/unstable.png)](https://packagist.org/packages/dercoder/omnipay-paysafecard)
[![License](https://poser.pugx.org/dercoder/omnipay-paysafecard/license.png)](https://packagist.org/packages/dercoder/omnipay-paysafecard)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements [Paysafecard](http://www.paysafecard.com) support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "dercoder/omnipay-paysafecard": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Paysafecard

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/dercoder/omnipay-paysafecard/issues),
or better yet, fork the library and submit a pull request.