<?php

namespace app\controllers;

use Yii;
use app\models\Entry;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CorkboardController
 */
class CorkboardController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
};