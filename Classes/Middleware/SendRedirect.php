<?php
declare(strict_types=1);
namespace FoT3\Rdct\Middleware;

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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\DateTimeAspect;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Looks up the value of $_GET[RDCT] in the database and if it is
 * found to be associated with a redirect URL then the redirection is triggered.
 */
class SendRedirect implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // No GET parameter set, do nothing
        $redirectHash = $request->getQueryParams()['RDCT'] ?? '';
        if (empty($redirectHash)) {
            return $handler->handle($request);
        }

        $row = $this->fetchRedirectRecord($redirectHash);
        if (is_array($row)) {
            $this->updateMD5paramsRecord($redirectHash);
            return new RedirectResponse($row['params'], 307);
        }
        return $handler->handle($request);
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
                    $queryBuilder->createNamedParameter($redirectHash, Connection::PARAM_STR)
                )
            )
            ->executeQuery()
            ->fetchAssociative();
    }

    /**
     * Updates the tstamp field of a cache_md5params record to the current time.
     *
     * @param string $redirectHash The hash string identifying the cache_md5params record for which to update the "tstamp" field to the current time.
     */
    protected function updateMD5paramsRecord(string $redirectHash): void
    {
        /** @var DateTimeAspect $dateAspect */
        $dateAspect = GeneralUtility::makeInstance(Context::class)->getAspect('date');
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('cache_md5params');
        $connection->update(
            'cache_md5params',
            ['tstamp' => $dateAspect->get('timestamp')],
            ['md5hash' => $redirectHash]
        );
    }

}
