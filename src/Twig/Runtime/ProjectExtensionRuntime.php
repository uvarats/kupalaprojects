<?php

namespace App\Twig\Runtime;

use App\Entity\EducationSubGroup;
use App\Entity\Project;
use App\Entity\ProjectSubject;
use Symfony\Component\String\UnicodeString;
use Twig\Extension\RuntimeExtensionInterface;

class ProjectExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function orientedOn(Project $project): string
    {
        $orientedOn = $project->getOrientedOn();
        $orientedOnNames = $orientedOn->map(static function (EducationSubGroup $subGroup): string {
            $name = $subGroup->getName();

            return (new UnicodeString($name))
                ->lower()
                ->toString();
        });

        return implode(', ', $orientedOnNames->toArray());
    }

    public function subjects(Project $project)
    {
        $subjects = $project->getSubjects();

        $subjectNames = $subjects->map(static function (ProjectSubject $subject): string {
            $subjectName = $subject->getName();

            return (new UnicodeString($subjectName))
                ->lower()
                ->toString();
        });

        return implode(', ', $subjectNames->toArray());
    }
}
