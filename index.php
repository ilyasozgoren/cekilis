<?php require("includes/main.php"); ?>
<!DOCTYPE html>
<html lang="tr-TR">
    <head>
	<title>AYDINLI MERKEZ ZİRVE 2020</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<script type="text/javascript" src="js/jquery.js"></script>	
	<script src = 'js/jquery-ui-1.8.23.custom.min.js'></script>
	<script type="text/javascript" src="js/names.js"></script>	
	<script type="text/javascript" src="js/raffle.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>	
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/raffle.css">
</head>
<body style="background: url(img/aydBG.jpg) center repeat; background-size:cover;height:100%">
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
            <li class="active"><a href="./">2020 MERKEZ ZİRVESİ<span class="sr-only">(current)</span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	<!--<div class="header">
		<span>ÇEKİLİŞLER<b id="participant-number"></b></span>
	</div>-->

	<div class="row">
	<div class="col-md-12">
	<?php 
	$res1 = cekilisler(1);
	echo '<div class="esnekKutuKapsul">';
	echo '<div class="esnekKutu">Oturum 1</div>';
	foreach ($res1 as $key => $v1) {
		$kaz = cekilis_kazananlar($v1['id']);
		$kaz_say = count($kaz);
		if($kaz_say == $v1['win_count']):
			//$aktif="0";
			$buton =  '<br>';
		else:
			if($v1['full_katilim']>0):
				$buton =  '<a href="cekilis-yap.php?section=1&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			else:
				$buton =  '<a href="cekilis-yap-multi.php?section=1&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			endif;
		endif;
		echo '<div class="esnekKutu">';
			echo $buton;
			echo '<hr>';
			echo $v1['win_count']." Adet";
			echo '<br>';
			echo $v1['name'];
			echo '<hr>';
				echo '<div class="kazanan">';
					echo '<ul>';
					foreach ($kaz as $key => $v) {
						echo '<li>'.$v['adsoyad'].'</li>';
					}
					echo '</ul>';
				echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	/*$res1 = cekilisler(2);
	echo '<div class="esnekKutuKapsul">';
	echo '<div class="esnekKutu">Oturum 2</div>';
	foreach ($res1 as $key => $v1) {
		$kaz = cekilis_kazananlar($v1['id']);
		$kaz_say = count($kaz);
		if($kaz_say == $v1['win_count']):
			//$aktif="0";
			$buton =  '<br>';
		else:
			if($v1['full_katilim']>0):
				$buton =  '<a href="cekilis-yap.php?section=2&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			else:
				$buton =  '<a href="cekilis-yap-multi.php?section=2&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			endif;
		endif;
		echo '<div class="esnekKutu">';
			echo $buton;
			echo '<hr>';
			echo $v1['win_count']." Adet";
			echo '<br>';
			echo $v1['name'];
			echo '<hr>';
				echo '<div class="kazanan">';
					echo '<ul>';
					foreach ($kaz as $key => $v) {
						echo '<li>'.$v['adsoyad'].'</li>';
					}
					echo '</ul>';
				echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	$res1 = cekilisler(3);
	echo '<div class="esnekKutuKapsul">';
	echo '<div class="esnekKutu">Oturum 3</div>';
	foreach ($res1 as $key => $v1) {
		$kaz = cekilis_kazananlar($v1['id']);
		$kaz_say = count($kaz);
		if($kaz_say == $v1['win_count']):
			//$aktif="0";
			$buton =  '<br>';
		else:
			//$aktif="1"; 
			if($v1['full_katilim']>0):
				$buton =  '<a href="cekilis-yap.php?section=3&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			else:
				$buton =  '<a href="cekilis-yap-multi.php?section=3&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			endif;
		endif;
		echo '<div class="esnekKutu">';
			echo $buton;
			echo '<hr>';
			echo $v1['win_count']." Adet";
			echo '<br>';
			echo $v1['name'];
			echo '<hr>';
				echo '<div class="kazanan">';
					echo '<ul>';
					foreach ($kaz as $key => $v) {
						echo '<li>'.$v['adsoyad'].'</li>';
					}
					echo '</ul>';
				echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	$res1 = cekilisler(4);
	echo '<div class="esnekKutuKapsul">';
	echo '<div class="esnekKutu">Oturum 4</div>';
	foreach ($res1 as $key => $v1) {
		$kaz = cekilis_kazananlar($v1['id']);
		$kaz_say = count($kaz);
		if($kaz_say == $v1['win_count']):
			//$aktif="0";
			$buton =  '<br>';
		else:
			//$aktif="1"; 
			if($v1['full_katilim']>0):
				$buton =  '<a href="cekilis-yap.php?section=4&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			else:
				$buton =  '<a href="cekilis-yap-multi.php?section=4&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			endif;
		endif;
		echo '<div class="esnekKutu">';
			echo $buton;
			echo '<hr>';
			echo $v1['win_count']." Adet";
			echo '<br>';
			echo $v1['name'];
			echo '<hr>';
				echo '<div class="kazanan">';
					echo '<ul>';
					foreach ($kaz as $key => $v) {
						echo '<li>'.$v['adsoyad'].'</li>';
					}
					echo '</ul>';
				echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	$res1 = cekilisler(5);
	echo '<div class="esnekKutuKapsul">';
	echo '<div class="esnekKutu">Oturum 5</div>';
	foreach ($res1 as $key => $v1) {
		$kaz = cekilis_kazananlar($v1['id']);
		$kaz_say = count($kaz);
		if($kaz_say == $v1['win_count']):
			//$aktif="0";
			$buton =  '<br>';
		else:
			//$aktif="1"; 
			if($v1['full_katilim']>0):
				$buton =  '<a href="cekilis-yap.php?section=5&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			else:
				$buton =  '<a href="cekilis-yap-multi.php?section=5&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			endif;
		endif;
		echo '<div class="esnekKutu">';
			echo $buton;
			echo '<hr>';
			echo $v1['win_count']." Adet";
			echo '<br>';
			echo $v1['name'];
			echo '<hr>';
				echo '<div class="kazanan">';
					echo '<ul>';
					foreach ($kaz as $key => $v) {
						echo '<li>'.$v['adsoyad'].'</li>';
					}
					echo '</ul>';
				echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	$res1 = cekilisler(6);
	echo '<div class="esnekKutuKapsul">';
	echo '<div class="esnekKutu">Oturum 6</div>';
	foreach ($res1 as $key => $v1) {
		$kaz = cekilis_kazananlar($v1['id']);
		$kaz_say = count($kaz);
		if($kaz_say == $v1['win_count']):
			//$aktif="0";
			$buton =  '<br>';
		else:
			//$aktif="1"; 
			if($v1['full_katilim']>0):
				$buton =  '<a href="cekilis-yap.php?section=6&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			else:
				$buton =  '<a href="cekilis-yap-multi.php?section=6&cid='.$v1['id'].'&all='.$v1['full_katilim'].'" class="btn btn-lg btn-info"> BAŞLAT </a>';
			endif;
		endif;
		echo '<div class="esnekKutu">';
			echo $buton;
			echo '<hr>';
			echo $v1['win_count']." Adet";
			echo '<br>';
			echo $v1['name'];
			echo '<hr>';
				echo '<div class="kazanan">';
					echo '<ul>';
					foreach ($kaz as $key => $v) {
						echo '<li>'.$v['adsoyad'].'</li>';
					}
					echo '</ul>';
				echo '</div>';
		echo '</div>';
	}
	echo '</div>';*/
	?>
</div>
</div>

</body>
</html>