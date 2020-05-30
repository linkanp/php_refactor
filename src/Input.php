<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: linkan
 * Date: 2020-05-04
 * Time: 20:26
 */

class Input
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function read()
    {
        $file = fopen($this->path,"r");
        $input = [];
        while(! feof($file))
        {
            $content = fgets($file);
            if(!empty($content)){
                $input[] = json_decode($content);
            }
        }
        fclose($file);
        return $input;
    }

}
