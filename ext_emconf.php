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
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
