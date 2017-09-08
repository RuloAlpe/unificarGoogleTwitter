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
    public function actionIndex()
    {
        return $this->render('index');
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

        if(isset($_POST['hashtag']) && isset($_POST['numero']) ){
            $arrayHashtag = explode(",", $_POST['hashtag']);
            /*echo count($arrayHashtag);
            var_dump($arrayHashtag);
            exit();*/
            $json = $twitter->getTweets($arrayHashtag, $_POST['numero']);
            
            //echo $json;
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
        $tweets = EntTweets::find()->where(['b_usado'=>0])->all();        

        return $this->render('mostrarTweets', [
            'tweets' => $tweets
        ]);
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
                    
            return $this->render('apiGoogle2', [
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