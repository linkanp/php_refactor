<?php
/**
 * Created by PhpStorm.
 * User: linkan
 * Date: 2020-05-26
 * Time: 19:32
 */

use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{

    public function inputProvider()
    {
        $binDataString1 = '{"number":{"length":16,"luhn":true},"scheme":"visa","type":"debit","brand":"Visa/Dankort","prepaid":false,"country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank","url":"www.jyskebank.dk","phone":"+4589893300","city":"Hjørring"}}';
        $binDataString2 = '{"number":{},"scheme":"visa","country":{"numeric":"840","alpha2":"US","name":"United States of America","emoji":"🇺🇸","currency":"USD","latitude":38,"longitude":-97},"bank":{"name":"VERMONT NATIONAL BANK","url":"www.communitynationalbank.com","phone":"(802) 744-2287"}}';
        $binDataString3 = '{"number":{"length":16,"luhn":true},"scheme":"visa","type":"debit","brand":"Traditional","prepaid":null,"country":{"numeric":"826","alpha2":"GB","name":"United Kingdom of Great Britain and Northern Ireland","emoji":"🇬🇧","currency":"GBP","latitude":54,"longitude":-2},"bank":{}}';

        $inputRow1 = (object)['bin' => 45717360, 'amount' => 100.00, 'currency' => 'EUR'];
        $inputRow2 = (object)['bin' => 41417360, 'amount' => 130.00, 'currency' => 'USD'];
        $inputRow3 = (object)['bin' => 4745030, 'amount' => 2000.00, 'currency' => 'GBP'];

        $rate1 = 1;
        $rate2 = 1.0975;
        $rate3 = 0.88878;
        return [
            [$binDataString1, $inputRow1, $rate1, 1],
            [$binDataString2, $inputRow2, $rate2,  2.37],
            [$binDataString3, $inputRow3, $rate3,  45.01]
        ];
    }

    /**
     * @dataProvider inputProvider
     */
    public function testCommissionCalculation($binDataString, $inputRow, $rate, $expected)
    {
        $binProvider = $this->createMock(BinProvider::class);
        $binProvider->method('lookup')
            ->willReturn($binDataString);
        $binProvider->method('formatResponse')
            ->willReturn(json_decode($binDataString));

        $rateProvider = $this->createMock(RateProvider::class);

        $rateProvider->method('formatResponse')
            ->willReturn($rate);

        $calculator = new CommissionCalculator($binProvider, $rateProvider, $inputRow);



        $this->assertEquals($expected, $calculator->calculate());
    }

    public function euDataProvider()
    {
        return [
            ['US', 0.02],
            ['AT', 0.01],
            ['GR', 0.01],
            ['BD', 0.02]
        ];
    }

    /**
     * @dataProvider euDataProvider
     */
    public function testEuValue($code, $expected)
    {
        $binProvider = $this->createMock(BinProvider::class);
        $rateProvider = $this->createMock(RateProvider::class);
        $inputRow = (object)['bin' => 45717360, 'amount' => 100.00, 'currency' => 'EUR'];
        $calculator = new CommissionCalculator($binProvider, $rateProvider, $inputRow);

        $reflector = new ReflectionClass(CommissionCalculator::class);
        $method = $reflector->getMethod('getEuValue');
        $method->setAccessible(true);
        $result = $method->invoke($calculator, $code);
        $this->assertEquals($expected, $result);
    }
}
