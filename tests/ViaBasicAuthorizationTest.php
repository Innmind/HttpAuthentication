<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaBasicAuthorization,
    ViaBasicAuthorization\Resolver,
    ViaBasicAuthorization\NullResolver,
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
};
use Innmind\Url\Url;
use Innmind\Immutable\Maybe;
use PHPUnit\Framework\TestCase;

class ViaBasicAuthorizationTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaBasicAuthorization(new NullResolver),
        );
    }

    public function testReturnNothingWhenNoAuthorizationHeader()
    {
        $authenticate = new ViaBasicAuthorization(
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
        $authenticate = new ViaBasicAuthorization(
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

    public function testReturnNothingWhenNotBasicAuthorization()
    {
        $authenticate = new ViaBasicAuthorization(
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
                Authorization::of('Bearer', 'foo'),
            ),
        );

        $this->assertNull($authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testInvokation()
    {
        $authenticate = new ViaBasicAuthorization(
            $resolver = $this->createMock(Resolver::class),
        );
        $resolver
            ->expects($this->once())
            ->method('__invoke')
            ->with('foo', 'bar')
            ->willReturn(Maybe::just($identity = $this->createMock(Identity::class)));
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                Authorization::of('Basic', \base64_encode('foo:bar')),
            ),
        );

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
