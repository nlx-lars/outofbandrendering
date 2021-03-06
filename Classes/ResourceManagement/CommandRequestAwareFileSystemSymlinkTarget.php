<?php
namespace PunktDe\OutOfBandRendering\ResourceManagement;

/*
 * This file is part of the PunktDe.OutOfBandRendering package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandRequestHandler;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\ResourceManagement\Target\FileSystemSymlinkTarget;

class CommandRequestAwareFileSystemSymlinkTarget extends FileSystemSymlinkTarget
{
    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

    protected function detectResourcesBaseUri()
    {
        $requestHandler = $this->bootstrap->getActiveRequestHandler();

        if ($requestHandler instanceof CommandRequestHandler) {
            return '/' . $this->baseUri;
        }

        return parent::detectResourcesBaseUri();
    }
}
