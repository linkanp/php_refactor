<?php
/**
 * Created by PhpStorm.
 * User: linkan
 * Date: 2020-03-06
 * Time: 19:56
 */

interface ProviderInterface
{
    public function lookup();

    public function formatResponse($output);
}
