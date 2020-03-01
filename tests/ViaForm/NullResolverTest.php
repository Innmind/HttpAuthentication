<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\{
    ViaForm\NullResolver,
    ViaForm\Resolver,
    Exception\AuthenticatorNotImplemented,
};
use Innmind\Http\Message\Form;
use PHPUnit\Framework\TestCase;

class NullResolverTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Resolver::class, new NullResolver);
    }

    public function testInvokation()
    {
        $this->expectException(AuthenticatorNotImplemented::class);

        (new NullResolver)(new Form);
    }
}
