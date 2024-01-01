<?php

declare(strict_types=1);

use App\Entity\Project;
use App\Enum\ProjectStateEnum;
use App\Enum\ProjectTransitionEnum;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    // start of project state workflow
    $projectWorkflow = $framework->workflows()->workflows('project');

    $projectWorkflow->type('state_machine')
        ->supports([Project::class])
        ->initialMarking([ProjectStateEnum::UNDER_MODERATION->value]);

    $projectWorkflow->auditTrail()->enabled(true);
    $projectWorkflow->markingStore()
        ->type('method')
        ->property('state');

    $projectWorkflow->place()->name(ProjectStateEnum::UNDER_MODERATION->value);
    $projectWorkflow->place()->name(ProjectStateEnum::APPROVED->value);
    $projectWorkflow->place()->name(ProjectStateEnum::REJECTED->value);
    $projectWorkflow->place()->name(ProjectStateEnum::ENDED->value);

    $projectWorkflow->transition()
        ->name(ProjectTransitionEnum::APPROVE->value)
        ->from([ProjectStateEnum::UNDER_MODERATION->value])
        ->to([ProjectStateEnum::APPROVED->value]);

    $projectWorkflow->transition()
        ->name(ProjectTransitionEnum::REJECT->value)
        ->from([ProjectStateEnum::UNDER_MODERATION->value])
        ->to([ProjectStateEnum::REJECTED->value]);

    $projectWorkflow->transition()
        ->name(ProjectTransitionEnum::CANCEL_REJECTION->value)
        ->from([ProjectStateEnum::REJECTED->value])
        ->to([ProjectStateEnum::UNDER_MODERATION->value]);

    $projectWorkflow->transition()
        ->name(ProjectTransitionEnum::CANCEL_APPROVAL->value)
        ->from([ProjectStateEnum::APPROVED->value])
        ->to([ProjectStateEnum::UNDER_MODERATION->value]);

    $projectWorkflow->transition()
        ->name(ProjectTransitionEnum::END->value)
        ->from([ProjectStateEnum::APPROVED->value])
        ->to([ProjectStateEnum::ENDED->value]);

    // end of project state workflow

    // start of voting state workflow

    // end of voting state workflow
};
