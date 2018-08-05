<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\{
    Identity,
    Exception\AuthenticatorNotImplemented,
};
use Innmind\Http\Message\Form;

final class NullResolver implements Resolver
{
    public function __invoke(Form $form): Identity
    {
        throw new AuthenticatorNotImplemented;
    }
}
