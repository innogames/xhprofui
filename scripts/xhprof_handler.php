<?php

use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\EntityManager;
use Xhprof\GuiBundle\Services\ProfilingSaveHandler;

require_once 'vendor/autoload.php';

$debug = false;
if (defined('XHPROF_DEBUG')) {
    $debug = XHPROF_DEBUG;
}

$paths = array('src');
$config = Setup::createAnnotationMetadataConfiguration(
    $paths,
    $debug,
    null,
    null,
    false
);

if (!defined('XHPROF_CONFIG')) {
    throw new Exception('You need to specify a constant for the config file XHPROF_CONFIG');
}
if (!file_exists(XHPROF_CONFIG)) {
    throw new RuntimeException('Config file in path ' . XHPROF_CONFIG . ' not found!');
}

$connection_parameters = Yaml::parse(file_get_contents(XHPROF_CONFIG));
if (empty($connection_parameters['xhprof']['database'])) {
    throw new RuntimeException('No valid database connection found, please use the standard structure!');
}

// obtaining the entity manager
$entity_manager = EntityManager::create($connection_parameters['xhprof']['database'], $config);

$handler = new ProfilingSaveHandler();
$handler->setDoctrineEntityManager($entity_manager);
$handler->register();
