<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('config')
    ->exclude('var')
    ->exclude('docker')
    ->exclude('public/build')
    ->exclude('Migrations');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'no_php4_constructor' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'no_superfluous_elseif' => true
    ])
    ->setFinder($finder);
