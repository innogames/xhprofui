<?php

namespace Xhprof\GuiBundle\Model;

class DataParser
{

    const SORT_ASC  = 'ASC';
    const SORT_DESC = 'DESC';

    private $metrics = [
        'ct',
        'wt',
        'cpu',
        'mu',
        'pmu'
    ];

    /**
     * @param $raw_data
     *
     * @return BinaryTree
     */
    public function parse($raw_data)
    {
        $parsed_data = [];
        foreach ($raw_data as $function_name => $row) {
            list($parent, $child) = $this->splitFunctionName($function_name);

            if (!isset($parsed_data[$child])) {
                $parsed_data[$child] = array("ct" => $row["ct"]);
                foreach ($this->metrics as $metric) {
                    $parsed_data[$child][$metric] = $row[$metric];
                }
            } else {
                /* increment call count for this child */
                $parsed_data[$child]["ct"] += $row["ct"];

                /* update inclusive times/metric for this child  */
                foreach ($this->metrics as $metric) {
                    $parsed_data[$child][$metric] += $row[$metric];
                }
            }

        }

        return $this->sortDataByMetric($parsed_data, 'wt', self::SORT_DESC);
    }

    private function splitFunctionName($function)
    {
        if (strpos($function, '==>') === false) {
            return [null, $function];
        }

        return explode("==>", $function);
    }

    /**
     * sort by given metric
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

} 