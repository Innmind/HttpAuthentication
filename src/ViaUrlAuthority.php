<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaUrlAuthority\Resolver;
use Innmind\Http\ServerRequest;
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use Innmind\Immutable\Attempt;

final class ViaUrlAuthority implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Attempt
    {
        $user = $request->url()->authority()->userInformation()->user();
        $password = $request->url()->authority()->userInformation()->password();

        if ($user->equals(User::none()) && $password->equals(Password::none())) {
            /** @var Attempt<Identity> */
            return Attempt::error(new \RuntimeException('No authentication provided'));
        }

        return ($this->resolve)($user, $password)->attempt(
            static fn() => new \RuntimeException('Failed to resolve identity'),
        );
    }
}
