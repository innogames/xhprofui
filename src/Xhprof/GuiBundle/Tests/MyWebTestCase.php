<?php

namespace Xhprof\GuiBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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
            $application = new Application(self::$kernel);
            $application->setAutoExit(false);
            $this->executeCommand($application, "doctrine:schema:drop", ["--force" => '']);
            $this->executeCommand($application, "doctrine:schema:create");
            self::$db_created = true;
        }
    }

    /**
     * executes a normal console command
     *
     * @param Application $application
     * @param string $command the command to execute
     * @param array $options additional options
     */
    private function executeCommand($application, $command, Array $options = array()) {
        $options["--env"] = "test";
        $options["--quiet"] = true;
        $options = array_merge($options, array('command' => $command));

        $application->run(new ArrayInput($options));
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
        $em = $container->get('doctrine')->getManager();
        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }

} 