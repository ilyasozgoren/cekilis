<?php require("includes/main.php"); ?>
<?php

$cekilisid = $_GET['cid'];
$all = $_GET['all'];
$section = $_GET['section'];
$ckontrol = cekilis_kontrol($cekilisid);

/*cekilise_katilamayanlar(1,5,0);
exit;*/

?>
<!DOCTYPE html>
<html lang="tr-TR">
    <head>
	<title>AYDINLI MERKEZ ZİRVE 2020</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<script type="text/javascript" src="js/jquery.js"></script>	
	<script src ="js/jquery-ui-1.8.23.custom.min.js"></script>
	<script type="text/javascript" src="js/names.js"></script>	
	<script type="text/javascript" src="js/raffle.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>	
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">	
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/raffle.css">
</head>
<body style="background: url(img/aydBG.jpg) center repeat; background-size:cover;">
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><img src="img/footer-logo-b-01.png"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <!--<li class="active"><a href="#">Home</a></li>-->
          </ul>
          <ul class="nav navbar-nav navbar-right">
			  <!--<li class="active"><span><button class="btn btn-success">ÇEKİLİŞİ BAŞLAT</button></span></li>-->
            <li class="active"><span>KATILIMCI:<b id="participant-number"></b></span> <span class="sr-only">(current)</span></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<div class="secimAlan" id="secim">
	<?php
		if($ckontrol):
	?>
	<!--<div class="header">
		<span>KATILIMCI:<b id="participant-number"></b></span>
		<button>Başla</button>
	</div>-->
	
	<div class='enter-names'>
		<H3>Katılımcı Listesi</H3>
		
		<?php
		$klist = katilimci_list($section,$cekilisid,$all);
		foreach ($klist as $key => $v) {
			$k.=$v['id'].' - '.$v['adsoyad'].'&#13;&#10;';
		}
		?>
		<button onclick="process();" class="btn btn-sm btn-info">ÇEKİLİŞİ BAŞLAT</button>
		<textarea class='name-text-field' ><?php echo $k;?></textarea>
		<input id="remove-winners-input" type="checkbox" onclick="toggleRemoveWinners();" checked="checked"/><label for="remove-winners-input">.</label>
	</div>
	
	<?php
	else:
	?>
	<div class="header">
		<span>ÇEKİLİŞ YAPILDI</span>
	</div>
	<?php
	endif;
	?>
	</div>

</body>
</html>