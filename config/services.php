<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $parameters = $container->parameters();

    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', '../src/')
        ->exclude([
            '../src/DependencyInjection/',
            '../src/Entity/',
            '../src/Kernel.php',
        ]);

    $services->set(\App\Service\Mail\ProjectMailerService::class)
        ->arg('$mailFrom', 'org@kp.grsu.by');

    $services->set(\App\Service\Project\ProjectFilterService::class)
        ->arg('$finder', service('fos_elastica.finder.project'));

    $services->alias(
        \App\Repository\Interface\FestivalRepositoryInterface::class,
        \App\Repository\FestivalRepository::class
    );

    $services->alias(
        \App\Service\User\UserResolverInterface::class,
        \App\Service\User\UserService::class
    );

    $services->set(\App\EventListener\ProjectUpdateListener::class)
        ->tag('doctrine.event_listener', [
            'event' => \Doctrine\ORM\Events::onFlush,
        ]);
};
