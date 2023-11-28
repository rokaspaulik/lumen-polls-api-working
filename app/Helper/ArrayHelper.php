<?php

namespace App\Helper;

class ArrayHelper
{
    public static function assignLetters(array $inputArray)
    {
        $alphabet = range('A', 'Z');
        $resultArray = [];

        foreach ($inputArray as $index => $value) {
            $letterIndex = $index % 26;
            $letter = $alphabet[$letterIndex];
            $resultArray[$letter] = $value;
        }

        return $resultArray;
    }

    // TODO check if is working
    public function decodeLetters(array $letterAssignedArray)
    {
        $alphabet = range('A', 'Z');
        $decodedArray = [];

        foreach ($letterAssignedArray as $letter => $value) {
            $letterIndex = array_search($letter, $alphabet);
            $decodedArray[$letterIndex] = $value;
        }

        ksort($decodedArray);

        return $decodedArray;
    }
}
