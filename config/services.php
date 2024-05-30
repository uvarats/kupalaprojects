<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

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

    $services->set(\App\Feature\Project\Service\ProjectMailerService::class)
        ->arg('$mailFrom', 'org@kp.grsu.by');

    $services->set(\App\Service\Project\ProjectSearchService::class)
        ->arg('$finder', service('fos_elastica.finder.project'));

    $services->alias(
        \App\Repository\Interface\FestivalRepositoryInterface::class,
        \App\Feature\Festival\Repository\FestivalRepository::class
    );

    $services->alias(
        \App\Feature\Project\Interface\ProjectRepositoryInterface::class,
        \App\Feature\Project\Repository\ProjectRepository::class
    );

    $services->alias(
        \App\Service\User\UserResolverInterface::class,
        \App\Service\User\UserService::class
    );

    $services->set(\App\EventListener\ProjectUpdateListener::class)
        ->tag('doctrine.event_listener', [
            'event' => \Doctrine\ORM\Events::onFlush,
        ]);

    $services->set('invite.service', \App\Feature\Team\Service\TeamInviteService::class);
    $services->alias(\App\Feature\Team\Interface\TeamInviteServiceInterface::class, 'invite.service');

    $services->set('invites.mailer', \App\Feature\Team\Service\InviteMailerService::class);
    $services->alias(\App\Feature\Team\Service\InviteMailerService::class, 'invites.mailer');

    $services->set(\App\Feature\Import\Service\ParticipantImporter::class)
        ->tag('import.handler', ['key' => \App\Feature\Import\Enum\ImportTypeEnum::PARTICIPANT->value]);

    $services->set('import.service', \App\Feature\Import\Service\Importer::class)
        ->args([tagged_locator('import.handler', indexAttribute: 'key')]);
    $services->alias(\App\Feature\Import\Interface\ImportServiceInterface::class, 'import.service');
};
