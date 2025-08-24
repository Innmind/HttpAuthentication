<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ValidateAuthorizationHeader,
    Authenticator,
    Identity,
};
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
    Headers,
    Header\Authorization,
    Header\AuthorizationValue,
    Header\Header,
    Header\Value\Value,
};
use Innmind\Url\Url;
use Innmind\Immutable\Attempt;
use PHPUnit\Framework\TestCase;

class ValidateAuthorizationHeaderTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ValidateAuthorizationHeader(
                $this->createMock(Authenticator::class),
            ),
        );
    }

    public function testForwardAuthenticationWhenNoHeader()
    {
        $validate = new ValidateAuthorizationHeader(
            $authenticate = $this->createMock(Authenticator::class),
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
        );
        $authenticate
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Attempt::result($expected = $this->createMock(Identity::class)));

        $this->assertSame($expected, $validate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testReturnNothingWhenHeaderNotOfExpectedType()
    {
        $validate = new ValidateAuthorizationHeader(
            $authenticate = $this->createMock(Authenticator::class),
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                new Header(
                    'Authorization',
                    new Value('Bearer foo'),
                ),
            ),
        );
        $authenticate
            ->expects($this->never())
            ->method('__invoke');

        $this->assertNull($validate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testForwardAuthenticationWhenValidHeader()
    {
        $validate = new ValidateAuthorizationHeader(
            $authenticate = $this->createMock(Authenticator::class),
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
            Headers::of(
                new Authorization(
                    new AuthorizationValue('Bearer', 'foo'),
                ),
            ),
        );
        $authenticate
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Attempt::result($expected = $this->createMock(Identity::class)));

        $this->assertSame($expected, $validate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
