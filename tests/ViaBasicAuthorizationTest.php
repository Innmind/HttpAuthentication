<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaBasicAuthorization,
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
use Innmind\Immutable\Attempt;
use PHPUnit\Framework\TestCase;

class ViaBasicAuthorizationTest extends TestCase
{
    public function testReturnNothingWhenNoAuthorizationHeader()
    {
        $authenticate = new ViaBasicAuthorization(
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
        $authenticate = new ViaBasicAuthorization(
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

    public function testReturnNothingWhenNotBasicAuthorization()
    {
        $authenticate = new ViaBasicAuthorization(
            static fn() => throw new \Exception,
        );
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
        $identity = $this->createMock(Identity::class);
        $authenticate = new ViaBasicAuthorization(
            static fn() => Attempt::result($identity),
        );
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
