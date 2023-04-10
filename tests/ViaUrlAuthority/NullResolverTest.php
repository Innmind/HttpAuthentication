<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\{
    ViaUrlAuthority\NullResolver,
    ViaUrlAuthority\Resolver,
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
        $this->assertNull((new NullResolver)(
            User::none(),
            Password::none(),
        )->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
