<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'RDCT Frontend redirects',
    'description' => 'Adds redirects based on "&RDCT" parameter in Frontend',
    'category' => 'fe',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'author' => 'Benni Mack',
    'author_email' => 'benni@typo3.org',
    'author_company' => '',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
