<?php
/**
 * Definitions for middlewares provided by EXT:rdct
 */
return [
    'frontend' => [
        'friends-of-typo3/rdct/send-redirect' => [
            'target' => FoT3\Rdct\Middleware\SendRedirect::class,
            'before' => [
                'typo3/cms-frontend/content-length-header'
            ],
            'after' => [
                'typo3/cms-frontend/maintenance-mode'
            ],
        ],
    ],
];
