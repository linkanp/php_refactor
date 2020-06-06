<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: linkan
 * Date: 2020-05-04
 * Time: 20:59
 */

class CommissionCalculator
{
    const COUNTRY_CODE = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];

    private $bin;
    private $amount;
    private $currency;

    private $binProvider;
    private $rateProvider;


    public function __construct( ProviderInterface $binProvider, ProviderInterface $rateProvider, $inputRow )
    {
        $this->bin = $inputRow->bin;
        $this->amount = $inputRow->amount;
        $this->currency = $inputRow->currency;
        $this->binProvider = $binProvider;
        $this->rateProvider = $rateProvider;
    }

    public function calculate()
    {
            $output = $this->binProvider->lookup();
            $binData = $this->binProvider->formatResponse($output);

            $output = $this->rateProvider->lookup();
            $rate = $this->rateProvider->formatResponse($output);
            if (is_numeric($rate) || $this->currency == 'EUR') {
                if ($this->currency == 'EUR' || $rate == 0) {
                    $amountFixed = $this->amount;
                }
                if ($this->currency != 'EUR' || $rate > 0) {
                    $amountFixed = $this->amount / $rate;
                }
            } else {
                throw new Exception('Rate is not valid.');
            }

            $euValue = $binData->country->alpha2;
            $commission = round($amountFixed * $this->getEuValue($euValue), 2, PHP_ROUND_HALF_EVEN);
            return $commission;
    }

    private function getEuValue($code) {
        $result = 0.02;
        if(in_array($code, self::COUNTRY_CODE)){
            $result = 0.01;
        }
        return $result;
    }
}
