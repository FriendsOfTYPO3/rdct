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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Evaluates &RDCT parameter in the frontend
 * this is done through ->sendRedirect()
 * and was previously located in $TSFE->sendRedirect()
 *
 * For creating entries, use the method ->makeRedirectUrl()
 */
class Redirects
{
    /**
     * Looks up the value of $this->RDCT in the database and if it is
     * found to be associated with a redirect URL then the redirection
     * is carried out with a 'Location:' header
     * May exit after sending a location-header.
     */
    public function sendRedirect()
    {
        // TSFE instantiated from BE or CLI context, nothing to do
        if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE)) {
            return;
        }

        $redirectHash = GeneralUtility::_GP('RDCT');
        // No GET parameter set, do nothing
        if (empty($redirectHash)) {
            return;
        }

        // Website is unavailable, do nothing
        if ($GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_force']
            && !GeneralUtility::cmpIP(
                GeneralUtility::getIndpEnv('REMOTE_ADDR'),
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask']
            )
        ) {
            return;
        }

        $row = $this->fetchRedirectRecord($redirectHash);
        if (is_array($row)) {
            $this->updateMD5paramsRecord($redirectHash);
            header('Location: ' . $row['params']);
            die;
        }
    }

    /**
     * Checks cache_md5params if a redirect hash is available
     *
     * @param string $redirectHash
     * @return mixed
     */
    protected function fetchRedirectRecord(string $redirectHash)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('cache_md5params');
        return $queryBuilder
            ->select('params')
            ->from('cache_md5params')
            ->where(
                $queryBuilder->expr()->eq(
                    'md5hash',
                    $queryBuilder->createNamedParameter($redirectHash, \PDO::PARAM_STR)
                )
            )
            ->execute()
            ->fetch();
    }

    /**
     * Updates the tstamp field of a cache_md5params record to the current time.
     *
     * @param string $redirectHash The hash string identifying the cache_md5params record for which to update the "tstamp" field to the current time.
     */
    protected function updateMD5paramsRecord(string $redirectHash)
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('cache_md5params');
        $connection->update(
            'cache_md5params',
            ['tstamp' => $GLOBALS['EXEC_TIME']],
            ['md5hash' => $redirectHash]
        );
    }

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
                $connection->insert(
                    'cache_md5params',
                    [
                        'md5hash' => $md5,
                        'tstamp'  => $GLOBALS['EXEC_TIME'],
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
