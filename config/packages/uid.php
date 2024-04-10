<?php

declare(strict_types=1);

return function (\Symfony\Config\FrameworkConfig $frameworkConfig): void {
    $uidConfig = $frameworkConfig->uid();

    $uidConfig->defaultUuidVersion(7)
        ->timeBasedUuidVersion(7);
};
