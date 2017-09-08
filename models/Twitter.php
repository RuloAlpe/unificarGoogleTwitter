<?php
namespace app\models;
use Yii;
use app\models\TwitterAPIExchange;

class Twitter{
    
    function getTweets($hashtag, $num){
        ini_set('display_errors', 1);
        //require_once('TwitterAPIExchange.php');
    
        $settings = array(
            'oauth_access_token' => "840283510887845891-nPc3t0j71c4xXiAfjG10P7YqJ5jxT0Y",
            'oauth_access_token_secret' => "O1x2VCMlrAeWvSZ3IcGRe881m2P0Cxre5pTX1bfl6eHVh",
            'consumer_key' => "Ur4njGbkJvwVwUxYiBcDw31w3",
            'consumer_secret' => "ctDjorxcO8SXOYKI58MpHKhZaLEhLrY4S17cVAvkiVlgh7ePR5"
        );

        //BUSCAR POR USUARIO
        /*$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$user.'&count=5';        
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($settings);
        $json =  $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();*/

        //BUSCAR POR HASHTAG
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield = '?q=%23';
        //Verificar si en uno o varios hashtags
        if(count($hashtag) > 1){
            foreach($hashtag as $hash){
                $getfield = $getfield . $hash . "%20OR%20%23";            
            }
            $getfield = $getfield . '&count=' . $num;
        }else{
            $getfield = '?q=%23'. $hashtag[0] . '&count=' . $num;                    
        }
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($settings);
        $json =  $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return $json;
    }
}
?>