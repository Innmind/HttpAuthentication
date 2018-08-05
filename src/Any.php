<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\Exception\{
    NotSupported,
    AuthenticatorNotImplemented,
    NoAuthenticationProvided,
};
use Innmind\Http\Message\ServerRequest;

final class Any implements Authenticator
{
    private $authenticators;

    public function __construct(Authenticator ...$authenticators)
    {
        $this->authenticators = $authenticators;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        foreach ($this->authenticators as $authenticate) {
            try {
                return $authenticate($request);
            } catch (NotSupported | AuthenticatorNotImplemented $e) {
                // attempt next strategy
            }
        }

        throw new NoAuthenticationProvided;
    }
}
