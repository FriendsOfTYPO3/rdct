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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;


class RedirectViewHelper extends AbstractViewHelper
{
    /**
     * @var  \FoT3\Rdct\Redirects
     * @inject
     */
    protected $rdct;


    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('inUrl', 'string', 'Input URL', true);
        $this->registerArgument('length', 'integer', 'URL string length limit', false, 0);
        $this->registerArgument('indexScriptUrl', 'string', 'URL of "index script" - the prefix of the "?RDCT=..." parameter. If not supplied it will default to \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv(TYPO3_REQUEST_DIR).index.php', false, '');
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->rdct->makeRedirectUrl($this->arguments['inUrl'], $this->arguments['length'], $this->arguments['indexScriptUrl']);
    }
}
