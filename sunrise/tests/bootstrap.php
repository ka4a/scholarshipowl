<?php

require_once __DIR__ . '/../app/helpers.php';

$loader = require __DIR__.'/../vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));


