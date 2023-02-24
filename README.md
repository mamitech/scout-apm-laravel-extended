# scout-apm-laravel-extended

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mamitech/scout-apm-laravel-extended.svg?style=flat-square)](https://packagist.org/packages/mamitech/scout-apm-laravel-extended)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mamitech/scout-apm-laravel-extended/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mamitech/scout-apm-laravel-extended/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mamitech/scout-apm-laravel-extended/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/mamitech/scout-apm-laravel-extended/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mamitech/scout-apm-laravel-extended.svg?style=flat-square)](https://packagist.org/packages/mamitech/scout-apm-laravel-extended)

scout-apm-laravel with additional features such as sample rate and add response body to custom context

## Installation

You can install the package via composer:

```bash
composer require mamitech/scout-apm-laravel-extended
```

## Usage

Currently we haven't created proper config file yet. You can set these env parameters to use the features.

### Sample Rate

```bash
SCOUT_SAMPLING_PER=10
```

This will set the tracing sampling rate to 1 in 10 chance (1/10)

### Additional Custom Context

```bash
SCOUT_CUSTOM_CONTEXT_ENABLED=true
```

This will enable additional request body and query params to trace data.

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
