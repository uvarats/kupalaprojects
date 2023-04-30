<?php

namespace App\Twig\Runtime;

use App\Enum\ProjectStateEnum;
use Twig\Extension\RuntimeExtensionInterface;

class ProjectStateExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function makeState(ProjectStateEnum $projectStateEnum)
    {

    }
}
