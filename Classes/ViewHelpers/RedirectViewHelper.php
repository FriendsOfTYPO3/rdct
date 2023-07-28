<?php
namespace FoT3\Rdct\ViewHelpers;

/*
 * This file is part of the FoT3\Rdct package.
 * @author el_equipo@punkt.de
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use FoT3\Rdct\Redirects;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;


class RedirectViewHelper extends AbstractViewHelper
{

    /**
     * @var Redirects
     */
    protected $rdct;

    /**
     * @param Redirects $rdct
     */
    public function injectRdct(Redirects $rdct): void
    {
        $this->rdct = $rdct;
    }

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('inUrl', 'string', 'Input URL', true);
        $this->registerArgument('length', 'integer', 'URL string length limit', false, 0);
        $this->registerArgument('indexScriptUrl', 'string', 'URL of "index script" - the prefix of the "?RDCT=..." parameter. If not supplied it will default to \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv(TYPO3_REQUEST_DIR).index.php', false, '');
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->rdct->makeRedirectUrl($this->arguments['inUrl'], $this->arguments['length'], $this->arguments['indexScriptUrl']);
    }
}
