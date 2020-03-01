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
            return new class($_SESSION['identity']) implements Identity {
                private $value;

                public function __construct($value)
                {
                    $this->value = $value;
                }

                public function __toString(): string
                {
                    return (string) $this->value;
                }
            };
        }

        $identity = ($this->authenticate)($request);

        $_SESSION['identity'] = (string) $identity;

        return $identity;
    }
}
