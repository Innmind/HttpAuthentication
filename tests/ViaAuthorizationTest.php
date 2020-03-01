<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaAuthorization,
    ViaAuthorization\Resolver,
    ViaAuthorization\NullResolver,
    Authenticator,
    Identity,
    Exception\NotSupported,
};
use Innmind\Http\{
    Message\ServerRequest,
    Headers,
    Header\Header,
    Header\Value\Value,
    Header\Authorization,
    Header\AuthorizationValue,
};
use PHPUnit\Framework\TestCase;

class ViaAuthorizationTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaAuthorization(new NullResolver)
        );
    }

    public function testThrowWhenNoAuthorizationHeader()
    {
        $authenticate = new ViaAuthorization(
            $resolver = $this->createMock(Resolver::class)
        );
        $resolver
            ->expects($this->never())
            ->method('__invoke');
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('headers')
            ->willReturn(Headers::of());

        $this->expectException(NotSupported::class);

        $authenticate($request);
    }

    public function testThrowWhenAuthorizationHeaderNotParsedCorrectly()
    {
        $authenticate = new ViaAuthorization(
            $resolver = $this->createMock(Resolver::class)
        );
        $resolver
            ->expects($this->never())
            ->method('__invoke');
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('headers')
            ->willReturn(Headers::of(
                new Header('Authorization', new Value('Basic foo'))
            ));

        $this->expectException(NotSupported::class);

        $authenticate($request);
    }

    public function testInvokation()
    {
        $authenticate = new ViaAuthorization(
            $resolver = $this->createMock(Resolver::class)
        );
        $expected = new AuthorizationValue('Bearer', 'foo');
        $resolver
            ->expects($this->once())
            ->method('__invoke')
            ->with($expected)
            ->willReturn($identity = $this->createMock(Identity::class));
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('headers')
            ->willReturn(Headers::of(
                new Authorization($expected)
            ));

        $this->assertSame($identity, $authenticate($request));
    }
}
