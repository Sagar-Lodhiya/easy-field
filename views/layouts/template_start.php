<?php

/**
 * template_start.php
 *
 * Author: pixelcave
 *
 * The first block of code used in every page of the template
 *
 */

use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<!DOCTYPE HTML>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js"> <!--<![endif]-->

<head>
    <meta charset="utf-8">

    <title> <?= $this->title ?></title>

    <meta name="description" content="<?php echo $template['description'] ?>">
    <meta name="author" content="<?php echo $template['author'] ?>">
    <meta name="robots" content="<?php echo $template['robots'] ?>">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Bootstrap is included in its original form, unaltered -->
    <link rel="stylesheet" href="<?= Url::base(true) ?>/theme/css/bootstrap.min.css">
    <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
    <link rel="stylesheet" href="<?= Url::base(true) ?>/theme/css/main.css">
    <link rel="stylesheet" href="<?= Url::base(true) ?>/theme/css/jquery.growl.css">

    <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->
    <?php if ($template['theme']) { ?>
        <link id="theme-link" rel="stylesheet" href="<?= Url::base(true) ?>/theme/css/themes/<?php echo $template['theme']; ?>.css"><?php } ?>

    <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
    <link rel="stylesheet" href="<?= Url::base(true) ?>/theme/css/themes.css">
    <!-- END Stylesheets -->
    
    <!-- Css for map -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?= Url::base(true) ?>/css/map.css">
    <!-- Css for map end -->


    <script type="application/javascript">
        var baseUrl = '<?php echo BaseUrl::home(); ?>';
        var _csrf = '<?php echo Yii::$app->request->getCsrfToken() ?>';
    </script>
    <style>
        html,
        body {
            height: 100%;
        }

        .full-height {
            min-height: 100%;
        }

        .fieldset legend {
            border-color: #6ad2eb;
        }
    </style>
    
    <?= Html::csrfMetaTags() ?>

    <?php $this->head() ?>
</head>

<body>