<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaForm;
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
};
use Innmind\Url\Url;
use Innmind\Immutable\Attempt;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ViaFormTest extends TestCase
{
    public function testReturnNothingWhenNotPostRequest()
    {
        $authenticate = ViaForm::of(
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
        $authenticate = ViaForm::of(
            static fn($value) => Attempt::result($value),
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::post,
            ProtocolVersion::v11,
        );

        $this->assertSame($request->form(), $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
