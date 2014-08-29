<?php

namespace Xhprof\GuiBundle\Model;

use \InvalidArgumentException;

class DataParser
{

    const SORT_ASC  = 'ASC';
    const SORT_DESC = 'DESC';

    const METRIC_COUNT = 'ct';
    const METRIC_WALL_TIME = 'wt';
    const METRIC_CPU = 'cpu';
    const METRIC_MEMORY_USAGE = 'mu';
    const METRIC_PEAK_MEMORY_USAGE = 'pmu';

    const METRIC_EXCL_WALL_TIME = 'excl_wt';
    const METRIC_EXCL_CPU = 'excl_cpu';
    const METRIC_EXCL_MEMORY_USAGE = 'excl_mu';
    const METRIC_EXCL_PEAK_MEMORY_USAGE = 'excl_pmu';

    /**
     * all available metric keys
     *
     * @var array
     */
    private $metrics = [
        self::METRIC_COUNT,
        self::METRIC_WALL_TIME,
        self::METRIC_CPU,
        self::METRIC_MEMORY_USAGE,
        self::METRIC_PEAK_MEMORY_USAGE
    ];

    /**
     * map of inclusive metrics to exclusive ones
     * @var array
     */
    private $exclusive_metrics = [
        self::METRIC_WALL_TIME         => self::METRIC_EXCL_WALL_TIME,
        self::METRIC_CPU               => self::METRIC_EXCL_CPU,
        self::METRIC_MEMORY_USAGE      => self::METRIC_EXCL_MEMORY_USAGE,
        self::METRIC_PEAK_MEMORY_USAGE => self::METRIC_EXCL_PEAK_MEMORY_USAGE
    ];

    private $parsed_data = array();

    /**
     * parse and returns the data sorted by the given metric and direction
     *
     * @param array  $raw_data
     * @param string $sort_by_metric
     * @param string $sort_direction
     *
     * @throws \InvalidArgumentException
     * @return array
     */
    public function parse(array $raw_data, $sort_by_metric = null, $sort_direction = null)
    {
        if ($sort_by_metric === null) {
            $sort_by_metric = self::METRIC_WALL_TIME;
        }
        if ($sort_direction === null) {
            $sort_direction = self::SORT_DESC;
        }
        $sort_by_metric = strtolower($sort_by_metric);
        $sort_direction = strtoupper($sort_direction);

        if (!in_array($sort_by_metric, $this->metrics)) {
            throw new InvalidArgumentException('unknown metric for sorting given!');
        }
        if ($sort_direction != self::SORT_DESC && $sort_direction != self::SORT_ASC) {
            throw new InvalidArgumentException('unknown sorting direction given!');
        }

        $parsed_data = $this->prepareData($raw_data);
        $parsed_data = $this->parseInclusiveMetrics($raw_data, $parsed_data);
        $parsed_data = $this->parseExclusiveMetrics($raw_data, $parsed_data);

        return $this->sortDataByMetric($parsed_data, $sort_by_metric, $sort_direction);
    }

    /**
     * parse only a part ot show a specific method and it's parents and children
     *
     * @param array  $raw_data
     * @param string $id
     * @param string $sort_by_metric
     * @param string $sort_direction
     *
     * @return array
     */
    public function parsePartial($raw_data, $id, $sort_by_metric = null, $sort_direction = null)
    {
        $parsed_data = $this->parse($raw_data, $sort_by_metric, $sort_direction);
        // search the current data with the id
        $current = $this->findById($id, $parsed_data);

        $result = [
            'current' => $current,
            'parents' => array(),
            'children' => array()
        ];

        // get the name of the current function from the data
        $keys = array_keys($current);
        $key = array_pop($keys);

        foreach ($raw_data as $function_name => $row) {
            list($parent, $child) = $this->splitFunctionName($function_name);
            if ($parent == $key && isset($parsed_data[$child])) {
                $result['children'][$child] = $parsed_data[$child];
            } elseif ($child == $key && isset($parsed_data[$parent])) {
                $result['parents'][$parent] = $parsed_data[$parent];
            }
        }
        return $result;
    }

    /**
     * get the cleartext name for given id
     *
     * @param integer $id
     * @param array $parsed_data
     *
     * @return string
     */
    public static function findNameById($id, array $parsed_data)
    {
        foreach ($parsed_data as $key => $data) {
            if ($data['id'] == $id) {
                return $key;
            }
        }

        return '';
    }

    /**
     * hash the function name
     *
     * @param string $function_name
     *
     * @return integer
     */
    public static function hashFunctionName($function_name)
    {
        return hash('crc32', $function_name);
    }

    /**
     * splits the function name into parent and child parts
     *
     * @param string $function
     *
     * @return array
     */
    private function splitFunctionName($function)
    {
        if (strpos($function, '==>') === false) {
            return [null, $function];
        }

        return explode("==>", $function);
    }

    /**
     * sort by given metric and direction
     *
     * @param array  $data
     * @param string $metric
     * @param string $direction sort direction (ASC/DESC), defaults to ASC
     *
     * @return array
     */
    private function sortDataByMetric($data, $metric, $direction = self::SORT_ASC)
    {
        uasort(
            $data,
            function ($a, $b) use ($metric, $direction) {
                if ($a[$metric] == $b[$metric]) {
                    return 0;
                }

                if ($direction == self::SORT_DESC) {
                    return ($a[$metric] < $b[$metric]) ? 1 : -1;
                } else {
                    return ($a[$metric] < $b[$metric]) ? -1 : 1;
                }
            }
        );

        return $data;
    }

    /**
     * prepare data
     *
     * @param array $raw_data
     *
     * @return array
     */
    private function prepareData(array $raw_data)
    {
        $parsed_data = [];
        foreach ($raw_data as $function_name => $row) {
            list($parent, $child) = $this->splitFunctionName($function_name);
            if (!isset($parsed_data[$child])) {
                $parsed_data[$child] = [];
                $parsed_data[$child]['id'] = self::hashFunctionName($child);
            }
        }
        return $parsed_data;
    }

    /**
     * parse and calculate the inclusive metrics
     *
     * @param array $raw_data
     * @param array $parsed_data
     *
     * @return array
     */
    private function parseInclusiveMetrics(array $raw_data, array $parsed_data = array())
    {
        foreach ($raw_data as $function_name => $row) {
            list($parent, $child) = $this->splitFunctionName($function_name);

            if (!isset($parsed_data[$child]) || !isset($parsed_data[$child][self::METRIC_COUNT])) {
                $parsed_data[$child][self::METRIC_COUNT] = $row[self::METRIC_COUNT];
                foreach ($this->metrics as $metric) {
                    $parsed_data[$child][$metric] = $row[$metric];
                }
            } else {
                // increment call count for this child
                $parsed_data[$child][self::METRIC_COUNT] += $row[self::METRIC_COUNT];

                // update inclusive times/metric for this child
                foreach ($this->metrics as $metric) {
                    $parsed_data[$child][$metric] += $row[$metric];
                }
            }
        }

        return $parsed_data;
    }

    /**
     * calculate the exclusive metrics
     *
     * @param array $raw_data
     * @param array $parsed_data
     *
     * @return mixed
     */
    private function parseExclusiveMetrics(array $raw_data, array $parsed_data)
    {
        // first set all exclusive metrics to the inclusive as default
        foreach ($parsed_data as $child => $data) {
            foreach ($this->exclusive_metrics as $incl_metric => $excl_metric) {
                $parsed_data[$child][$excl_metric] = $data[$incl_metric];
            }
        }

        foreach ($raw_data as $function_name => $row) {
            list($parent, $child) = $this->splitFunctionName($function_name);

            // reduce the metrics from the parent
            foreach ($this->exclusive_metrics as $incl_metric => $excl_metric) {
                if (isset($parsed_data[$parent])) {
                    $parsed_data[$parent][$excl_metric] -= $row[$incl_metric];
                }
            }

        }

        return $parsed_data;
    }

    /**
     * find data by id
     *
     * @param string $id the hash id
     * @param array $parsed_data the data to search in
     *
     * @return array
     */
    private function findById($id, array $parsed_data)
    {
        foreach ($parsed_data as $key => $data) {
            if ($data['id'] == $id) {
                return [$key => $data];
            }
        }

        return [];
    }
}
