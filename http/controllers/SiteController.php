<?php

namespace http\controllers;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $this->render("index");
    }
}