<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
        'no_useless_else' => true,
        'simplified_if_return' => true,
        'blank_line_before_statement' => ['statements' => ['return']],
        'class_attributes_separation' => ['elements' => ['method' => 'one', 'property' => 'one']],
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'use',
                'curly_brace_block',
                'parenthesis_brace_block',
                'square_brace_block',
                'return',
                'throw',
                'continue',
                'break',
                'switch',
                'case',
            ],
        ],
        'single_space_after_construct' => true,
        'binary_operator_spaces' => ['default' => 'single_space'],
        'declare_equal_normalize' => ['space' => 'single'],
        'function_typehint_space' => true,
    ])
    ->setFinder((new PhpCsFixer\Finder())->in([__DIR__ . '/src', __DIR__ . '/test']));
