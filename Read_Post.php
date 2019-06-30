<?php
include "jdate.php";
require 'vendor/autoload.php';

use voku\helper\HtmlDomParser;
$last_update="2019/5/2";
$html = HtmlDomParser::file_get_html('http://khabarsun.com/techno/page/1/');
$ht=$html->find('.year-month');
$year=$ht->find('em');
$dsn = "pgsql:host=localhost;port=5432;dbname=discourse_development;user=sama;password=samatoos110";
// create a PostgreSQL database connection
$conn = new PDO($dsn);
foreach ($year as $r){
    $yer_latin=ta_latin_num($r->plaintext);
    $yer = preg_replace("/[^0-9]/", '', $yer_latin);
    $yer_g=jalali_to_gregorian($yer,'1','1');
    $element = $html->find('.day-month');
    foreach ($element as $row){
        $f=ta_latin_num($row->plaintext);
        $split=split("[0-9]",$f);
        $alpha=$split[(sizeof($split))-1];
        $day=explode($alpha, $f);
        str_replace(' ','',$alpha);
        $nu_month=word_pe_lat($alpha);
        $date_post_array=jalali_to_gregorian($yer,$nu_month,$day[0]);
        $date_post=implode('/',$date_post_array);
        $date1=date_create($date_post);
        $date2=date_create($last_update);
        $diff=date_diff($date1,$date2);
             if($diff->format("%R")==="+"){



                     if($conn){
                         $select="SELECT raw from posts where id=13";
                         $stmt=$conn->query($select);
                         $row = $stmt->fetch(PDO::FETCH_ASSOC) ;
//                            $body=$row['raw']."</br>"."test";
                         $body = $row['raw']
                             .'ehsan';
                         $sth = $conn->prepare('Update posts set raw=:body , cooked=:cooked where id=:id');
                         $sth->execute(array(
                             ':body' => $body,
                             ':cooked' => $body,
                             ':id'=>13
                         ));
                     }
            }else
                exit();
    }
    $last_update=$date_post;

}
$conn="";




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