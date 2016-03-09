# Laravel Messageable

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require draperstudio/laravel-messageable
```

And then include the service provider within `app/config/app.php`.

``` php
'providers' => [
    DraperStudio\Messageable\ServiceProvider::class
];
```

At last you need to publish and run the migration.

```
php artisan vendor:publish --provider="DraperStudio\Messagable\ServiceProvider" && php artisan migrate
```
## Usage

## Setup a Model

``` php
<?php

/*
 * This file is part of Laravel Messageable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use DraperStudio\Messageable\Contracts\Messageable;
use DraperStudio\Messageable\Traits\Messageable as MessageableTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Messageable
{
    use MessageableTrait;
}
```

## Examples

#### Create a new thread
``` php
Thread::create([
    'subject' => str_random(10),
]);
```

#### Add one message to a thread
``` php
$thread->addMessage([
    'body' => str_random(10),
], $user);
```

#### Add multiple messages to a thread
``` php
$thread->addMessage([
    [
        'data' => ['body' => str_random(10)],
        'creator' => User::find(1),
    ],
    [
        'data' => ['body' => str_random(10)],
        'creator' => User::find(2),
    ],
], $user);
```

#### Add one participant to a thread
``` php
$thread->addParticipant($user);
```

#### Add multiple participants to a thread
``` php
$thread->addParticipants([
    User::find(3), Organization::find(2), Player::find(4)
]);
```

#### Mark a thread as ready by the user
``` php
$thread->markAsRead($user);
```

#### Get all threads
``` php
Thread::getAllLatest()->get();
```

#### Get all threads that a user has participated in
``` php
Thread::forModel($user)->latest('updated_at')->get();
```

#### Get all threads that a user has participated in with new messages
``` php
Thread::forModelWithNewMessages($user)->latest('updated_at')->get();
```

#### Get the creator of a thread
``` php
$thread->creator();
```

#### Get the latest message of a thread
``` php
$thread->getLatestMessage();
```

#### Get an array of participant IDs and Types
``` php
$thread->participantsIdsAndTypes();
```

#### Check if the User Model hasn't read the latest message in the thread yet
``` php
$thread->isUnread($user);
```

#### Check if the User Model participated to the Thread
``` php
$thread->hasParticipant($user);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@draperstudio.tech instead of using the issue tracker.

## Credits

- [DraperStudio][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/DraperStudio/laravel-messageable.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/DraperStudio/Laravel-Messageable/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/DraperStudio/laravel-messageable.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/DraperStudio/laravel-messageable.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/DraperStudio/laravel-messageable.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/DraperStudio/laravel-messageable
[link-travis]: https://travis-ci.org/DraperStudio/Laravel-Messageable
[link-scrutinizer]: https://scrutinizer-ci.com/g/DraperStudio/laravel-messageable/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/DraperStudio/laravel-messageable
[link-downloads]: https://packagist.org/packages/DraperStudio/laravel-messageable
[link-author]: https://github.com/DraperStudio
[link-contributors]: ../../contributors
