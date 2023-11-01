<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\{
    ViaForm\NullResolver,
    ViaForm\Resolver,
};
use Innmind\Http\ServerRequest\Form;
use PHPUnit\Framework\TestCase;

class NullResolverTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Resolver::class, new NullResolver);
    }

    public function testInvokation()
    {
        $this->assertNull((new NullResolver)(Form::of([]))->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
