<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaUrlAuthority\Resolver;
use Innmind\Http\Message\ServerRequest;

final class ViaUrlAuthority implements Authenticator
{
    private $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        return ($this->resolve)(
            $request->url()->authority()->userInformation()->user(),
            $request->url()->authority()->userInformation()->password()
        );
    }
}
