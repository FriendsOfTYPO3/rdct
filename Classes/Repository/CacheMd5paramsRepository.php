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

namespace FoT3\Rdct\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CacheMd5paramsRepository
{
    protected string $table = 'cache_md5params';

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }

    protected function getConnection(): Connection
    {
        return $this->getConnectionPool()->getConnectionForTable($this->table);
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getConnectionPool()->getQueryBuilderForTable($this->table);
    }

    /**
     * Checks cache_md5params if a redirect hash is available
     *
     * @param string $redirectHash
     * @return array|bool
     */
    public function fetchRedirectRecord(string $redirectHash): array|bool
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder
            ->select('params')
            ->from($this->table)
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
     * @param int $timestamp
     */
    public function updateMD5paramsRecord(string $redirectHash, int $timestamp): void
    {
        $connection = $this->getConnection();
        $connection->update(
            $this->table,
            ['tstamp' => $timestamp],
            ['md5hash' => $redirectHash]
        );
    }

    public function countByMd5hash(string $md5): int
    {
        $connection = $this->getConnection();
        return $connection->count(
            '*',
            $this->table,
            ['md5hash' => $md5]
        );
    }

    public function insert(string $md5, int $timestamp, string $inUrl, int $type = 2): void
    {
        $connection = $this->getConnection();
        $connection->insert(
            $this->table,
            [
                'md5hash' => $md5,
                'tstamp'  => $timestamp,
                'type'    => $type,
                'params'  => $inUrl
            ]
        );
    }
}
