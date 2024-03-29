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

namespace FoT3\Rdct\Middleware;

use FoT3\Rdct\Repository\CacheMd5paramsRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\DateTimeAspect;
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
        $cacheMd5paramsRepository = GeneralUtility::makeInstance(CacheMd5paramsRepository::class);
        $row = $cacheMd5paramsRepository->fetchRedirectRecord($redirectHash);
        if (is_array($row)) {
            /** @var DateTimeAspect $dateAspect */
            $dateAspect = GeneralUtility::makeInstance(Context::class)->getAspect('date');
            $cacheMd5paramsRepository->updateMD5paramsRecord($redirectHash, $dateAspect->get('timestamp'));
            return new RedirectResponse($row['params'], 307);
        }
        return $handler->handle($request);
    }
}
