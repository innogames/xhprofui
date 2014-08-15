<?php

namespace Xhprof\GuiBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

class MyWebTestCase extends WebTestCase
{

    private static $db_created = false;

    /**
     * set up the database
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();

        self::bootKernel(['environment' => 'test', 'debug' => true]);

        $this->createDatabase();
        $this->loadFixtures();
    }

    /**
     * initially create the database
     *
     * @return void
     */
    private function createDatabase() {
        if (self::$db_created === false) {
            $container = self::$kernel->getContainer();
            /** @var EntityManager $em */
            $em = $container->get('doctrine')->getManager();
            $meta = $em->getMetadataFactory()->getAllMetadata();
            $tool = new SchemaTool($em);
            $tool->dropDatabase();
            $tool->createSchema($meta);
            self::$db_created = true;
        }
    }

    /**
     * purge database and load fixtures
     *
     * @return void
     */
    private function loadFixtures() {
        $path = self::$kernel->locateResource('@XhprofGuiBundle/Tests/Fixtures/');
        $loader = new Loader();
        $loader->loadFromDirectory($path);
        $container = self::$kernel->getContainer();
        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }

} 