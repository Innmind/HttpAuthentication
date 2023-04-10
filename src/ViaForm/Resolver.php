<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Message\Form;
use Innmind\Immutable\Maybe;

interface Resolver
{
    /**
     * @return Maybe<Identity>
     */
    public function __invoke(Form $form): Maybe;
}
