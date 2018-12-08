<?php
use yii\helpers\Html;
$view->title = 'Update plan - Writenator';
?>
-use yii\helpers\Html
-use yii\bootstrap\ActiveForm
-$view->title = 'Login'
-$view->params['breadcrumbs'][] = $view->title;
.site-login
  h1 #{$view->title}
  p Please fill out the following fields to login:
  .row
    .col-lg-5
      -$form = ActiveForm::begin(['id' => 'login-form'])
      !=$form->field($model, 'username')
      !=$form->field($model, 'password')->passwordInput()
      !=$form->field($model, 'rememberMe')->checkbox()
      .form-group
        !=Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button'])
      -ActiveForm::end()
