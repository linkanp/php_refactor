<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: linkan
 * Date: 2020-05-05
 * Time: 18:27
 */

class BinProvider implements ProviderInterface
{
    private $url;
    private $bin;

    public function __construct($url, $bin)
    {
        $this->url = $url;
        $this->bin = $bin;
    }

    public function lookup()
    {
        $url = $this->url.$this->bin;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        return $output;
    }

    public function formatResponse($output)
    {
        return json_decode($output);
    }
}
