<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaForm;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Message\Form;

interface Resolver
{
    public function __invoke(Form $form): Identity;
}
