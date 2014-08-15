<?php

namespace Xhprof\GuiBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
        $container = self::$kernel->getContainer();
        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();

        $this->createDatabase($em);
        $this->loadFixtures($em);
    }

    /**
     * initially create the database
     *
     * @param EntityManager $em
     *
     * @return void
     */
    private function createDatabase(EntityManager $em) {
        if (self::$db_created === false) {
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
     * @param EntityManager $em
     *
     * @return void
     */
    private function loadFixtures(EntityManager $em) {
        $path = self::$kernel->locateResource('@XhprofGuiBundle/Tests/Fixtures/');
        $loader = new Loader();
        $loader->loadFromDirectory($path);
        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }

} 