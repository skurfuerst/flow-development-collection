<?php

require(__DIR__ . '/Doctrine/Common/ClassLoader.php');
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine', __DIR__);
$classLoader->register();

require(__DIR__ . '/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

?>