<?php

namespace frontend\controllers;

use yii\web\Controller;

class FreeController extends Controller
{
    public function actionIndex(){

        return $this->render('index');

    }

}