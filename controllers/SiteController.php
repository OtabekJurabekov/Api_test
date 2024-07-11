<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Json;
use yii\httpclient\Client;
use app\models\CarForm;
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function actionCars()
    {
        $httpClient = \Yii::$app->httpclient;
        $response = $httpClient->createRequest()
            ->setMethod('GET')
            ->setUrl('http://13.49.68.228/api/v1/cars/')
            ->send();

        if ($response->isOk) {
            $carsData = Json::decode($response->content);
            return $this->render('cars', [
                'carsData' => $carsData,
            ]);
        } else {
            Yii::error('API request failed: ' . $response->statusCode);
            // Handle the case where the API request was not successful
            // You might want to redirect or show an error message
        }
    }
    public function actionCarDelete($id)
    {
        // Create HTTP client instance
        $client = new \yii\httpclient\Client();
    
        try {
            // Send DELETE request to API endpoint
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('http://13.49.68.228/api/v1/car-delete/' . $id . '/')
                ->send();
            
            $responseData = Json::decode($response->content);

            if (isset($responseData['message'])) {
                Yii::$app->session->setFlash('success', $responseData['message']);
                return $this->redirect(['site/cars']);
            } else {
            }

        } catch (\yii\httpclient\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error communicating with API: ' . $e->getMessage());
        }
    
        // Redirect to index page or any other page after deletion
        return $this->redirect(['site/cars']);
    }
    
    public function actionCarDetail($id){
        $client = new \yii\httpclient\Client();
        try{
            $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://13.49.68.228/api/v1/cars/' . $id)
            ->send();

            $carData = Json::decode($response->content);

            return $this->render('car-detail', [
                'carData' => $carData,
            ]);
        } catch (\yii\httpclient\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error communicating with API: ' . $e->getMessage());
        }
    }

    public function actionCarCreate()
    {
        $model = new CarForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = new Client();

            try {
                $response = $client->createRequest()
                    ->setMethod('POST')
                    ->setUrl('http://13.49.68.228/api/v1/car-create/')
                    ->setData([
                        'model' => $model->model,
                        'brand' => $model->brand,
                        'description' => $model->description,
                        'color' => $model->color,
                        'year' => $model->year,
                        'price' => $model->price,
                    ])
                    ->send();

                if ($response->isOk) {
                    $carsData = Json::decode($response->content);
                    Yii::$app->session->setFlash('success', 'Car created successfully!');
                    return $this->redirect(['site/cars']);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to create car: API response status ' . $response->statusCode);
                }
            } catch (\yii\httpclient\Exception $e) {
                Yii::$app->session->setFlash('error', 'Error communicating with API: ' . $e->getMessage());
            }
        }

        return $this->render('car-create', [
            'model' => $model,
        ]);
    }


    public function actionCarUpdate($id)
{
    $client = new Client();
    
    try {
        // Fetch car details from the API
        $responseDetail = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://13.49.68.228/api/v1/cars/' . $id)
            ->send();

        // Decode the JSON response
        $carData = Json::decode($responseDetail->content);

        // Create a new instance of CarForm and populate it with fetched data
        $model = new CarForm();
        $model->attributes = $carData;

        // Handle form submission
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Prepare data for the API PUT request
            $requestData = [
                'model' => $model->model,
                'brand' => $model->brand,
                'description' => $model->description,
                'color' => $model->color,
                'year' => $model->year,
                'price' => $model->price,
            ];

            // Send PUT request to update the car details
            $response = $client->createRequest()
                ->setMethod('PUT')
                ->setUrl('http://13.49.68.228/api/v1/car-update/' . $id . "/") 
                ->setData($requestData)
                ->send();

            // Decode the response from the API
            $responseData = Json::decode($response->content);

            // Check if the update was successful
            if (isset($responseData['message'])) {
                Yii::$app->session->setFlash('success', $responseData['message']);
                return $this->redirect(['site/cars']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update car: ' . $response->content);
            }
        }
    } catch (\yii\httpclient\Exception $e) {
        Yii::$app->session->setFlash('error', 'Error communicating with API: ' . $e->getMessage());
    }

    // Render the update form with the model
    return $this->render('car-update', [
        'model' => $model,
    ]);
}


    
    /**
     * {@inheritdoc}
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
     * @return Response|string
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

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
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
}
