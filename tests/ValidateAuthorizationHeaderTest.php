<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ValidateAuthorizationHeader,
    Authenticator,
    Identity,
};
use Innmind\Http\{
    Message\ServerRequest,
    Headers,
    Header\Authorization,
    Header\AuthorizationValue,
    Header\Header,
    Header\Value\Value,
};
use Innmind\Immutable\Maybe;
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
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('headers')
            ->willReturn(Headers::of());
        $authenticate
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Maybe::just($expected = $this->createMock(Identity::class)));

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
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('headers')
            ->willReturn(Headers::of(
                new Header(
                    'Authorization',
                    new Value('Bearer foo'),
                ),
            ));
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
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('headers')
            ->willReturn(Headers::of(
                new Authorization(
                    new AuthorizationValue('Bearer', 'foo'),
                ),
            ));
        $authenticate
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Maybe::just($expected = $this->createMock(Identity::class)));

        $this->assertSame($expected, $validate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
