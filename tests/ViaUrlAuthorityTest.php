<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaUrlAuthority,
    Authenticator,
    ViaUrlAuthority\Resolver,
    ViaUrlAuthority\NullResolver,
    Identity,
};
use Innmind\Url\Url;
use Innmind\Http\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class ViaUrlAuthorityTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaUrlAuthority(new NullResolver),
        );
    }

    public function testReturnNothingWhenNoUserProvidedInTheUrl()
    {
        $authenticate = new ViaUrlAuthority(
            $resolver = $this->createMock(Resolver::class),
        );
        $url = Url::of('https://localhost/');
        $resolver
            ->expects($this->never())
            ->method('__invoke');
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('url')
            ->willReturn($url);

        $this->assertNull($authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testInvokation()
    {
        $authenticate = new ViaUrlAuthority(
            $resolver = $this->createMock(Resolver::class),
        );
        $url = Url::of('https://user:password@localhost/');
        $user = $url->authority()->userInformation()->user();
        $password = $url->authority()->userInformation()->password();
        $resolver
            ->expects($this->once())
            ->method('__invoke')
            ->with($user, $password)
            ->willReturn($identity = $this->createMock(Identity::class));
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('url')
            ->willReturn($url);

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
