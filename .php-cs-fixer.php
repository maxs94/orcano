<?php 

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
->exclude(['vendor', 'var', 'migrations', 'node_modules'])
->in(__DIR__);
$year = date('Y');
$header = "© 2023-" . $year . " by the orcano team (https://github.com/maxs94/orcano)";

$config = new PhpCsFixer\Config();
$config
->setRules(
    [
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'header_comment' => [
            'header' => $header, 
            'separate' => 'bottom', 
            'location' => 'after_declare_strict', 
            'comment_type' => 'PHPDoc'
        ],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'blank_line_after_opening_tag' => false,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'yoda_style' => false,
        'return_assignment' => false,
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'fully_qualified_strict_types' => false,
        'long_to_shorthand_operator' => true,
        'php_unit_test_class_requires_covers' => true,
        'php_unit_fqcn_annotation' => true,
    ]
)
->setRiskyAllowed(true)
->setFinder($finder)
;

return $config;

