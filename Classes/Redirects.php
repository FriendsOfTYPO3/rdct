<?php
declare(strict_types=1);

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

namespace FoT3\Rdct;

use FoT3\Rdct\Repository\CacheMd5paramsRepository;
use TYPO3\CMS\Core\Context\Context;
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
    public function makeRedirectUrl(
        string $inUrl,
        int $l = 0,
        string $index_script_url = ''
    ): string
    {
        if (strlen($inUrl) > $l) {
            $md5 = substr(md5($inUrl), 0, 20);
            $cacheMd5paramsRepository = GeneralUtility::makeInstance(CacheMd5paramsRepository::class);
            $count = $cacheMd5paramsRepository->countByMd5hash($md5);

            if (!$count) {
                $cacheMd5paramsRepository->insert(
                    $md5,
                    GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp'),
                    $inUrl
                );
            }
            //@TODO GeneralUtility::getIndpEnv('TYPO3_REQUEST_DIR')
            //https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ApiOverview/RequestLifeCycle/RequestAttributes/NormalizedParams.html?highlight=getindpenv#generalutility-getindpenv-migration
            $inUrl = ($index_script_url ?: GeneralUtility::getIndpEnv('TYPO3_REQUEST_DIR') . 'index.php') . '?RDCT=' . $md5;
        }
        return $inUrl;
    }
}
