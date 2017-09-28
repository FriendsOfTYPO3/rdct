<?php
defined('TYPO3_MODE') or die();

// Add hook to check for the RDCT parameter
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['tslib_fe-PostProc']['rdct'] = \FoT3\Rdct\Redirects::class . '->sendRedirect';
