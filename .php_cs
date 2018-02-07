<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,
        '@PHP71Migration' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
        'multiline_comment_opening_closing' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'ordered_class_elements' => true,
        'native_function_invocation' => true,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'escape_implicit_backslashes' => true,
        'mb_str_functions' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
