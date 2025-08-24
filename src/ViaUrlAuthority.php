<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\ServerRequest;
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use Innmind\Immutable\Attempt;

final class ViaUrlAuthority
{
    /** @var callable(User, Password): Attempt<Identity> */
    private $resolve;

    /**
     * @param callable(User, Password): Attempt<Identity> $resolve
     */
    public function __construct(callable $resolve)
    {
        $this->resolve = $resolve;
    }

    /**
     * @return Attempt<Identity>
     */
    public function __invoke(ServerRequest $request): Attempt
    {
        $user = $request->url()->authority()->userInformation()->user();
        $password = $request->url()->authority()->userInformation()->password();

        if ($user->equals(User::none()) && $password->equals(Password::none())) {
            /** @var Attempt<Identity> */
            return Attempt::error(new \RuntimeException('No authentication provided'));
        }

        return ($this->resolve)($user, $password);
    }
}
