<?php

namespace Gl3n\HttpQueryStringFilterBundle\Annotation;

/**
 * You must place this annotation on a controller method
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class HttpQueryStringFilter
{
    public $name;
}