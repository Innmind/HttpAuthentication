<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaForm,
    Authenticator,
    ViaForm\Resolver,
    ViaForm\NullResolver,
    Identity,
};
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
};
use Innmind\Url\Url;
use Innmind\Immutable\Maybe;
use PHPUnit\Framework\TestCase;

class ViaFormTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaForm(new NullResolver),
        );
    }

    public function testReturnNothingWhenNotPostRequest()
    {
        $authenticate = new ViaForm(
            $resolver = $this->createMock(Resolver::class),
        );
        $resolver
            ->expects($this->never())
            ->method('__invoke');
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
        );

        $this->assertNull($authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testInvokation()
    {
        $authenticate = new ViaForm(
            $resolver = $this->createMock(Resolver::class),
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::post,
            ProtocolVersion::v11,
        );
        $resolver
            ->expects($this->once())
            ->method('__invoke')
            ->with($request->form())
            ->willReturn(Maybe::just($identity = $this->createMock(Identity::class)));

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
