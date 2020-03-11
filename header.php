<?php require("includes/main.php"); ?>
<?php require ('global/initialize.php'); ?>
<!DOCTYPE html>
<html lang="tr-TR">
    <head>
        <title>AYDINLI MERKEZ ZİRVE 2020</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <?php foreach ($CDN['CSS'] as $index => $value){ ?>

        <link rel="stylesheet" href="<?php echo $value; ?>">
        <?php } ?>

        <link rel="stylesheet" href="assets/css/style.css">

        <link rel="shortcut icon" href="assets/img/favicon.png">
    </head>
    <body style="background: url(img/aydBG.jpg) center repeat; background-size:cover;height:100%">
        <nav class="navbar navbar-expand-lg navbar-inverse navbar-dark">
            <a class="navbar-brand" href="index.php">
                <img src="img/footer-logo-b-01.png" width="114" height="23" class="d-inline-block align-top" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <!--<a class="nav-link" href="index.php">Ana Sayfa</a>-->
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="nav-link"><a href="./">2020 MERKEZ ZİRVESİ<span class="sr-only">(current)</span></a></li>
                </ul>
            </div>
        </nav>