<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/4 0004
 * Time: 下午 10:32
 */

namespace backend\controllers;


use yii\web\Controller;

class TestController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        date_default_timezone_set ('Asia/Shanghai');
        set_time_limit(0);

        $buffer    = ini_get('output_buffering');
        if($buffer){
            ob_end_flush();
        }

        echo '处理新词库...'.PHP_EOL;
        flush();
        $filename     = "words.txt";
        $handle     = fopen ($filename, "r");
        $content     = fread ($handle, filesize ($filename));
        fclose ($handle);

        $content    =    trim($content);
        $arr1         =     explode( "\r\n" ,$content );
        $arr1        =    array_flip(array_flip($arr1));

        foreach($arr1 as $key=>$value){

            $value  =   dealchinese($value);
            if(!empty($value)){
                $arr1[$key] = $value;
            }else{
                unset($arr1[$key]);
            }
        }

        echo '处理原来词库...'.PHP_EOL;
        flush();
        $filename2     = "unigram.txt";
        $handle2     = fopen ($filename2, "r");
        $content2     = fread ($handle2, filesize ($filename2));
        fclose ($handle2);
        $content2    = dealchinese($content2,"\r\n");
        $arr2         = explode( "\r\n" ,$content2 );
        echo '删除相同词条...'.PHP_EOL;
        flush();
        $array_diff    = array_diff($arr1,$arr2);

        echo '格式化词库...'.PHP_EOL;
        flush();
        $words='';
        foreach($array_diff as $k => $word){
            $words .= $word."\t1".PHP_EOL."x:1".PHP_EOL;
        }
//echo $words;
        file_put_contents('words_new.txt',$words,FILE_APPEND);
        echo 'done!';

        function dealChinese($str, $join=''){
            preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches); //将中文字符全部匹配出来
            $str = join($join, $matches[0]); //从匹配结果中重新组合
            return $str;
        }
    }
}