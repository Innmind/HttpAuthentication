<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\ServerRequest;
use Innmind\Immutable\{
    Sequence,
    Attempt,
};

final class Any implements Authenticator
{
    /** @var Sequence<Authenticator> */
    private Sequence $authenticators;

    /**
     * @no-named-arguments
     */
    public function __construct(Authenticator ...$authenticators)
    {
        $this->authenticators = Sequence::of(...$authenticators);
    }

    public function __invoke(ServerRequest $request): Attempt
    {
        /** @var Attempt<Identity> */
        return $this->authenticators->reduce(
            Attempt::error(new \LogicException('No authenticator defined')),
            static fn(Attempt $identity, $authenticate) => $identity->recover(
                static fn() => $authenticate($request),
            ),
        );
    }
}
