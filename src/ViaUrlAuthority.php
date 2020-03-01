<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaUrlAuthority\Resolver,
    Exception\NotSupported,
};
use Innmind\Http\Message\ServerRequest;
use Innmind\Url\Authority\UserInformation\{
    NullUser,
    NullPassword,
};

final class ViaUrlAuthority implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        $user = $request->url()->authority()->userInformation()->user();
        $password = $request->url()->authority()->userInformation()->password();

        if ($user instanceof NullUser && $password instanceof NullPassword) {
            throw new NotSupported;
        }

        return ($this->resolve)($user, $password);
    }
}
