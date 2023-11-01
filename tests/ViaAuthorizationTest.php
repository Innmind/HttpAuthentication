<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaAuthorization,
    ViaAuthorization\Resolver,
    ViaAuthorization\NullResolver,
    Authenticator,
    Identity,
};
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
    Headers,
    Header\Header,
    Header\Value\Value,
    Header\Authorization,
    Header\AuthorizationValue,
};
use Innmind\Url\Url;
use Innmind\Immutable\Maybe;
use PHPUnit\Framework\TestCase;

class ViaAuthorizationTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaAuthorization(new NullResolver),
        );
    }

    public function testReturnNothingWhenNoAuthorizationHeader()
    {
        $authenticate = new ViaAuthorization(
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

    public function testReturnNothingWhenAuthorizationHeaderNotParsedCorrectly()
    {
        $authenticate = new ViaAuthorization(
            $resolver = $this->createMock(Resolver::class),
        );
        $resolver
            ->expects($this->never())
            ->method('__invoke');
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                new Header('Authorization', new Value('Basic foo')),
            ),
        );

        $this->assertNull($authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testInvokation()
    {
        $authenticate = new ViaAuthorization(
            $resolver = $this->createMock(Resolver::class),
        );
        $expected = new AuthorizationValue('Bearer', 'foo');
        $resolver
            ->expects($this->once())
            ->method('__invoke')
            ->with($expected)
            ->willReturn(Maybe::just($identity = $this->createMock(Identity::class)));
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                new Authorization($expected),
            ),
        );

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
