<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaUrlAuthority\Resolver;
use Innmind\Http\Message\ServerRequest;
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use Innmind\Immutable\Maybe;

final class ViaUrlAuthority implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Maybe
    {
        $user = $request->url()->authority()->userInformation()->user();
        $password = $request->url()->authority()->userInformation()->password();

        if ($user->equals(User::none()) && $password->equals(Password::none())) {
            /** @var Maybe<Identity> */
            return Maybe::nothing();
        }

        return Maybe::just(($this->resolve)($user, $password));
    }
}
