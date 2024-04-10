<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'docker']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER' => true,
        'strict_comparison' => true,
        'declare_strict_types' => true,
        'single_quote' => true,
        'phpdoc_align' => true,
        'cast_spaces' => ['space' => 'none'],
        'no_unused_imports' => true,
        'nullable_type_declaration' => ['syntax' => 'question_mark'],
        'nullable_type_declaration_for_default_null_value' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
