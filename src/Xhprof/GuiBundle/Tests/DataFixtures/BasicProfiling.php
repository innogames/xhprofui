<?php

namespace Xhprof\GuiBundle\Tests\DataFixtures;

use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Xhprof\GuiBundle\Entity\Profiling;

class BasicProfiling implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $profiling = new Profiling();
        $profiling->setTimestamp(new DateTime());
        $profiling->setCookiesParams([]);
        $profiling->setWallTime(1111);
        $profiling->setCpu(1234);
        $profiling->setMemory(4321);
        $profiling->setRequestUri('/my/test/uri');
        $profiling->setPeakMemory(9999);
        $profiling->setServerName('localhost');
        $profiling->setRequestMethod('GET');
        $profiling->setData('');

        $manager->persist($profiling);
        $manager->flush();
    }
}
