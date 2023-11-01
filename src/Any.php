<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\ServerRequest;
use Innmind\Immutable\{
    Sequence,
    Maybe,
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

    public function __invoke(ServerRequest $request): Maybe
    {
        /** @var Maybe<Identity> */
        return $this->authenticators->reduce(
            Maybe::nothing(),
            static fn(Maybe $identity, $authenticate) => $identity->otherwise(
                static fn() => $authenticate($request),
            ),
        );
    }
}
