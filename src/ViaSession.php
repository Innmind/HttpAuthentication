<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\Message\ServerRequest;

final class ViaSession implements Authenticator
{
    private Authenticator $authenticate;

    public function __construct(Authenticator $authenticate)
    {
        $this->authenticate = $authenticate;

        // this class is here as an example on how to store the authenticated
        // identity to achieve stateful authentication
        throw new \LogicException;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        \session_start();

        if (isset($_SESSION['identity'])) {
            /** @psalm-suppress MixedArgument */
            return new class($_SESSION['identity']) implements Identity {
                private string $value;

                public function __construct(string $value)
                {
                    $this->value = $value;
                }

                public function toString(): string
                {
                    return $this->value;
                }
            };
        }

        $identity = ($this->authenticate)($request);

        $_SESSION['identity'] = $identity->toString();

        return $identity;
    }
}
