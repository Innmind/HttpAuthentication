# Http authentication

[![Build Status](https://github.com/Innmind/HttpAuthentication/workflows/CI/badge.svg)](https://github.com/Innmind/HttpAuthentication/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/Innmind/HttpAuthentication/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/HttpAuthentication)
[![Type Coverage](https://shepherd.dev/github/Innmind/HttpAuthentication/coverage.svg)](https://shepherd.dev/github/Innmind/HttpAuthentication)

Simple tool to authenticate a request.

The library relies on 2 principles:

* an identity, it's an object that represents the entity (user, app, etc) that tries to login
* an authenticator, it will try a strategy to extract informations (login/password, token, etc) out of a request to then resolve it to an identity

The goal here is to have something very simple that do not require your domain logix to implement or extend anything from this library. This is done by having the [`Identity`](src/Identity.php) interface, your domain entity should already use an interface to represent its identity so you'll only need to implement the interface from this library in the class that will lie in your app.

## Installation

```sh
composer require innmind/http-authentication
```

## Usage

```php
use function Innmind\HttpAuthentication\bootstrap;
use Innmind\HttpAuthentication\{
    Identity,
    ViaBasicAuthorization\Resolver as BasicResolver,
    ViaForm\Resolver as FormResolver,
};

$auth = bootstrap();
$viaBasicAuthorization = $auth['via_basic_authorization'](
    new class implements BasicResolver {
        public function __invoke(string $user, string $password): Identity
        {
            // this info comes from the Authorization header

            // your logic here to authenticate the user
        }
    }
);
$viaForm = $auth['via_form'](
    new class implements FormResolver {
        public function __invoke(Form $form): Identity
        {
            // your logic here to authenticate the user by inspecting
            // the form, you have access to the whole form data so the
            // library doesn't force you to have specific fields
        }
    }
);
$authenticate = $auth['any'](
    $viaBasicAuthorization,
    $viaForm
);

$identity = $authenticate(/* an instance of Innmind\Http\Message\ServerRequest */);
```

The three resolvers are all optionals so you can choose which one to use. Because all authenticators implement the same [interface](src/Authenticator.php) you can easily decorate the authenticator exposed from the container to add your own logic such as persisting the identity in a session (stateless by default); you can find a very dumb example [here](src/ViaSession.php) of such an implementation.
