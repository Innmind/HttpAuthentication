<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaUrlAuthority;
use Innmind\Url\Url;
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
};
use Innmind\Immutable\Attempt;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ViaUrlAuthorityTest extends TestCase
{
    public function testReturnNothingWhenNoUserProvidedInTheUrl()
    {
        $authenticate = new ViaUrlAuthority(
            static fn() => throw new \Exception,
        );
        $url = Url::of('https://localhost/');
        $request = ServerRequest::of(
            $url,
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
        $authenticate = new ViaUrlAuthority(
            static fn($user, $password) => Attempt::result([$user, $password]),
        );
        $url = Url::of('https://user:password@localhost/');
        $user = $url->authority()->userInformation()->user();
        $password = $url->authority()->userInformation()->password();
        $request = ServerRequest::of(
            $url,
            Method::get,
            ProtocolVersion::v11,
        );

        $this->assertSame([$user, $password], $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
