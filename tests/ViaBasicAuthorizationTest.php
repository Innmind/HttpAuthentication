<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaBasicAuthorization;
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
    Headers,
    Header,
    Header\Value,
    Header\Authorization,
};
use Innmind\Url\Url;
use Innmind\Immutable\Attempt;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ViaBasicAuthorizationTest extends TestCase
{
    public function testReturnNothingWhenNoAuthorizationHeader()
    {
        $authenticate = ViaBasicAuthorization::of(
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
        $authenticate = ViaBasicAuthorization::of(
            static fn() => throw new \Exception,
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                Header::of('Authorization', Value::of('Basic foo')),
            ),
        );

        $this->assertNull($authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testReturnNothingWhenNotBasicAuthorization()
    {
        $authenticate = ViaBasicAuthorization::of(
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
        $authenticate = ViaBasicAuthorization::of(
            static fn($user, $password) => Attempt::result([$user, $password]),
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                Authorization::of('Basic', \base64_encode('foo:bar')),
            ),
        );

        $this->assertSame(['foo', 'bar'], $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
