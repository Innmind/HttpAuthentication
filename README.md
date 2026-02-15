# Http authentication

[![Build Status](https://github.com/Innmind/HttpAuthentication/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/Innmind/HttpAuthentication/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/Innmind/HttpAuthentication/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/HttpAuthentication)
[![Type Coverage](https://shepherd.dev/github/Innmind/HttpAuthentication/coverage.svg)](https://shepherd.dev/github/Innmind/HttpAuthentication)

Simple tool to authenticate a request.

## Installation

```sh
composer require innmind/http-authentication
```

## Usage

```php
use Innmind\HttpAuthentication\ViaBasicAuthorization;
use Innmind\Router\{
    Router,
    Component,
    Collect,
    Handle,
};
use Innmind\Http\{
    ServerRequest,
    Response,
};
use Innmind\Immutable\Attempt;

function login(string $user, string $password): Attempt
{
    // find the user
}

$router = Router::of(
    Component::of(ViaBasicAuthorization::of(
        static fn(string $user, string $password) => login($user, $password),
    ))
        ->map(Collect::of('user'))
        ->pipe(Handle::of(
            static fn(ServerRequest $request, mixed $user) => Attempt::result(
                Response::of(
                    // build response
                ),
            ),
        ));
);

$response = $router(/* an instance of Innmind\Http\ServerRequest */)->unwrap();
```
