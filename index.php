<?php
/**
 * Created by PhpStorm.
 * User: linkan
 * Date: 2020-05-04
 * Time: 20:12
 */
require_once __DIR__ . "/vendor/autoload.php";


$input = new Input($argv[1]);
$inputRows = $input->read();

foreach ( $inputRows as $k => $row ) {
    $binProvider = new BinProvider('https://lookup.binlist.net/', $row->bin);
    $rateProvider = new RateProvider('https://api.exchangeratesapi.io/latest', $row->currency);
    $calculator = new CommissionCalculator( $binProvider, $rateProvider, $row);
    $commission = $calculator->calculate();
    $output = new Output();
    $output->outputText($commission);
    echo PHP_EOL;
}
