<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaBasicAuthorization,
    ViaBasicAuthorization\Resolver,
    ViaBasicAuthorization\NullResolver,
    Authenticator,
    Identity,
    Exception\NotSupported,
};
use Innmind\Http\{
    Message\ServerRequest,
    Headers\Headers,
    Header\Header,
    Header\Value\Value,
    Header\Authorization,
    Header\AuthorizationValue,
};
use PHPUnit\Framework\TestCase;

class ViaBasicAuthorizationTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaBasicAuthorization(new NullResolver)
        );
    }

    public function testThrowWhenNoAuthorizationHeader()
    {
        $authenticate = new ViaBasicAuthorization(
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
        $authenticate = new ViaBasicAuthorization(
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

    public function testThrowWhenNotBasicAuthorization()
    {
        $authenticate = new ViaBasicAuthorization(
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
                new Authorization(
                    new AuthorizationValue('Bearer', 'foo')
                )
            ));

        $this->expectException(NotSupported::class);

        $authenticate($request);
    }

    public function testInvokation()
    {
        $authenticate = new ViaBasicAuthorization(
            $resolver = $this->createMock(Resolver::class)
        );
        $resolver
            ->expects($this->once())
            ->method('__invoke')
            ->with('foo', 'bar')
            ->willReturn($identity = $this->createMock(Identity::class));
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->any())
            ->method('headers')
            ->willReturn(Headers::of(
                new Authorization(
                    new AuthorizationValue('Basic', base64_encode('foo:bar'))
                )
            ));

        $this->assertSame($identity, $authenticate($request));
    }
}
