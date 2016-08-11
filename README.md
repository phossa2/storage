# phossa2/storage
[![Build Status](https://travis-ci.org/phossa2/storage.svg?branch=master)](https://travis-ci.org/phossa2/storage)
[![Code Quality](https://scrutinizer-ci.com/g/phossa2/storage/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phossa2/storage/)
[![PHP 7 ready](http://php7ready.timesplinter.ch/phossa2/storage/master/badge.svg)](https://travis-ci.org/phossa2/storage)
[![HHVM](https://img.shields.io/hhvm/phossa2/storage.svg?style=flat)](http://hhvm.h4cc.de/package/phossa2/storage)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa2/storage.svg?style=flat)](https://packagist.org/packages/phossa2/storage)
[![License](https://poser.pugx.org/phossa2/storage/license)](http://mit-license.org/)

**phossa2/storage** is a PHP storage library with support for local or cloud
storage.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with [PSR-1][PSR-1],
[PSR-2][PSR-2], [PSR-3][PSR-3], [PSR-4][PSR-4], and the proposed [PSR-5][PSR-5].

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-3]: http://www.php-fig.org/psr/psr-3/ "PSR-3: Logger Interface"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"
[PSR-5]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md "PSR-5: PHPDoc"

Installation
---
Install via the `composer` utility.

```bash
composer require "phossa2/storage"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa2/storage": "^2.0.0"
    }
}
```

Introduction
---

- Simple [restful like APIs](#api).

- [Unified path syntax](#unified) like `/local/img/avatar.jpg` for all systems.

- Mounting and umounting [`filesystem`](#filesystem).

- Support for different [drivers](#driver).

- Support for [stream](#stream).

Usage
---

Create the storage instance,

```php
use Phossa2\Storage\Storage;
use Phossa2\Storage\Filesystem;
use Phossa2\Storage\Driver\LocalDriver;

// mount local dir '/www/storage' to '/local'
$storage = new Storage(
    '/local',
    new Filesystem(new LocalDriver('/www/storage'))
);

// add a file
$filename = '/local/newfile.txt';
$storage->put($filename, 'this is the content');

// check existens
if ($storage->has($filename)) {
    // read file content
    $str = $storage->get($filename);

    // delete the file
    $storage->del($filename);
}

// mount another filesystem
$storage->mount('/aws', new Filesystem(new AwsDriver()));
```

Features
---

- <a name="api"></a>**Restful APIs**

  Support for simple and instinctive APIs like `get()`, `put()`, `has()` and
  `del()` etc.

  Others APIs like

  - `meta()`

    Get the meta data of the file

    ```php
    // get the meta data
    if ($storage->has($file)) {
        $meta = $storage->meta($file);
    }

    // update meta data
    $new = ['mtime' => time()];
    $storage->put($file, null, $new);
    ```

  - `copy()` and `move()`

    Copy or move files in or between filesystems

    ```php
    // move to another name
    $storage->move('/local/README.txt', '/local/README.bak.txt');

    // copy into another filesystem's directory
    $storage->copy('/local/README.txt', '/aws/www/');
    ```

- <a name="unified"></a>**Unified path syntax**

  Uses unified path syntax like `/local/dir/file.txt' for all systems including
  windows. The underlying driver is responsible for translating path
  transparently.

  ```php
  $storage = new Storage(
    '/disk/d',
    new Filesystem(new LocalDriver('D:\\\\'))
  );

  $storage->put('/disk/d/temp/newfile.txt', 'this is content');
  ```

- <a name="filesystem"></a>**Mounting and umounting filesystems**

  `filesytem` is a wrapper of different drivers with permissions. User may
  mount a read only filesystem as follows,

  ```php
  // mount as readonly, default is Filesystem::PERM_ALL
  $storage->mount(
      '/readonly',
      new Filesystem(
        new LocalDriver('/home/www/public'),
        Filesystem::PERM_READ
      )
  );

  // will fail
  $storage->put('/readonly/newfile.txt', 'this is the content');
  ```

  Different filesystem may use same drivers,

  ```php
  $driver = new LocalDriver('/home/www/public');

  // writable
  $storage->mount('/public', new Filesystem($driver));

  // readonly
  $storage->mount('/readonly', new Filesystem($driver, Filesystem::PERM_READ));
  ```

  Filesystems may overlapping on top of others,

  ```php
  // mount root
  $storage->mount('/', new Filesystem(...));

  // mount var
  $storage->mount('/var', new Filesystem(...));

  // mount cache
  $storage->mount('/var/cache', new Filesystem(...));
  ```

- <a name="driver"></a>**Drivers**

  Support for different drivers inlucing local or cloud storage.

- <a name="stream"></a>**Streaming**

  Write and read streams as follows,

  ```php
  // read stream
  $stream = $storage->get('/local/thefile.txt', true);

  // write with stream
  $storage->put('/local/anotherfile.txt', $stream);

  // close it
  if (is_resource($stream)) {
      fclose($stream);
  }
  ```

APIs
---

- <a name="storage_api"></a>`\Phossa2\Storage\Storage`

  - `bool has(string $path)`

    check `$path` existens.

  - `null|string|array|resource get(string $path, bool $getAsStream)`

    Get content of the `$path`.

    - If not found or failure, returns `NULL`.

    - If `$path` is a directory, returns an array of the full paths of the
      files under this `$path`.

    - If `$getAsStream` is `true`, returns a stream handler.

  - `bool put(string $path, string|resource|null $content, array $meta = [])`

    Set the content or meta data of the `$path`.

  - `bool del(string $path)`

    Remove the `$path`. If `$path` is a directory, will remove all files under
    this path and the path itself (unless it is a mount point).

  - `bool copy(string $from, string $to)` and `bool copy(string $from, string $to)`

    Copy or move `$from` to `$to`.

  - `array meta(string $path)`

    Get the meta data of `$path`. If not found or error, returns `[]`.

  - `Phossa2\Storage\Path path(string $path)`

    Returns a `Phossa2\Storage\Path` object

- <a name="error_api"></a>Error related

  - `bool hasError()`

    Has error ?

  - `string getError()`

    Get previous error message. If no error, returns `''`.

    ```php
    if (!$storage->copy('/local/from.txt', '/local/to.txt')) {
        $err = $storage->getError();
    }
    ```

  - `string getErrorCode()`

    Get a numeric string of the error code.

Change log
---

Please see [CHANGELOG](CHANGELOG.md) from more information.

Contributing
---

Please see [CONTRIBUTE](CONTRIBUTE.md) for more information.

Dependencies
---

- PHP >= 5.4.0

- phossa2/shared >= 2.0.23

License
---

[MIT License](http://mit-license.org/)
