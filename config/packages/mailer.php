<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (FrameworkConfig $config): void {
    $mailer = $config->mailer();

    $mailer->dsn(env('MAILER_DSN'));
    $mailer->envelope()->sender(env('MAILER_DSN'));
    $mailer->header('From', env('MAILER_DSN'));
};