# FyreInflector

**FyreInflector** is a free, inflection library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer install fyre/inflector
```

In PHP:

```php
use Fyre\Inflector;
```


## Methods

**Inflect**

Inflect a word based on a count.

- `$word` is a string representing word.
- `$count` is a number representing the count.

```php
$inflected = Inflector::inflect($word, $count);
```

**Pluralize**

Get the plural form of a word.

- `$word` is a string representing word.

```php
$plural = Inflector::pluralize($word);
```

**Singularize**

Get the singular form of a word.

- `$word` is a string representing word.

```php
$singular = Inflector::singularize($word);
```