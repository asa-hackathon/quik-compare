<?php

namespace App\Helpers;
use Throwable;
use Exception;

class Helper
{
    public static function parseAttribute($object, $path){
        $pathArray = array_filter(explode('.', $path));

        if (empty($pathArray)) {
            return null;
        }

        return array_reduce($pathArray, function ($v1,$v2) {
            return $v1[$v2];
        }, $object);
    }

    public static function evaluateExpression($object, $expression){
        try {
            $utils = [
                "formatMoney" => function ($number, $format = "text", $currency = "rupees", $appendSymbol = true, $minSymbol = false, $partialMinSymbol = false) {
                    $CRORE = "Crore";
                    $LAKH = "Lakh";
                    $THOU = "Thou";
                    $HUN = "Hun";

                    if (empty($number))
                        return "";
                    $returnStr = "";
                    $number = trim($number);
                    if (strpos($number, '.') > 0)
                        $number = trim(substr($number,0, strpos($number, '.')));
                    if ($currency == "rupees") {
                        if (!$minSymbol) {
                            $format = (strlen($number) > 5) ? "decimal" : "number";
                        }

                        if ($format == "text") {
                            $rupeess = trim($number);

                            $crores = (int)($rupeess / 10000000);
                            $rupeess %= 10000000;

                            $lakhs = (int)($rupeess / 100000);
                            $rupeess %= 100000;
                            if (!$partialMinSymbol || $lakhs != 0 || $crores != 0) {
                                $thousands = (int)($rupeess / 1000);
                                $rupeess %= 1000;
                            } else {
                                $thousands = (int)$rupeess;
                            }

                            $hundreds = (int)($rupeess / 100);
                            $rupeess %= 100;

                            $tens = (int)($rupeess / 10);
                            $rupeess %= 10;

                            if (!$minSymbol) {
                                $returnStr .= ($crores == 0) ? "" : $crores . " " . $CRORE . " ";
                                $returnStr .= ($lakhs == 0) ? "" : $lakhs . " " . $LAKH . " ";
                                $returnStr .= ($thousands == 0) ? "" : $thousands . " " . $THOU . " ";
                                $returnStr .= ($hundreds == 0) ? "" : (($crores != 0 || $lakhs != 0 || $thousands != 0) ? " and " . $hundreds . " " . $HUN : $hundreds . " " . $HUN);
                            } else {

                                /* Don't show zero if less than 9
                                   Earlier 9000 = 09 K wrong
                                   Changed to 9 K
                                */

                                if ($lakhs <= 9 && $crores != 0) {
                                    $lakhs = "0" . $lakhs;
                                }
                                if ($thousands <= 9 && $lakhs != 0) {
                                    $thousands = "0" . $thousands;
                                }

                                $returnStr =
                                    ($crores == 0 ?
                                        ($lakhs == 0 ? ($thousands == 0 ? "" : (!$partialMinSymbol) ? $thousands . ($hundreds == 0 ? "" : "." . $hundreds . $tens) . "K " : $thousands) :
                                            $lakhs . ($thousands == 0 ? "" : "." . $thousands) . "L ")
                                        : $crores . ($lakhs == 0 ? "" : "." . $lakhs) . "Cr ");
                            }

//				$returnStr .= " Rupees";

                        } else if ($format == "number") {

                            $length = strlen($number);

                            if ($length > 3) {
                                $removeZero = false;
                                $hundredPart = substr($number, $length - 2);
                                $higherPart = substr($number, 0, $length - 2);
                                if (((($length - 3) % 2) == 1)) {
                                    $higherPart = "0" . $higherPart;
                                    $removeZero = true;
                                }
                                //$higherPart = ((($length - 3) % 2) == 1) ? "0".$higherPart : $higherPart;
                                $higherParts = str_split($higherPart, 2);

                                $returnStr .= implode(",", $higherParts) . $hundredPart;
                                if ($removeZero == true) {
                                    $returnStr = substr($returnStr, 1);
                                }

                            } else {
                                $returnStr = $number;
                            }

                        } else if ($format == 'decimal') {

                            $rupeess = trim($number);

                            $crores = (int)($rupeess / 10000000);
                            $rupeess %= 10000000;

                            $lakhs = (int)($rupeess / 100000);
                            $rupeess %= 100000;

                            $thousands = (int)($rupeess / 1000);
                            $rupeess %= 1000;

                            $hundreds = (int)($rupeess / 100);
                            $rupeess %= 100;

                            if ($crores != 0) {
                                $returnStr .= $crores;
                                if ($lakhs != 0) {
                                    if($lakhs <=9 && $lakhs > 0)
                                    {
                                        $returnStr .= ".0" . $lakhs;
                                        $returnStr .= (" " . $CRORE);
                                    }else{
                                        $returnStr .= "." . $lakhs;
                                        $returnStr .= (" " . $CRORE);
                                    }
                                } else {
                                    $returnStr .= (" " . (($crores == 1) ? $CRORE : ($CRORE . "s")));
                                }
                            } else if ($lakhs != 0) {

                                $returnStr .= $lakhs;
                                if ($thousands != 0) {
                                    if($thousands <=9 && $thousands > 0)
                                    {
                                        $returnStr .= ".0" . $thousands;
                                        $returnStr .= (" " . $LAKH);
                                    }else{
                                        $returnStr .= "." . $thousands;
                                        $returnStr .= (" " . $LAKH);
                                    }
                                } else {
                                    $returnStr .= (" " . (($lakhs == 1) ? $LAKH : ($LAKH . "s")));
                                }
                            } else if ($thousands != 0) {

                                $returnStr .= $thousands;
                                if ($hundreds != 0) {
                                    $returnStr .= "." . $hundreds;
                                    $returnStr .= (" " . $THOU);
                                } else {
                                    $returnStr .= (" " . $THOU);
                                }
                            } else if ($hundreds != 0) {
                                $returnStr .= "Rs. " . $rupeess;
                            }

                        }
                    }
                    return $returnStr;
                }
            ];

            $computedFunction = create_function('$response, $utils', $expression);

            if($computedFunction === false) {
                return null;
            }

            $result = $computedFunction($object, $utils);
            
            if(!is_null($result)) {
                return $result;
            } else {
                return null;
            }
        
        } catch(Throwable $e) {
            return null;
        }
    }
}