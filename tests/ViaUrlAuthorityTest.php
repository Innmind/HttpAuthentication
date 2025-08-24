<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaUrlAuthority,
    Identity,
};
use Innmind\Url\Url;
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
};
use Innmind\Immutable\Attempt;
use PHPUnit\Framework\TestCase;

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
        $identity = $this->createMock(Identity::class);
        $authenticate = new ViaUrlAuthority(
            static fn() => Attempt::result($identity),
        );
        $url = Url::of('https://user:password@localhost/');
        $user = $url->authority()->userInformation()->user();
        $password = $url->authority()->userInformation()->password();
        $request = ServerRequest::of(
            $url,
            Method::get,
            ProtocolVersion::v11,
        );

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
