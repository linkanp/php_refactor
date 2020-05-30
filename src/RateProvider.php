<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: linkan
 * Date: 2020-05-05
 * Time: 18:27
 */

class RateProvider implements ProviderInterface
{
    private $url;
    private $currency;

    public function __construct($url, $currency)
    {
        $this->url = $url;
        $this->currency = $currency;
    }

    public function lookup()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $output;

    }

    public function formatResponse($output)
    {
        $rate = 0;
        if(!empty($output['rates'][$this->currency])){
            $rate = $output['rates'][$this->currency];
        }
        return $rate;
    }
}
