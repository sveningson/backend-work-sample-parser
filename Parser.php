<?php

class Parser{
    private static $file = "config.txt";

    private $parsedArray = [];

    private function parseArray($keys, $value){
        $array = [];
        if(!$key = array_shift($keys))
            if(is_numeric($value)){
                return (int) trim($value);
            }elseif(trim($value) === "false"){
                return FALSE;
            }elseif(trim($value) === "true"){
                return TRUE;
            }else{
                return trim(trim($value),'"');
            }
        
        return $array[] =  array(trim($key) => self::parseArray($keys, $value));
    }

    public function run(){
        $parsedArray = [];
        $myfile = fopen(self::$file, 'r') or die("Unable to open file!");
        while(($line = fgets($myfile)) !== false){
            $line = trim($line);
            if(substr($line, 0,1) === "#")
                continue;
            if(empty($line))
                continue;
             
            $data = explode("=", $line);
            $keys = explode(".", $data[0]);
            $value = $data[1];

            if(!$parsedArray){
                $parsedArray = self::parseArray($keys, $value);
            }else{
                $parsedArray = array_merge_recursive($parsedArray, self::parseArray($keys, $value));
            }
        }

        fclose($myfile);
    }

}