<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'RDCT Frontend redirects',
    'description' => 'Adds redirects based on "&RDCT" parameter in Frontend',
    'category' => 'fe',
    'state' => 'alpha',
    'author' => 'Benni Mack',
    'author_email' => 'benni@typo3.org',
    'author_company' => '',
    'version' => '3.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
