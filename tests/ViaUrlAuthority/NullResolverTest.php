<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\{
    ViaUrlAuthority\NullResolver,
    ViaUrlAuthority\Resolver,
    Exception\AuthenticatorNotImplemented,
};
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use PHPUnit\Framework\TestCase;

class NullResolverTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Resolver::class, new NullResolver);
    }

    public function testInvokation()
    {
        $this->expectException(AuthenticatorNotImplemented::class);

        (new NullResolver)(
            User::none(),
            Password::none(),
        );
    }
}
