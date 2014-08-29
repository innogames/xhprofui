<?php

namespace Xhprof\GuiBundle\Tests\Model;

use Xhprof\GuiBundle\Model\DataParser;

class DataParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * parse data
     *
     * @return void
     */
    public function testParsingCompleteSet()
    {
        $parser = new DataParser();
        $result = $parser->parse($this->getTestData());
        $this->assertInternalType('array', $result);
        $this->assertEquals($this->getExpectedData(), $result);
    }

    /**
     * test partial
     *
     * @return void
     */
    public function testParsingPartial()
    {
        $parser = new DataParser();
        $result = $parser->parsePartial($this->getTestData(), DataParser::hashFunctionName('foo'));
        $this->assertInternalType('array', $result);

        $this->assertArrayHasKey('children', $result);
        $this->assertArrayHasKey('parents', $result);
        $this->assertArrayHasKey('current', $result);

        $expected_data = $this->getExpectedData();

        $current = $result['current'];
        $this->assertArrayHasKeyHasValue('foo', $expected_data['foo'], $current);

        $children = $result['children'];
        $this->assertArrayHasKeyHasValue('bar', $expected_data['bar'], $children);
        $this->assertArrayHasKeyHasValue('strlen', $expected_data['strlen'], $children);

        $parents = $result['parents'];
        $this->assertArrayHasKeyHasValue('main()', $expected_data['main()'], $parents);
    }

    /**
     * get sample test data, used the sample from the xhprof package
     *
     * @return array
     */
    private function getTestData()
    {
        return array(
            'foo==>bar' => array(
                'ct' => 5,
                'wt' => 257,
                'cpu' => 409,
                'mu' => 4992,
                'pmu' => 0
            ),
            'foo==>strlen' => array(
                'ct' => 5,
                'wt' => 20,
                'cpu' => 25,
                'mu' => 752,
                'pmu' => 0
            ),
            'bar==>bar@1' => array(
                'ct' => 4,
                'wt' => 58,
                'cpu' => 62,
                'mu' => 3872,
                'pmu' => 0
            ),
            'bar@1==>bar@2' => array(
                'ct' => 3,
                'wt' => 22,
                'cpu' => 25,
                'mu' => 2832,
                'pmu' => 0
            ),
            'bar@2==>bar@3' => array(
                'ct' => 2,
                'wt' => 10,
                'cpu' => 13,
                'mu' => 1792,
                'pmu' => 0
            ),
            'bar@3==>bar@4' => array(
                'ct' => 1,
                'wt' => 2,
                'cpu' => 4,
                'mu' => 752,
                'pmu' => 0
            ),
            'main()==>foo' => array(
                'ct' => 1,
                'wt' => 544,
                'cpu' => 546,
                'mu' => 7104,
                'pmu' => 0
            ),
            'main()==>xhprof_disable' => array(
                'ct' => 1,
                'wt' => 1,
                'cpu' => 2,
                'mu' => 760,
                'pmu' => 0
            ),
            'main()' => array(
                'ct' => 1,
                'wt' => 811,
                'cpu' => 809,
                'mu' => 9256,
                'pmu' => 0
            )
        );
    }

    /**
     * get the data which is expected
     *
     * @return array
     */
    private function getExpectedData()
    {
        return array(
            'main()' => array(
                'id' => DataParser::hashFunctionName('main()'),
                'ct' => 1,
                'wt' => 811,
                'cpu' => 809,
                'mu' => 9256,
                'pmu' => 0,
                'excl_wt' => 266,
                'excl_cpu' => 261,
                'excl_mu' => 1392,
                'excl_pmu' => 0
            ),
            'foo' => array(
                'id' => DataParser::hashFunctionName('foo'),
                'ct' => 1,
                'wt' => 544,
                'cpu' => 546,
                'mu' => 7104,
                'pmu' => 0,
                'excl_wt' => 267,
                'excl_cpu' => 112,
                'excl_mu' => 1360,
                'excl_pmu' => 0
            ),
            'bar' => array(
                'id' => DataParser::hashFunctionName('bar'),
                'ct' => 5,
                'wt' => 257,
                'cpu' => 409,
                'mu' => 4992,
                'pmu' => 0,
                'excl_wt' => 199,
                'excl_cpu' => 347,
                'excl_mu' => 1120,
                'excl_pmu' => 0
            ),
            'bar@1' => array(
                'id' => DataParser::hashFunctionName('bar@1'),
                'ct' => 4,
                'wt' => 58,
                'cpu' => 62,
                'mu' => 3872,
                'pmu' => 0,
                'excl_wt' => 36,
                'excl_cpu' => 37,
                'excl_mu' => 1040,
                'excl_pmu' => 0
            ),
            'bar@2' => array(
                'id' => DataParser::hashFunctionName('bar@2'),
                'ct' => 3,
                'wt' => 22,
                'cpu' => 25,
                'mu' => 2832,
                'pmu' => 0,
                'excl_wt' => 12,
                'excl_cpu' => 12,
                'excl_mu' => 1040,
                'excl_pmu' => 0
            ),
            'strlen' => array(
                'id' => DataParser::hashFunctionName('strlen'),
                'ct' => 5,
                'wt' => 20,
                'cpu' => 25,
                'mu' => 752,
                'pmu' => 0,
                'excl_wt' => 20,
                'excl_cpu' => 25,
                'excl_mu' => 752,
                'excl_pmu' => 0
            ),
            'bar@3' => array(
                'id' => DataParser::hashFunctionName('bar@3'),
                'ct' => 2,
                'wt' => 10,
                'cpu' => 13,
                'mu' => 1792,
                'pmu' => 0,
                'excl_wt' => 8,
                'excl_cpu' => 9,
                'excl_mu' => 1040,
                'excl_pmu' => 0
            ),
            'bar@4' => array(
                'id' => DataParser::hashFunctionName('bar@4'),
                'ct' => 1,
                'wt' => 2,
                'cpu' => 4,
                'mu' => 752,
                'pmu' => 0,
                'excl_wt' => 2,
                'excl_cpu' => 4,
                'excl_mu' => 752,
                'excl_pmu' => 0
            ),
            'xhprof_disable' => array(
                'id' => DataParser::hashFunctionName('xhprof_disable'),
                'ct' => 1,
                'wt' => 1,
                'cpu' => 2,
                'mu' => 760,
                'pmu' => 0,
                'excl_wt' => 1,
                'excl_cpu' => 2,
                'excl_mu' => 760,
                'excl_pmu' => 0
            )
        );
    }

    /**
     * assert that array has key and value
     *
     * @param mixed $expected_key
     * @param mixed $expected_value
     * @param array $array
     *
     * @return void
     */
    protected function assertArrayHasKeyHasValue($expected_key, $expected_value, array $array)
    {
        $this->assertArrayHasKey($expected_key, $array);
        $content = $array[$expected_key];
        $this->assertEquals($expected_value, $content);
    }
}
