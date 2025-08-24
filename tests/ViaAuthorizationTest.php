<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaAuthorization,
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
use Innmind\Immutable\Attempt;
use PHPUnit\Framework\TestCase;

class ViaAuthorizationTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaAuthorization(static fn() => null),
        );
    }

    public function testReturnNothingWhenNoAuthorizationHeader()
    {
        $authenticate = new ViaAuthorization(
            static fn() => throw new \Exception,
        );
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
            static fn() => throw new \Exception,
        );
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
        $identity = $this->createMock(Identity::class);
        $authenticate = new ViaAuthorization(
            static fn() => Attempt::result($identity),
        );
        $expected = new AuthorizationValue('Bearer', 'foo');
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
