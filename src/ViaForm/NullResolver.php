<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\ServerRequest\Form;
use Innmind\Immutable\Maybe;

final class NullResolver implements Resolver
{
    public function __invoke(Form $form): Maybe
    {
        /** @var Maybe<Identity> */
        return Maybe::nothing();
    }
}
