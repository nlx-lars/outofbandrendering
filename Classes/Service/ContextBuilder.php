<?php
declare(strict_types=1);

namespace PunktDe\OutOfBandRendering\Service;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\Mvc;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * @Flow\Scope("singleton")
 */
class ContextBuilder
{
    /**
     * @Flow\InjectConfiguration(path="urlSchemaAndHost")
     * @var string
     */
    protected $urlSchemeAndHostFromConfiguration;

    /**
     * @Flow\Inject
     * @var UriFactoryInterface
     */
    protected $uriFactory;

    /**
     * @Flow\Inject
     * @var ServerRequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * @Flow\Inject
     * @var Mvc\ActionRequestFactory
     */
    protected $actionRequestFactory;

    /**
     * @var ControllerContext
     */
    protected $controllerContext;

    public function initializeObject(): void
    {
        // we want to have nice URLs
        putenv('FLOW_REWRITEURLS=1');
    }

    /**
     * @param string $urlSchemaAndHost Can be set if known (eg from the primary domain retrieved from the domain repository)
     * @return ControllerContext
     *
     * @throws Mvc\Exception\InvalidActionNameException
     * @throws Mvc\Exception\InvalidArgumentNameException
     * @throws Mvc\Exception\InvalidArgumentTypeException
     * @throws Mvc\Exception\InvalidControllerNameException
     */
    public function buildControllerContext(string $urlSchemaAndHost = ''): ControllerContext
    {
        if ($urlSchemaAndHost === '') {
            $urlSchemaAndHost = $this->urlSchemeAndHostFromConfiguration;
        }

        if (!($this->controllerContext instanceof ControllerContext)) {
            $httpRequest = $this->requestFactory->createServerRequest('get', $this->uriFactory->createUri($urlSchemaAndHost));
            $this->controllerContext = new ControllerContext(
                $this->actionRequestFactory->createActionRequest($httpRequest),
                new Mvc\ActionResponse(),
                new Mvc\Controller\Arguments(),
                new Mvc\Routing\UriBuilder()
            );
        }

        return $this->controllerContext;
    }
}
