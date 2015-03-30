<?php

namespace Gl3n\HttpQueryStringFilterBundle\EventListener;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Common\Annotations\Reader;
use Gl3n\HttpQueryStringFilterBundle\Filter;

/**
 * Filters query on "kernel.controller" event
 */
class HttpQueryStringFilterListener
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * Constructor
     *
     * @param Reader       $reader
     * @param RequestStack $requestStack
     * @param Filter       $filter
     */
    public function __construct(Reader $reader, RequestStack $requestStack, Filter $filter)
    {
        $this->reader = $reader;
        $this->requestStack = $requestStack;
        $this->filter = $filter;
    }

    /**
     * Replaces query parameter bag by the filtered one
     *
     * @param FilterControllerEvent $event
     *
     * @throws BadRequestHttpException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $this->requestStack->getMasterRequest();

        $controller = $event->getController();
        $method = new \ReflectionMethod($controller[0], $controller[1]);

        $annotation = $this->reader->getMethodAnnotation($method, 'Gl3n\HttpQueryStringFilterBundle\Annotation\HttpQueryStringFilter');

        if ($annotation) {
            try {
                $request->query = $this->filter->filter($annotation->name, $request->query);
            } catch (\InvalidArgumentException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
    }
}
