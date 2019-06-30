<?php
//require 'vendor/autoload.php';
//use Goutte\Client;
//
//$client= new Client();
//header('Content-Type: text/html; charset=utf-8');
//$crawler = $client->request('GET', 'http://khabarsun.com/techno/');
//var_dump($crawler);
//die();
//
//$xmlDoc = new DOMDocument();
////$r=$xmlDoc->loadHTML();
//
//var_dump($r);


include "jdate.php";
require 'vendor/autoload.php';

use voku\helper\HtmlDomParser;
$last_update="2019/1/2";
$last_update_sp=split('/',$last_update);
$html = HtmlDomParser::file_get_html('http://khabarsun.com/techno/page/1/');
$ht=$html->find('.year-month');
$year=$ht->find('em');
foreach ($year as $r){
    $yer_latin=ta_latin_num($r->plaintext);
    $yer = preg_replace("/[^0-9]/", '', $yer_latin);
    $yer_g=jalali_to_gregorian($yer,'1','1');
    $last_update_sp=split('/',$last_update);
    if($yer_g[0] >= $last_update_sp[0]){
        $element = $html->find('.day-month');
        foreach ($element as $row){
            $f=ta_latin_num($row->plaintext);
            $split=split("[0-9]",$f);
            $alpha=$split[(sizeof($split))-1];
            $day=explode($alpha, $f);
            str_replace(' ','',$alpha);
            $nu_month=word_pe_lat($alpha);
            $date_post=jalali_to_gregorian($yer,$nu_month,$day[0]);
            if ($date_post[1] >= $last_update_sp[1]){

                    if ($date_post[2] >= $last_update_sp[2]){
                      echo $i++."</br>";

                    }else
                        continue;
            }else
                continue;
        }
        $last_update=implode('/',$date_post);
    }else
        exit();
}

die();



function ta_latin_num($string) {
    //arrays of persian and latin numbers
    $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $latin_num = range(0, 9);

    $string = str_replace($persian_num, $latin_num, $string);

    return $string;
}

function word_pe_lat($st){
    switch ($st){
        case "فروردین":
            return 1;
        case "اردیبهشت":
            return 2;
        case  "خرداد":
            return 3;
        case  "تیر":
            return 4;
        case "مرداد":
            return 5;
        case "شهریور":
            return 6;
        case "مهر":
            return 7;
        case  "آبان":
            return 8;
        case  "آذر":
            return 9;
        case "دی":
            return 10;
        case  "بهمن" :
            return 11;
        case  "اسفند":
            return 12;
    }
}