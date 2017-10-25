<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Twitter;
use app\models\EntTweets;
use yii\web\Response;
use Google\Cloud\Language\LanguageClient;
use \Statickidz\GoogleTranslate;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    /*public function actionIndex()
    {
        return $this->render('index');
    }*/
    public function actionIndex(){
        $twitter = new Twitter();
        $tweets = EntTweets::find()->where(['b_usado'=>0])->all();
        foreach($tweets as $tweet){
            $tweet->b_usado = 1;
            $tweet->save();
        }
        if( isset($_POST['hashtag']) && isset($_POST['numero']) ){
            //Yii::$app->response->format = Response::FORMAT_JSON;
            $arrayHashtag = explode(",", $_POST['hashtag']);
            
            $hoy = date("Y-m-d");
            $fecha = null;
            if($_POST['tiempo'] == 2){
                $fecha = date("Y-m-d", strtotime($hoy . '-4 day'));
            }
            if($_POST['tiempo'] == 3){
                $fecha = date("Y-m-d", strtotime($hoy . '-9 day'));
            }
            
            $json = $twitter->getTweets($arrayHashtag, $_POST['numero'], $fecha);

            //echo $json;exit();

            $jsonDecode = json_decode($json);
            //var_dump($jsonDecode);exit();
            
            $num_items = count($jsonDecode->statuses);
            
            for($i=0; $i<$num_items; $i++){
                $nuevoTweet = new EntTweets();

                $user = $jsonDecode->statuses[$i];

                $nuevoTweet->id = $user->id_str;
                $nuevoTweet->txt_usuario = $user->user->screen_name;                
                $nuevoTweet->txt_tweet =$user->text;
                $nuevoTweet->save();
            }
            
            $tweets = EntTweets::find()->where(['b_usado'=>0])->all();

            require __DIR__.'\..\vendor\autoload.php';
            $language = new LanguageClient([
                'projectId' => 'modified-wonder-176917',
                'keyFilePath' => '../web/Mi primer proyecto-449267dd9cee.json'
            ]);

            $traductor = new GoogleTranslate();
            
            return $this->renderAjax('apiGoogle4', [
                'language' => $language,
                'tweets' => $tweets,
                'traductor' => $traductor            
            ]);

            //return $this->renderAjax('about');
        }
        //echo $_POST['hashtag'];
        //exit();
        return $this->render('index2');
    }

    public function actionBuscarPalabra($pal, $sent){
        $tweets = EntTweets::find()->where(['b_usado'=>0])->all();
        $encontrados = [];
        $i = 0;
        foreach($tweets as $tweet){
            //echo $tweet->txt_tweet . "////-" . $pal; 
            $resultado = strpos($tweet->txt_tweet, $pal);
            if($resultado !== false){
                $encontrados[$i] = $tweet->txt_tweet;
                $i = $i + 1;
            }
        }

        require __DIR__.'\..\vendor\autoload.php';
        $language = new LanguageClient([
            'projectId' => 'modified-wonder-176917',
            'keyFilePath' => '../web/Mi primer proyecto-449267dd9cee.json'
        ]);

        return $this->renderAjax('modal',[
            'language' => $language,
            'encontrados' => $encontrados,
            'pal' => $pal,
            'sent' => $sent
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionMostrarTwitts(){
        $twitter = new Twitter();

        if( (isset($_POST['hashtag']) && isset($_POST['numero'])) || (isset($_POST['user']) && isset($_POST['numeroUser'])) ){
            if(!empty($_POST['hashtag'])){
                $arrayHashtag = explode(",", $_POST['hashtag']);

                $hoy = date("Y-m-d");
                $fecha = null;
                if($_POST['tiempo'] == 2){
                    $fecha = date("Y-m-d", strtotime($hoy . '-4 day'));
                }
                if($_POST['tiempo'] == 3){
                    $fecha = date("Y-m-d", strtotime($hoy . '-9 day'));
                }
                
                $json = $twitter->getTweets($arrayHashtag, $_POST['numero'], $fecha);

                //echo $json;exit();

                $jsonDecode = json_decode($json);
                
                $num_items = count($jsonDecode->statuses);
                for($i=0; $i<$num_items; $i++){
                    $nuevoTweet = new EntTweets();
    
                    $user = $jsonDecode->statuses[$i];
    
                    $nuevoTweet->id = $user->id_str;
                    $nuevoTweet->txt_usuario = $user->user->screen_name;                
                    $nuevoTweet->txt_tweet =$user->text;
                    $nuevoTweet->save();
                }

            }
            if(!empty($_POST['user'])){
                $json = $twitter->getTweetsUser($_POST['user'], $_POST['numeroUser']);

                $jsonDecode = json_decode($json);
                $num_items = count($jsonDecode);
                for($i=0; $i<$num_items; $i++){
                    $nuevoTweet = new EntTweets();
    
                    $user = $jsonDecode[$i];
    
                    $nuevoTweet->id = $user->id_str;
                    $nuevoTweet->txt_usuario = $user->user->screen_name;                
                    $nuevoTweet->txt_tweet =$user->text;
                    $nuevoTweet->save();
                }

            }else if(empty($_POST['hashtag'])){
                $this->redirect(['site/index']);
                return;
            }
        }else{
            $this->redirect(['site/index']);
            return;
        }
        
        $tweets = EntTweets::find()->where(['b_usado'=>0])->all();

        require __DIR__.'\..\vendor\autoload.php';
        $language = new LanguageClient([
            'projectId' => 'modified-wonder-176917',
            'keyFilePath' => '../web/Mi primer proyecto-449267dd9cee.json'
        ]);
        
        return $this->render('apiGoogle', [
            'language' => $language,
            'tweets' => $tweets            
        ]);
    }

    public function actionAnalizarTweet(){
        if(isset($_POST['twitt'])){
            require __DIR__.'\..\vendor\autoload.php';
            $language = new LanguageClient([
                'projectId' => 'modified-wonder-176917',
                'keyFilePath' => '../web/Mi primer proyecto-449267dd9cee.json'
            ]);
                    
            return $this->render('apiGoogle2', [
                'language' => $language,
                'twittsEnUnaLinea' => $_POST['twitt'],
            ]);
        }
    }        

    public function actionHabilitarTweet(){
        //Yii::$app->response->format = Response::FORMAT_JSON;
        $twittsEnUnaLinea = "";
        
        if(isset($_POST['twitter']) && isset($_POST['categoria'])){
            //var_dump($_POST['categoria']);
            //exit();
            $tweets = EntTweets::find()->where(['in', 'id_tweet', $_POST['twitter']])->all();        
            foreach($tweets as $tweet){
                $twittsEnUnaLinea = $twittsEnUnaLinea . $tweet->txt_tweet . " ";
                $tweet->b_habilitado = 1;
                $tweet->save();
            }

            require __DIR__.'\..\vendor\autoload.php';
            $language = new LanguageClient([
                'projectId' => 'modified-wonder-176917',
                'keyFilePath' => '../web/Mi primer proyecto-449267dd9cee.json'
            ]);
            
            return $this->render('apiGoogle', [
                'language' => $language,
                'twittsEnUnaLinea' => $twittsEnUnaLinea,
                'categorias' => $_POST['categoria']
            ]);
        }
    }

    /*public function actionResultadosApiGoogle($cat = null){
        require __DIR__.'\..\vendor\autoload.php';

        $language = new LanguageClient([
            'projectId' => 'modified-wonder-176917',
            //'keyFilePath' => __DIR__. '\..\web\Mi primer proyecto-449267dd9cee.json'
        ]);
        
        var_dump($cat);
        exit();
        $tweets = EntTweets::find()->where(['b_habilitado'=>1])->all();
             
        return $this->render('apiGoogle', [
            'tweets' => $tweets,
            'language' => $language
        ]);
    }*/    
}
