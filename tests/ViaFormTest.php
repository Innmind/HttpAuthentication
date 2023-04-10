<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaForm,
    Authenticator,
    ViaForm\Resolver,
    ViaForm\NullResolver,
    Identity,
};
use Innmind\Http\Message\{
    ServerRequest,
    Method,
    Form,
};
use Innmind\Immutable\Maybe;
use PHPUnit\Framework\TestCase;

class ViaFormTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaForm(new NullResolver),
        );
    }

    public function testReturnNothingWhenNotPostRequest()
    {
        $authenticate = new ViaForm(
            $resolver = $this->createMock(Resolver::class),
        );
        $resolver
            ->expects($this->never())
            ->method('__invoke');
        $request = $this->createMock(ServerRequest::class);
        $request
            ->expects($this->once())
            ->method('method')
            ->willReturn(Method::get);

        $this->assertNull($authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testInvokation()
    {
        $authenticate = new ViaForm(
            $resolver = $this->createMock(Resolver::class),
        );
        $request = $this->createMock(ServerRequest::class);
        $form = Form::of([]);
        $request
            ->expects($this->once())
            ->method('method')
            ->willReturn(Method::post);
        $request
            ->expects($this->any())
            ->method('form')
            ->willReturn($form);
        $resolver
            ->expects($this->once())
            ->method('__invoke')
            ->with($form)
            ->willReturn(Maybe::just($identity = $this->createMock(Identity::class)));

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
