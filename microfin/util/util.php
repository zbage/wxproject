<?php
header("Content-Type: text/html;charset=utf-8");
class Util{
    /*
     *
     */
    public static function currentDate(){
        date_default_timezone_set('PRC');
        return date('Y-m-d');
    }

    /*
     *
     */
    public static function currentTime(){
        date_default_timezone_set('PRC');
        return date('H:i:s');
    }

    /*
     *
     */
    public static function current(){
        date_default_timezone_set('PRC');
        return date('Y-m-d H:i:s');
    }
}
?>