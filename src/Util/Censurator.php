<?php


namespace App\Util;


class Censurator
{
    private $badWords;

    public function __construct()
    {
        $this->badWords = ['fuck', 'pussy'];
    }

    public function purify(string $textToPurify) :string
    {
        foreach ($this->badWords as $badWord){
            $replace = str_pad(substr($badWord, 0, 1) , mb_strlen($badWord), '*');
            $textToPurify = str_ireplace($badWord, $replace, $textToPurify);
        }


        return $textToPurify;
    }
}