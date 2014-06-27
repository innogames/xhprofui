<?php

namespace Xhprof\GuiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profiling
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Xhprof\GuiBundle\Entity\ProfilingRepository")
 */
class Profiling
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $wall_time;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $cpu;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $memory;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $peak_memory;

    /**
     * @var resource
     *
     * @ORM\Column(type="blob")
     */
    private $data;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Profiling
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set wall_time
     *
     * @param integer $wallTime
     * @return Profiling
     */
    public function setWallTime($wallTime)
    {
        $this->wall_time = $wallTime;

        return $this;
    }

    /**
     * Get wall_time
     *
     * @return integer 
     */
    public function getWallTime()
    {
        return $this->wall_time;
    }

    /**
     * Set cpu
     *
     * @param integer $cpu
     * @return Profiling
     */
    public function setCpu($cpu)
    {
        $this->cpu = $cpu;

        return $this;
    }

    /**
     * Get cpu
     *
     * @return integer 
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * Set memory
     *
     * @param integer $memory
     * @return Profiling
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * Get memory
     *
     * @return integer 
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * Set peak_memory
     *
     * @param integer $peakMemory
     * @return Profiling
     */
    public function setPeakMemory($peakMemory)
    {
        $this->peak_memory = $peakMemory;

        return $this;
    }

    /**
     * Get peak_memory
     *
     * @return integer 
     */
    public function getPeakMemory()
    {
        return $this->peak_memory;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return Profiling
     */
    public function setData($data)
    {
        //$this->data = $data;
        $handle = fopen('php://memory', 'w+');
        $gzdata = gzcompress(json_encode($data));
        fputs($handle, $gzdata);
        rewind($handle);
        $this->data = $handle;
        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return json_decode(gzuncompress(stream_get_contents($this->data)), true);
        //return $this->data;
    }
}
