<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use app\assets\LoginAsset;
use app\widgets\Alert;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\BaseUrl;

LoginAsset::register($this);
$this->title = 'Welcome To '. Yii::$app->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js"> <!--<![endif]-->

<head>
    <meta charset="utf-8">

    <meta name="author" content="<?php echo 'Codeflix Web' ?>">
    <meta name="robots" content="<?php echo 'noindex, nofollow' ?>">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <?= Html::csrfMetaTags() ?>

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="<?= BaseUrl::home() ?>theme/img/favicon.png">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="<?= BaseUrl::home() ?>theme/img/icon180.png" sizes="180x180">
    <!-- END Icons -->
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <style>
        <?php
        if ($this->context->action->id == 'dealer-login') { ?>html {
            background: white;
        }

        <?php
        }
        if ($this->context->action->id == 'fo-login') {
        ?>html {
            background: lightblue;
        }

        <?php
        }
        ?>.invalid-feedback {
            color: red;
        }
    </style>
</head>

<body style="background-color: #99c9ff;">
    <?php $this->beginBody() ?>
    <!-- Login Background -->
    <!-- <div id="login-background"> -->

        <!-- For best results use an image with a resolution of 2560x400 pixels (prefer a blurred image for smaller file size) -->
        <!-- <img src="<?= BaseUrl::home() ?>theme/img/placeholders/headers/login_header.jpg" alt="Login Background" class="full-bg animation-pulseSlow"> -->
        
        <!-- C:\wamp\www\staff-yii\web\theme\img\placeholders\photos\photo20@2x.jpg -->
        <!--<img src="<?= BaseUrl::home() ?>theme/img/login3.png" alt="Login Background" class="full-bg animation-pulseSlow">-->
    <!-- </div> -->
    <!-- END Login Background -->

    <!-- Login Container -->
    <div id="login-container" class="animation-fadeIn">
        <div class="row" style="font-size: initial">
            <?= Alert::widget() ?>
        </div>
        <!-- Login Title -->
        <div class="login-title text-center">
            <h1><img style="margin-bottom: 2px;" height="75px" src="<?= BaseUrl::home() ?>theme/img/logo.png" alt="" srcset="">
                <!-- </br> -->
                <!-- <strong><?= $this->title ?></strong><br><small>Please <strong>Login</strong></small> -->
            </h1>
        </div>
        <!-- END Login Title -->

        <!-- Login Block -->
        <div class="block push-bit">
            <!-- Login Form -->
            <!--        <form action="index.html" method="post" id="form-login" class="">-->
            <?php $form = ActiveForm::begin(['id' => 'form-login', 'options' => ['class' => 'form-horizontal form-bordered form-control-borderless']]); ?>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Email'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group form-actions">
                    <div class="col-xs-4">
                        <?= $form->field($model, 'rememberMe')->checkbox(['checked' => false]) ?>
                    </div>
                    <div class="col-xs-8 text-right">
                        <?= Html::submitButton('Login to Dashboard', ['class' => 'btn btn-sm btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <!-- END Login Form -->
            </div>
            <!-- END Login Block -->

            <!-- Footer -->
            <footer class="text-muted text-center">
                <small><span><?= date('Y') ?></span> &copy; <a href="<?= BaseUrl::home() ?>"><?= Yii::$app->id ?></a></small>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Login Container -->

        <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>
            !window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-1.11.2.min.js"%3E%3C/script%3E'));

            window.addEventListener('DOMContentLoaded', async () => { // Wait for DOM to load first
                try {
                    // Code to wait for 5 seconds before retrieving device ID
                    const deviceId = await window.electron.getDeviceId();
                    // Use the retrieved deviceId for login purposes (e.g., send it with login request)
                    console.log('Device ID:', deviceId);
                    $("#loginform-device_id").val(deviceId);
                } catch (error) {
                    console.error('Error getting device ID:', error);
                }
            });
        </script>
        <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>