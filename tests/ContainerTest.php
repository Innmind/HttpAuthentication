<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\Any;
use Innmind\Compose\ContainerBuilder\ContainerBuilder;
use Innmind\Url\Path;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testLoad()
    {
        $container = (new ContainerBuilder)(
            new Path('container.yml'),
            new Map('string', 'mixed')
        );

        $this->assertInstanceOf(Any::class, $container->get('authenticator'));
    }
}
