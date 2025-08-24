<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\ServerRequest;
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use Innmind\Immutable\Attempt;

/**
 * @template T
 */
final class ViaUrlAuthority
{
    /**
     * @param \Closure(User, Password): Attempt<T> $resolve
     */
    private function __construct(private \Closure $resolve)
    {
    }

    /**
     * @return Attempt<T>
     */
    public function __invoke(ServerRequest $request): Attempt
    {
        $user = $request->url()->authority()->userInformation()->user();
        $password = $request->url()->authority()->userInformation()->password();

        if ($user->equals(User::none()) && $password->equals(Password::none())) {
            /** @var Attempt<T> */
            return Attempt::error(new \RuntimeException('No authentication provided'));
        }

        return ($this->resolve)($user, $password);
    }

    /**
     * @template A
     *
     * @param callable(User, Password): Attempt<A> $resolve
     *
     * @return self<A>
     */
    public static function of(callable $resolve): self
    {
        return new self(\Closure::fromCallable($resolve));
    }
}
