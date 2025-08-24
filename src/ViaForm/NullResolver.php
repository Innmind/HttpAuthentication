<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\ServerRequest\Form;
use Innmind\Immutable\Attempt;

final class NullResolver implements Resolver
{
    public function __invoke(Form $form): Attempt
    {
        /** @var Attempt<Identity> */
        return Attempt::error(new \LogicException('Not implemented'));
    }
}
