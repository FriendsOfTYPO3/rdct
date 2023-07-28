<?php
declare(strict_types=1);
namespace FoT3\Rdct;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Creates Redirect entries
 */
class Redirects
{
    /**
     * Create a shortened "redirect" URL with specified length from an incoming URL
     *
     * @param string $inUrl Input URL
     * @param int $l URL string length limit
     * @param string $index_script_url URL of "index script" - the prefix of the "?RDCT=..." parameter. If not supplied it will default to \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_DIR').'index.php'
     * @return string Processed URL
     */
    public function makeRedirectUrl($inUrl, $l = 0, $index_script_url = '')
    {
        if (strlen($inUrl) > $l) {
            $md5 = substr(md5($inUrl), 0, 20);
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('cache_md5params');
            $count = $connection->count(
                '*',
                'cache_md5params',
                ['md5hash' => $md5]
            );
            if (!$count) {
                $context = GeneralUtility::makeInstance(Context::class);
                $connection->insert(
                    'cache_md5params',
                    [
                        'md5hash' => $md5,
                        'tstamp'  => $context->getPropertyFromAspect('date', 'timestamp'),
                        'type'    => 2,
                        'params'  => $inUrl
                    ]
                );
            }
            $inUrl = ($index_script_url ?: GeneralUtility::getIndpEnv('TYPO3_REQUEST_DIR') . 'index.php') . '?RDCT=' . $md5;
        }
        return $inUrl;
    }
}
