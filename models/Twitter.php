<?php
namespace app\models;
use Yii;
use app\models\TwitterAPIExchange;

ini_set('display_errors', 1);
//require_once('TwitterAPIExchange.php');

class Twitter{

    public $settings = array(
        'oauth_access_token' => "840283510887845891-nPc3t0j71c4xXiAfjG10P7YqJ5jxT0Y",
        'oauth_access_token_secret' => "O1x2VCMlrAeWvSZ3IcGRe881m2P0Cxre5pTX1bfl6eHVh",
        'consumer_key' => "Ur4njGbkJvwVwUxYiBcDw31w3",
        'consumer_secret' => "ctDjorxcO8SXOYKI58MpHKhZaLEhLrY4S17cVAvkiVlgh7ePR5"
    );

    function getTweets($hashtag, $num){
        //BUSCAR POR HASHTAG
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        //$getfield = '?q=%23';
        $getfield = '?q=';
        //Verificar si en uno o varios hashtags
        if(count($hashtag) > 1){
            foreach($hashtag as $hash){
                //$getfield = $getfield . $hash . "%20OR%20%23";
                $getfield = $getfield . urlencode($hash) . "%20OR%20";
            }
            $getfield = $getfield . '&count=' . $num . '&result_type=recent';                                
        }else{
            //$getfield = '?q=%23'. $hashtag[0] . '&count=' . $num . '&result_type=recent';
            $getfield = '?q='. urlencode($hashtag[0]) . '&count=' . $num . '&result_type=recent';
        }
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($this->settings);
        $json =  $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return $json;
    }

    function getTweetsUser($user, $num){
        //BUSCAR POR USUARIO
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        //echo $fecha;exit();
        $getfield = '?screen_name=' . $user . '&count=' . $num;
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($this->settings);
        //echo $getfield;exit();        
        $json =  $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
            
        return $json;
    }
}
?>