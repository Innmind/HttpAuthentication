<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaAuthorization;
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

class ViaAuthorizationTest extends TestCase
{
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
                Header::of('Authorization', Value::of('Basic foo')),
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
            static fn($value) => Attempt::result($value),
        );
        $expected = Authorization::of('Bearer', 'foo');
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                $expected,
            ),
        );

        $this->assertSame($expected, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
