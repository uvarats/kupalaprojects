<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator, \Symfony\Config\FrameworkConfig $config): void {
    $containerConfigurator->extension('webpack_encore', [
        'output_path' => '%kernel.project_dir%/public/build',
        'script_attributes' => [
            'defer' => true,
        ],
    ]);

    $config->assets()->jsonManifestPath('%kernel.project_dir%/public/build/manifest.json');
};
