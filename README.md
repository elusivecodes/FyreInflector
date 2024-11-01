# FyreInflector

**FyreInflector** is a free, open-source inflection library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer require fyre/inflector
```

In PHP:

```php
use Fyre\Utility\Inflector;
```


## Basic Usage

```php
$inflector = new Inflector();
```


## Methods

**Camelize**

Convert a delimited string into CamelCase.

- `$string` is the input string.
- `$delimiter` is a string representing the delimiter, and will default to "*_*".

```php
$camelized = $inflector->camelize($string, $delimiter);
```

**Classify**

Convert a table_name to a singular ClassName.

- `$tableName` is a string representing the table name.

```php
$classified = $inflector->classify($tableName);
```

**Dasherize**

Convert a string into kebab-case.

- `$string` is the input string.

```php
$dasherized = $inflector->dasherize($string);
```

**Humanize**

Convert a delimited string into Human Readable Form.

- `$string` is the input string.
- `$delimiter` is a string representing the delimiter, and will default to "*_*".

```php
$humanized = $inflector->humanize($string, $delimiter);
```

**Pluralize**

Get the plural form of a word.

- `$string` is the input string.

```php
$plural = $inflector->pluralize($string);
```

**Rules**

Add inflection rules.

- `$type` is a string representing the inflection type, and must be one of either "*plural*", "*singular*", "*irregular*" or "*uncountable*".
- `$rules` is an array containing the rules to add.

```php
$inflector->rules($type, $rules);
```

**Singularize**

Get the singular form of a word.

- `$string` is the input string.

```php
$singular = $inflector->singularize($string);
```

**Tableize**

Convert a ClassName to a pluralized table_name.

- `$className` is a string representing the class name.

```php
$tableized = $inflector->tableize($className);
```

**Underscore**

Convert a string into snake_case.

- `$string` is the input string.

```php
$underscored = $inflector->underscore($string);
```

**Variable**

Convert a delimited string into camelBack.

- `$string` is the input string.

```php
$variable = $inflector->variable($string);
```