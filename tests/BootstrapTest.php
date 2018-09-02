<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use function Innmind\HttpAuthentication\bootstrap;
use Innmind\HttpAuthentication\{
    Authenticator,
    ValidateAuthorizationHeader,
    Any,
    ViaUrlAuthority,
    ViaBasicAuthorization,
    ViaForm,
    ViaStorage,
};
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $auth = bootstrap();

        $this->assertInternalType('callable', $auth['validate_authorization_header']);
        $this->assertInstanceOf(
            ValidateAuthorizationHeader::class,
            $auth['validate_authorization_header']($this->createMock(Authenticator::class))
        );
        $this->assertInternalType('callable', $auth['any']);
        $this->assertInstanceOf(
            Any::class,
            $auth['any']($this->createMock(Authenticator::class))
        );
        $this->assertInternalType('callable', $auth['via_url_authority']);
        $this->assertInstanceOf(
            ViaUrlAuthority::class,
            $auth['via_url_authority']($this->createMock(ViaUrlAuthority\Resolver::class))
        );
        $this->assertInternalType('callable', $auth['via_basic_authorization']);
        $this->assertInstanceOf(
            ViaBasicAuthorization::class,
            $auth['via_basic_authorization']($this->createMock(ViaBasicAuthorization\Resolver::class))
        );
        $this->assertInternalType('callable', $auth['via_form']);
        $this->assertInstanceOf(
            ViaForm::class,
            $auth['via_form']($this->createMock(ViaForm\Resolver::class))
        );
        $this->assertInternalType('callable', $auth['via_storage']);
        $viaStorage = $auth['via_storage']($this->createMock(ViaStorage\Storage::class));
        $this->assertInternalType('callable', $viaStorage);
        $this->assertInstanceOf(
            ViaStorage::class,
            $viaStorage($this->createMock(Authenticator::class))
        );
    }
}
