<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\ServerRequest\Form;
use Innmind\Immutable\Attempt;

interface Resolver
{
    /**
     * @return Attempt<Identity>
     */
    public function __invoke(Form $form): Attempt;
}
