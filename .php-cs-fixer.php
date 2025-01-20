<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true, // PSR12 coding standard
        'array_syntax' => ['syntax' => 'short'], // Use [] instead of array()
        'no_unused_imports' => true, // Remove unused imports
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__) // Target the current directory
            ->exclude(['vendor', 'storage', 'bootstrap/cache'])  
    );
