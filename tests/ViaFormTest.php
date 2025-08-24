<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaForm,
    Identity,
};
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
};
use Innmind\Url\Url;
use Innmind\Immutable\Attempt;
use PHPUnit\Framework\TestCase;

class ViaFormTest extends TestCase
{
    public function testReturnNothingWhenNotPostRequest()
    {
        $authenticate = new ViaForm(
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

    public function testInvokation()
    {
        $identity = $this->createMock(Identity::class);
        $authenticate = new ViaForm(
            static fn() => Attempt::result($identity),
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::post,
            ProtocolVersion::v11,
        );

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
