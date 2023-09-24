<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'docker'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER' => true,
    ])
    ->setFinder($finder)
;
