<?php
function cekilisler($section){

  $output = ORM::for_table('raffles')
  ->where('section',$section)
  ->where('active',1)
  ->order_by_asc('sira')
  ->find_array();
 
  return $output;
 
}
function cekilis_kontrol($cid){

  $output = ORM::for_table('raffles')
  ->where('id',$cid)
  ->where('active',1)
  ->find_array();
 if(count($output)):
  return true;
 else:
  return false;
 endif;
 
}
function section_buyuk_cekilisler($section){
    $res1 = ORM::raw_execute("SELECT
    id
    FROM
    raffles
    WHERE full_katilim ='1' and active=1");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1['id']; 
    }
    return $rows1;
}
function cekilise_katilamayanlar($section,$cid,$all){
  if($all>0):
    $buyuk = section_buyuk_cekilisler($section);
    if(count($buyuk)):
      $whr = implode(",",$buyuk);
      $sql = "WHERE raffles.id in($whr) ";
    else:
      $whr = " ";
    endif;
    $res1 = ORM::raw_execute("SELECT
    raffle_results.pid
    FROM
    raffles
    INNER JOIN raffle_results ON raffles.id = raffle_results.rid $sql
    ");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1['pid']; 
    }
    return $rows1;
  else:
    $buyuk = section_buyuk_cekilisler($section);
    $buyuk[] = $cid;
    $whr = implode(",",$buyuk);
    
    $res1 = ORM::raw_execute("SELECT
    raffle_results.pid
    FROM
    raffles
    INNER JOIN raffle_results ON raffles.id = raffle_results.rid
    WHERE
    raffles.id in($whr) and raffles.active=1");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1['pid']; 
    }
    return $rows1;
  endif;

  
  /*$statement1 = ORM::get_last_statement();
  $rows1 = array();
  while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
      $rows1[] = $row1; 
  }

  return $rows1;*/
}
function katilimci_list($section,$cid,$all){
  $nocekilis = cekilise_katilamayanlar($section,$cid,$all); 
  //print_r($nocekilis);
  //exit;
  if(count($nocekilis)>0):
    $whr = implode(",",$nocekilis);
    $sql = "where id not in ($whr)";
  else:
    $sql = " ";
  endif;
  $res1 = ORM::raw_execute("select * from participants $sql order by rand()");
  $statement1 = ORM::get_last_statement();
  $rows1 = array();
  while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
      $rows1[] = $row1; 
  }
 
  return $rows1;
 
}
function cekilis_kazananlar($rid){
  
  $res1 = ORM::raw_execute("SELECT
  participants.adsoyad,
  raffle_results.rid
  FROM
  raffle_results
  INNER JOIN participants ON raffle_results.pid = participants.id
  where raffle_results.rid = $rid");
  $statement1 = ORM::get_last_statement();
  $rows1 = array();
  while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
      $rows1[] = $row1; 
  }
 
  return $rows1;
 
}
function insert_winner_user($rid,$persno){
    $addWinner = ORM::for_table('raffle_results')->create();
    $addWinner->rid = $rid;
    $addWinner->pid = $persno;
    $addWinner->status = 1;
    $addWinner->credate = date("Y-m-d H:i:s");
    $addWinner->save();
    return $addWinner->id();
} 
function turkcetarih_formati($format, $datetime = 'now'){
  $z = date("$format", strtotime($datetime));
  $gun_dizi = array(
      'Monday'    => 'Pazartesi',
      'Tuesday'   => 'Salı',
      'Wednesday' => 'Çarşamba',
      'Thursday'  => 'Perşembe',
      'Friday'    => 'Cuma',
      'Saturday'  => 'Cumartesi',
      'Sunday'    => 'Pazar',
      'January'   => 'OCAK',
      'February'  => 'ŞUBAT',
      'March'     => 'MART',
      'April'     => 'NİSAN',
      'May'       => 'MAYIS',
      'June'      => 'HAZİRAN',
      'July'      => 'TEMMUZ',
      'August'    => 'AĞUSTOS',
      'September' => 'EYLÜL',
      'October'   => 'EKİM',
      'November'  => 'KASIM',
      'December'  => 'ARALIK',
      'Mon'       => 'Pts',
      'Tue'       => 'Sal',
      'Wed'       => 'Çar',
      'Thu'       => 'Per',
      'Fri'       => 'Cum',
      'Sat'       => 'Cts',
      'Sun'       => 'Paz',
      'Jan'       => 'Oca',
      'Feb'       => 'Şub',
      'Mar'       => 'Mar',
      'Apr'       => 'Nis',
      'Jun'       => 'Haz',
      'Jul'       => 'Tem',
      'Aug'       => 'Ağu',
      'Sep'       => 'Eyl',
      'Oct'       => 'Eki',
      'Nov'       => 'Kas',
      'Dec'       => 'Ara',
  );
  foreach($gun_dizi as $en => $tr){
      $z = str_replace($en, $tr, $z);
  }
  if(strpos($z, 'Mayıs') !== false && strpos($format, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
  return $z;
}
function tr_strtolower($metin) {
  return mb_strtolower($metin, 'utf-8');
}
function tr_strtoupper($metin) {
  return mb_strtoupper($metin, 'utf-8');
}
function tr_ucfirst($metin) {
  $ilk = mb_substr($metin,0,1, 'utf-8');
  $kalan = mb_substr($metin,1,strlen($metin), 'utf-8');
        return tr_strtoupper($ilk).tr_strtolower($kalan);
}
function match_uri($str){
  preg_match('|^/([^/]+)|', $str, $matches);

  if (!isset($matches[1]))
    return false;

  return $matches[1];  
}
function resimal($data){
  $pattern= '/src\=\"(.*)\" style/';
  //$content = '23:15:59';
   
  preg_match_all($pattern, $data, $results);
   
  //print_r($results[1][0]);

  return $results[1][0];
}
function lang_texts(){
 
  $res = ORM::raw_execute("select * from language_text where active='1'");
  $statement = ORM::get_last_statement();
  $rows = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $rows[] = $row; 
  }
  return $rows;
}
function tarifler_anasayfa(){

  $output = ORM::for_table('tarifler')
  ->where('active',1)
  ->order_by_desc('id')
  ->limit(3)
  ->find_array();
 
  return $output;
 
}
function tarifler(){

  $output = ORM::for_table('tarifler')
  ->where('active',1)
  ->limit(15)
  ->find_array();
 
  return $output;
 
 }
 function tarifler_count(){

  $output = ORM::for_table('tarifler')
  ->where('active',1)
  ->find_array();
 
  return $output;
 
 }

 function tariflerLoad($row,$rowperpage){

  $res1 = ORM::raw_execute("select * from tarifler limit ".$row.",".$rowperpage);
  $statement1 = ORM::get_last_statement();
  $rows1 = array();
  while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
      $rows1[] = $row1; 
  }
  
    return $rows1;
 
 }

 function tarif_icerik_getir($slug,$lang){

  if($slug):
    if($lang=="TR"):
      $sqlek=" where postSlug_TR='".$slug."'";
    else:
        $sqlek=" where postSlug_EN='".$slug."'";
    endif;
  
  endif;

  $res1 = ORM::raw_execute("select * from tarifler ".$sqlek);
  $statement1 = ORM::get_last_statement();
  $rows1 = array();
  while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
      $rows1[] = $row1; 
  }
  
    return $rows1[0];
}
function blog_posts(){

  $output = ORM::for_table('blog_posts')
  ->where('active',1)
  ->limit(15)
  ->find_array();
 
  return $output;
 
}
function blog_count(){

  $output = ORM::for_table('blog_posts')
  ->where('active',1)
  ->find_array();
 
  return $output;
 
}
function blogLoad($row,$rowperpage){

  $res1 = ORM::raw_execute("select * from blog_posts limit ".$row.",".$rowperpage);
  $statement1 = ORM::get_last_statement();
  $rows1 = array();
  while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
      $rows1[] = $row1; 
  }
  
    return $rows1;
 
}
function blog_anasayfa(){

  $output = ORM::for_table('blog_posts')
  ->where('active',1)
  ->order_by_desc('id')
  ->limit(3)
  ->find_array();
 
  return $output;
 
 }
 function blog_icerik_getir($slug,$lang){

  if($slug):
    if($lang=="TR"):
      $sqlek=" where postSlug_TR='".$slug."'";
    else:
        $sqlek=" where postSlug_EN='".$slug."'";
    endif;
  
  endif;

  $res1 = ORM::raw_execute("select * from blog_posts ".$sqlek);
  $statement1 = ORM::get_last_statement();
  $rows1 = array();
  while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
      $rows1[] = $row1; 
  }
  
    return $rows1[0];
}
function blog_icerik_getirID($bid){

    //print_r("select * from blog_posts where id='".$bid."' and active=1");

    $res1 = ORM::raw_execute("select * from blog_posts where id='".$bid."' and active=1");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1; 
    }
  
    return $rows1[0];
}
function blogNextPrev($bid){
  
  $res = ORM::raw_execute("SELECT @a := $bid, ( SELECT id FROM blog_posts WHERE id < @a and active=1  ORDER BY id DESC LIMIT 1 ) AS prev_id,
  ( SELECT id FROM blog_posts WHERE id > @a and active=1  ORDER BY id ASC LIMIT 1 ) AS next_id FROM blog_posts LIMIT 1 ");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows[0];
}
function eyup_sef_basliklar($bid){
  
  $res = ORM::raw_execute("select * from sef_eyup where id = '".$bid."' and durum = '1'");
  $statement = ORM::get_last_statement();
  $rows = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $rows[] = $row; 
  }
  return $rows[0];
}
function eyup_sef_icerikler($icerik_id){
  $res = ORM::raw_execute("select * from sef_eyup_icerikler where icerik_id = '".$icerik_id."' and durum = '1'");
  $statement = ORM::get_last_statement();
  $rows = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $rows[] = $row; 
  }
  return $rows;
}
function icerik_sayfa($slug,$lang){
  if($slug):
    $sql = " where slug_".$lang." = '$slug' ";
    //echo $sql;
  else:
    $sql ="";
  endif;

  $res = ORM::raw_execute("select * from pages $sql");
      $statement = ORM::get_last_statement();
      $rows = array();
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
          $rows[] = $row; 
      }
      
      return $rows[0];
}

function insert_newsletter_user($email){
  $email = cleanInput($email);
  $count_kod = ORM::for_table('newsletters')
					->where('email', $email)
					->count();

	if($count_kod>0):
		$result['sonuc']="0";
		$result['durum']=$email." adresi ile bir abonelik bulunmaktadır. Teşekkürler. ";
		return $result;
	else:
  
    $ekle = ORM::for_table('newsletters')->create();
    $ekle->email = $email;
    $ekle->credate =date("Y-m-d H:i:s");
    $ekle->save();

    $result['sonuc']="1";
		$result['durum']="Bülten aboneliğiniz alınmıştır. Teşekkürler. ";
		return $result;
  endif;
}
function ulke_getir(){
$res = ORM::raw_execute("select * from countries order by CountryNameTR");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function sehir_getir($country_id){
$res = ORM::raw_execute("select * from cities where CountryID = $country_id order by CityNameTR");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function sehir_isim($city){
$res = ORM::raw_execute("select CityNameTR from cities where CityID = $city ");
    $statement = ORM::get_last_statement();
    //$rows = array();
    $row = $statement->fetch(PDO::FETCH_ASSOC);
       
    return $row['CityNameTR'];
}


function kurumsal_cozumler($lang,$slug){

    if($slug):
      $sql = " where slug_".$lang." = '$slug' ";
      //echo $sql;
    else:
      $sql ="";
    endif;
  
    $res = ORM::raw_execute("select * from kurumsal_cozumler $sql");
        $statement = ORM::get_last_statement();
        $rows = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row; 
        }
        
      if($slug):  
        return $rows[0];
      else:
        return $rows;
      endif;
    
}

function kurumsal_cozumler_icerik($lang,$slug){

  if($lang =="TR"):
    $sql=" WHERE kurumsal_cozumler.slug_TR = '$slug' ";
  else:
    $sql=" WHERE kurumsal_cozumler.slug_EN = '$slug' ";
  endif;
  $res = ORM::raw_execute("SELECT
  kurumsal_cozumler_icerik.coverBaslik,
  kurumsal_cozumler_icerik.coverBaslik_EN,
  kurumsal_cozumler_icerik.postCover,
  kurumsal_cozumler_icerik.postImage,
  kurumsal_cozumler_icerik.postDesc,
  kurumsal_cozumler_icerik.postDesc_EN,
  kurumsal_cozumler_icerik.sira
  FROM
  kurumsal_cozumler
  INNER JOIN kurumsal_cozumler_icerik ON kurumsal_cozumler.id = kurumsal_cozumler_icerik.kuid
  $sql ");
        $statement = ORM::get_last_statement();
        $rows = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row; 
        }
        return $rows;
}


function egitim_isim($egitim){

  switch ($egitim) {
    
    case "1":
    return "Profesyonel Aşçılık";
    break;
    case "2":
    return "Profesyonel Pastacılık ve Ekmekçilik";
    break;
    case "3":
    return "Yiyecek - İçecek İşletmeciliği";
    break;
    case "4":
    return "Chef & Owner Profesyonel Aşçılık";
    break;
    case "5":
    return "Chef & Owner Profesyonel Pasta ve Ekmekçilik";
    break;
    case "8":
    return "World Chefs “Commis Chef” Aşçılık";
    break;
    case "10":
    return "World Chefs “Commis Chef” Pastacılık";
    break;
    case "9":
    return  "Profesyonel Fırıncılık ve Ekmekçilik";
    break;
    case "7":
    return "Profesyonel Barista";
    break;
    case "6":
    return "Smart Chef";
    break;
    case "11":
    return "Kurumlara Özel Profesyonel Eğitimler";
    break;
    case "12":
    return "Yabancı Öğrenciler İçin Eğitim";
    break;
    default:
    break;
  }
       
    //return $row['CityNameTR'];
}
function seo($s) {
 $tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',':',',');
 $eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','');
 $s = str_replace($tr,$eng,$s);
 $s = strtolower($s);
 $s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
 $s = preg_replace('/\s+/', '-', $s);
 $s = preg_replace('|-+|', '-', $s);
 $s = preg_replace('/#/', '', $s);
 $s = str_replace('.', '', $s);
 $s = trim($s, '-');
 return $s;
}


function footer_menu_treeList_count($catid){
  $res = ORM::raw_execute("SELECT
  *, SUBSTR(
    hierarchy,
    1,
    (
      LENGTH(hierarchy) - LENGTH(cat_id) - 1
    )
  ) AS parent,
  LENGTH(hierarchy) - LENGTH(REPLACE(hierarchy, '-', '')) AS LEVEL
FROM
  menu_categories WHERE SUBSTR(
    hierarchy,
    1,
    (
      LENGTH(hierarchy) - LENGTH(cat_id) - 1
    )
  ) = '$catid' and active = 1 ");
        $statement = ORM::get_last_statement();
        $rows = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row; 
        }
        return $rows;
}
function footer_menu_treeList($catid,$lang){
  
  $res = ORM::raw_execute("SELECT
  *, SUBSTR(
    hierarchy,
    1,
    (
      LENGTH(hierarchy) - LENGTH(cat_id) - 1
    )
  ) AS parent,
  LENGTH(hierarchy) - LENGTH(REPLACE(hierarchy, '-', '')) AS LEVEL
  FROM
  menu_categories WHERE SUBSTR(
    hierarchy,
    1,
    (
      LENGTH(hierarchy) - LENGTH(cat_id) - 1
    )
  ) = '$catid' and active = 1 ");
        
    $statement = ORM::get_last_statement();

    /*echo"<pre>";
    print_r($statement->fetch(PDO::FETCH_ASSOC));
    echo"</pre>";*/
    //exit;
    $lvl = $lvl1 =  0;
    //$kk=1;
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      //$k = explode("-",$row['hierarchy']);

      //$dosyaSay = fileCount($row['cat_id']);
      $i = 0;
      //if ($i == 0) echo '<div class="col-lg-4 col-md-3 col-sm-12"><ul>';

      switch ($row['cat_id']) {
        case "1":
          echo '<div class="col-lg-4 col-md-3 col-sm-12"><ul>';
          break;
        case "16":
        echo '<div class="col-lg-3 col-md-3 col-sm-12"><ul style="padding-top: 20px;">';
          break;
        case "17":
          echo '<div class="col-lg-3 col-md-3 col-sm-12"><ul>';
            break;
        case "19":
        echo '<div class="col-lg-2 col-md-3 col-sm-12"><ul>';
          break;
        default:
      }
      //echo '<li>';
      switch ($row['LEVEL']) {
        case "0":
          echo '<li class="foot-ana">';
          break;
        case "1":
        echo '<li class="foot-parent-ana">';
          break;
        case "2":
          echo '<li class="foot-parent-alt">';
            break;
        default:
      }
      
      echo '<a href="'.$row['menu_link'].'">';
      if($lang == "EN"): 
        echo $row['cat_name_EN'];
      else:
        echo $row['cat_name'];
      endif;
      echo '</a>';
      //echo '<br></b>  <button class="btn btn-info" data-toggle="modal" data-id="'.$row['cat_id'].'" id="catEdit_doc"><i class="fa fa-pencil" ></i></button>  &nbsp; ';
      //echo '<button class="btn btn-warning" data-toggle="modal"  data-id="'.$row['hierarchy'].'" id="catAdd_doc"><i class="glyphicon glyphicon-plus" ></i></button> &nbsp; ';
      //echo '</h5>';
      //echo '<hr>';
      footer_menu_treeList($row['hierarchy'],$lang);
      echo '</li>';
      //$i++;
      //if ($i > 0) echo '</ul></div>';
      switch ($row['cat_id']) {
        case "44":
          echo '</ul></div>';
          break;
        case "51":
        echo '</ul></div>';
          break;
        case "66":
          echo '</ul></div>';
            break;
        case "80":
        echo '</ul></div>';
          break;
        default:
      }
    }
}
function head_menu_treeList($catid,$lang){


  $res = ORM::raw_execute("SELECT
  *, SUBSTR(
    hierarchy,
    1,
    (
      LENGTH(hierarchy) - LENGTH(cat_id) - 1
    )
  ) AS parent,
  LENGTH(hierarchy) - LENGTH(REPLACE(hierarchy, '-', '')) AS LEVEL
  FROM
  menu_categories WHERE SUBSTR(
    hierarchy,
    1,
    (
      LENGTH(hierarchy) - LENGTH(cat_id) - 1
    )
  ) = '$catid' and active = 1 ");
        
    $statement = ORM::get_last_statement();

    /*echo"<pre>";
    print_r($statement->fetch(PDO::FETCH_ASSOC));
    echo"</pre>";*/
    //exit;
    $lvl = $lvl1 =  0;
    //$kk=1;
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      //$k = explode("-",$row['hierarchy']);

      //echo '<li>';
      

      $i = 0;
      //if($catid)
      
      switch ($row['LEVEL']) {
        case "0":
        echo '<li class="menuAna"><a href="#">'.$row['cat_name'._DIL].'<span class="menuleft-btn"></span></a>';
        break;
        case "1":
          if($row['menu_link']):
          echo '<li class="menuParent"><a href="'.$row['menu_link'].'">'.$row['cat_name'._DIL].'</a>';
          else:
          echo '<li class="menuParent"><a href="#">'.$row['cat_name'._DIL].'<span class="menuleft-btn"></span></a>';
          endif;
        break;
        case "2":
        echo '<li class="menuParentAlt"><a href="'.$row['menu_link'].'">'.$row['cat_name'._DIL].'</a>';
        break;
        default:
      }
      
        if ($i == 0 && !$row['menu_link']): 
        echo '<ul>';
        head_menu_treeList($row['hierarchy'],$lang);
        echo '</li>';
        $i++;
        else:
          echo "</li>";
        endif;
        if ($i > 0) echo '</ul>';
      
      
      //echo '<a href="'.$row['menu_link'].'">';
      //echo $row['cat_name'];
      //echo '</a>';
      //footer_menu_treeList($row['hierarchy']);
      //echo '</li>';
      //$i++;
      //if ($i > 0) echo '</ul></div>';
    }
}
function logout(){
  $_SESSION = array();
  unset($_SESSION);
}

function send_email_iletisim($data){

  $subject="İletişim Form";
    
    if($data):

    $BODY ='<table class="body">
    <tr>
      <td><b>Adı Soyadı</b>  </td>
      <td>'.$data["name"].' </td>
    </tr>
    <tr>
      <td><b>Telefon</b> </td>
      <td>'.$data["phone"].'</td>
    </tr>
    <tr>
      <td><b>E-Posta</b></td>
      <td>'.$data["email"].'</td>
    </tr>
    <tr>
      <td><b>Mesaj</b></td>
      <td> '.$data["message"].'</td>
    </tr>
    <tr>
      <td><b>Tarih</b></td>
      <td> '.date("Y-m-d H:i:s").'</td>
    </tr>
    </table>';
            //$emails=$_POST['email'];
    $emails=array("info@eksmutfak.com.tr");
    //$emails=array("iozgoren@gmail.com");
    $bccemails=array("iozgoren@gmail.com");
    else:

      $BODY = '
    
      <p><br>HATA OLUŞTU VEYA BOŞ FORM GELDİ</p><br>
      <p>FORM NO : '.$fno.' <br>
      TARİH : '.date("Y-m-d H:i:s").' <br>
    IP : '.$_SERVER['REMOTE_ADDR'].'<br>';
            //$emails=$_POST['email'];
    $emails=array("iozgoren@gmail.com","geveze67@yahoo.com");
    $bccemails=array("iozgoren@yahoo.com");

    endif;    
    $logo = 'http://www.eksmutfak.com.tr/img/header/logo-01.png';
       
    $icerik=$BODY;
        
    $status = send_message($emails, $subject,  $icerik, $logo, $bccemails);
}

function addBizeUlas($data){
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
  $addContact = ORM::for_table('iletisim_form')->create();
  $addContact->isim = $data['name'];
  $addContact->mesaj = $data['message'];
  $addContact->telefon = $data['phone'];
  $addContact->email = $data['email'];
  $addContact->ip = $ip;
  $addContact->credate = date("Y-m-d H:i:s");;
  $addContact->save();

  send_email_iletisim($data);
  return $addContact->id();

}

function popupcek($sayfa){

  if($sayfa):
        $res = ORM::raw_execute("SELECT * FROM popup where sayfa = '".$sayfa."' and expr_date > CURDATE() and active='1' ");
        $statement = ORM::get_last_statement();
        $rows = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            //var_dump($row);
            $rows[] = $row; 
        }
        return $rows[0];
     
  endif; 
}
function workshop_kategorileri($id){
  if($id):
$sqlek=" id='".$id."' AND ";
else:
  $sqlek="";
  endif;

    $res = ORM::raw_execute("select * from workshop_cat where $sqlek active='1' order by name ");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function slider_getir(){
    $res = ORM::raw_execute("select * from slider where slider_durum = 1 order by slider_sira");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}

function is_ortaklari($durum=0){
    if($durum==0):
    $res = ORM::raw_execute("select * from referans");
  else:
    $res = ORM::raw_execute("select * from referans where anasayfa='1'");
  endif;
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function pro_egitim_getir($pro_id){

    if($pro_id):
    $sqlek=" where id='".$pro_id."'";
    else:
    $sqlek="";
    endif;

    $res1 = ORM::raw_execute("select * from pro_egitimler $sqlek");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1; 
    }
    if($pro_id):
      return $rows1[0];
    else:
      return $rows1;
    endif;
    
}
function pro_egitim_getir_slug($slug,$lang){

  //echo $lang."fdsfdsf";

    if($slug):
      if($lang=="TR"):
        $sqlek=" where slug_TR='".$slug."'";
      else:
          $sqlek=" where slug_EN='".$slug."'";
      endif;
    else:
    $sqlek="";
    endif;

    $res1 = ORM::raw_execute("select * from pro_egitimler $sqlek");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1; 
    }
    if($pro_id):
      return $rows1[0];
    else:
      return $rows1;
    endif;
    
}
function blog_post_list_slide(){

    $res1 = ORM::raw_execute("select * from blog_posts where slide>0 order by slide limit 3");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1; 
    }
    
    return $rows1;
}
function blog_post_list(){

    $res1 = ORM::raw_execute("select * from blog_posts where slide<1 order by postID desc");
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1; 
    }
    
      return $rows1;
}
function egitmen_getir(){
    $res = ORM::raw_execute("select * from egitmenler where aktif = 1 order by sira");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function danisman_getir(){
    $res = ORM::raw_execute("select * from egitmenler where danisman_kurul = 1 order by id");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function getEgitmen($e_id){
    $res = ORM::raw_execute("select * from egitmenler where id =".$e_id);
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function pro_egitim_program_getir($pro_id){
    $res = ORM::raw_execute("select * from pro_egitim_program where pro_id = ".$pro_id." order by haftano");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function akademik_takvim_liste(){
   $res = ORM::raw_execute("SELECT
pro_egitimler.egitim_ad,
pro_egitimler.id,
akademik_takvim.baslangic_trh,
DAY(akademik_takvim.baslangic_trh) as d,
akademik_takvim.haftaici,
akademik_takvim.haftasonu,
akademik_takvim.bilgi,
akademik_takvim.ucret,
akademik_takvim.aktif
FROM
akademik_takvim
INNER JOIN pro_egitimler ON akademik_takvim.egitim_id = pro_egitimler.id
ORDER BY
akademik_takvim.aktif ASC,
akademik_takvim.baslangic_trh ASC");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}

function egitim_ucretler(){
    $res = ORM::raw_execute("select * from pro_egitimler_ucretler");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function workshop_getir($work_id){
    $res1 = ORM::raw_execute("select * from workshoplar where id=".$work_id);
    $statement1 = ORM::get_last_statement();
    $rows1 = array();
    while ($row1 = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $rows1[] = $row1; 
    }
    return $rows1[0];
}


function workshop_program_getir($work_id){
    $res = ORM::raw_execute("select * from workshoplar_program where work_id = ".$work_id." order by haftano");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function showCalendar($ay,$yil){

  $gun_dizi = array(
      'Monday'    => 'Pazartesi',
      'Tuesday'   => 'Salı',
      'Wednesday' => 'Çarşamba',
      'Thursday'  => 'Perşembe',
      'Friday'    => 'Cuma',
      'Saturday'  => 'Cumartesi',
      'Sunday'    => 'Pazar',
      'January'   => 'Ocak',
      'February'  => 'Şubat',
      'March'     => 'Mart',
      'April'     => 'Nisan',
      'May'       => 'Mayıs',
      'June'      => 'Haziran',
      'July'      => 'Temmuz',
      'August'    => 'Ağustos',
      'September' => 'Eylül',
      'October'   => 'Ekim',
      'November'  => 'Kasım',
      'December'  => 'Aralık',
      'Mon'       => 'Pts',
      'Tue'       => 'Sal',
      'Wed'       => 'Çar',
      'Thu'       => 'Per',
      'Fri'       => 'Cum',
      'Sat'       => 'Cts',
      'Sun'       => 'Paz',
      'Jan'       => 'Oca',
      'Feb'       => 'Şub',
      'Mar'       => 'Mar',
      'Apr'       => 'Nis',
      'Jun'       => 'Haz',
      'Jul'       => 'Tem',
      'Aug'       => 'Ağu',
      'Sep'       => 'Eyl',
      'Oct'       => 'Eki',
      'Nov'       => 'Kas',
      'Dec'       => 'Ara',
  );

    // Get key day informations. 
    // We need the first and last day of the month and the actual day
  $today    = getdate();

  if($today['mon']==$ay):
    $firstDay = getdate(mktime(0,0,0,$today['mon'],1,$today['year']));
    $lastDay  = getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));
  else:
    $carpan = $ay - $today['mon']; 
    $plusmonth = 2678400 * $carpan;
    $gelecekAy = time() + $plusmonth;

    $today    = getdate($gelecekAy);
    $firstDay = getdate(mktime(0,0,0,$today['mon'],1,$today['year']));
    $lastDay  = getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));
  endif;

/*echo "<pre>";
print_r($firstDay);
echo "</pre>";
echo "<pre>";
print_r($lastDay);
echo "</pre>";*/

/*<div class="event-desc">
          Mangal Keyfi
          <hr>
        </div>
        <div class="event-desc">
          Kenwood ile Temel Pastacılık 4 Haftalık 2. ders
        </div>*/

    /*if($today['mon'] == 1):

    else:*/
    $oncekiAyisim1 = date("F", mktime(null, null, null, $today['mon']-1, 1));
    $sonrakiAyisim1 = date("F", mktime(null, null, null, $today['mon']+1, 1));

    $oncekiAyisim = $gun_dizi[$oncekiAyisim1];
    $sonrakiAyisim = $gun_dizi[$sonrakiAyisim1];
    
    $oncekiay = $today['mon']-1;
    $oncekiyil = $today['year'];

    $sonrakiay = $today['mon']+1;
    $sonrakiyil = $today['year'];

    if($oncekiay<1):
      $oncekiay  = 12;
      $oncekiyil = $oncekiyil-1;
    endif;

    if($sonrakiay>12):
      $sonrakiay  = 1;
      $sonrakiyil = $sonrakiyil+1;
    endif;
    //endif;
    $bas = $yil."-".$ay."-".$firstDay['mday'];
    $bit = $yil."-".$ay."-".$lastDay['mday'];
    
    $worklist = aylik_workshop_liste($bas,$bit,"");
    /*echo "<pre>";
print_r($worklist);
echo "</pre>";*/
    
    //$firstDay = getdate(mktime(0,0,0,4,1,$today['year']));
    //$lastDay  = getdate(mktime(0,0,0,4+1,0,$today['year']));

    //$nextmonth = 

    //$date = getdate(date('Y-m-d', strtotime('+1 month')));
    
    echo '<div class="container">
        <div class="row">
          <div class="col-lg-2">
            <p class="aylist-geri"><span></span> <a href="workshop-takvimi.php?ay='.$oncekiay.'&yil='.$oncekiyil.'">'.$oncekiAyisim.'</a> </p>
          </div>
          <div class="col-lg-8">
              <h4 class="takvim-workshop text-center">Workshoplar</h4>
              <p class="takvim-workshop-ay text-center">'.$gun_dizi[$today['month']].' '.$today['year'].'</p>
          </div>
          <div class="col-lg-2">
            <p class="aylist-ileri text-right"><a href="workshop-takvimi.php?ay='.$sonrakiay.'&yil='.$sonrakiyil.'">'.$sonrakiAyisim.'</a> <span></span></p>
          </div>
        </div>
          <div class="seperator"></div>
        <div class="element">
          <div class="row">
            <div class="toolbox">
              <div class="t-item"> 
                <span class="toolbox-title"> Liste Görünümü</span> <a href="workshop-takvimi-liste.php"><img src="img/workshop/liste-icon.png"></a>
              </div>
              <div class="t-item">
                <span class="toolbox-title">  Filtreler </span><a href="workshop-takvimi-liste.php"><img src="img/workshop/filtre-icon.png"></a>
              </div>
              <div class="t-item">
                  <span class="toolbox-title">Tükenenleri Gizle </span>
                  <label class="switch"> 
                    <input type="checkbox" class="success">
                    <span class="slider round"></span>
                  </label>
              </div>
            </div>
            <div class="col-lg-12">';
    // Create a table with the necessary header informations
    echo '<table id="calendar">';
    //echo '  <tr><th colspan="7">'.$today['month']." - ".$today['year']."</th></tr>";
    echo '<tr class="weekdays">';
    echo '  <th scope="col">Pazartesi</th>
            <th scope="col">Salı</th>
            <th scope="col">Çarşamba</th>
            <th scope="col">Perşembe</th>
            <th scope="col">Cuma</th>
            <th scope="col">Cumartesi</th>
            <th scope="col">Pazar</th>';
    echo '<tr class="days">';

    if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
    for($i=1;$i<$firstDay['wday'];$i++){
        echo '<td class="day other-month"> </td>';
    }
    $actday = 0;
    for($i=$firstDay['wday'];$i<=7;$i++){
        $actday++;
        if ($actday == $today['mday']) {
            $class = 'day';
            $class_number = ' class="date"';
        } else {
            $class = 'day';
            $class_number = ' class="date"';
        }
        if($worklist[$actday]):
            $icerik = '<div class="event-desc">
                  <a href="workshoplar-gunluk.php?work_id='.$worklist[$actday]['id'].'">'.$worklist[$actday]['baslik'].'
                  <hr>
                  
                  </a>
                  </div>';

                  $classvar = " workshopvar";
        else:
          $icerik = '';
           $classvar = "";
        endif;
        echo '<td class="'.$class.' '.$classvar.'"><div'.$class_number.'>'.$actday.' </div>'.$icerik.' </td>';
    }
    echo '</tr>';
    
    //Get how many complete weeks are in the actual month
    $fullWeeks = floor(($lastDay['mday']-$actday)/7);
    
    for ($i=0;$i<$fullWeeks;$i++){
        echo '<tr class="days">';
        for ($j=0;$j<7;$j++){
            $actday++;
            if ($actday == $today['mday']) {
                $class = 'day';
                $class_number = ' class="date"';
            } else {
                $class = 'day';
                $class_number = ' class="date"';
            }
            if($worklist[$actday]):
              $icerik = '<div class="event-desc">
                  <a href="workshoplar-gunluk.php?work_id='.$worklist[$actday]['id'].'">'.$worklist[$actday]['baslik'].'
                  <hr>
                  
                  </a>
                  </div>';
                  $classvar = " workshopvar";
            else:
              $icerik = '';
              $classvar = "";
            endif;
            echo '<td class="'.$class.' '.$classvar.'"><div'.$class_number.'>'.$actday.' </div>'.$icerik.' </td>';
        }
        echo '</tr>';
    }
    
    
    //Now display the rest of the month
    if ($actday < $lastDay['mday']){
        echo '<tr class="days">';
        $sont = 1;
        for ($i=0; $i<7;$i++){
            $actday++;
            if ($actday == $today['mday']) {
                $class = ' day';
                $class_number = ' class="date"';
            } else {
                $class = 'day other-month';
                $class_number = ' class="date"';
            }
            
            if ($actday <= $lastDay['mday']){
                if($worklist[$actday]):
                   $icerik = '<div class="event-desc">
                  <a href="workshoplar-gunluk.php?work_id='.$worklist[$actday]['id'].'">'.$worklist[$actday]['baslik'].'
                  <hr>
                  
                  </a>
                  </div>';
                  $classvar = " workshopvar";
                else:
                  $icerik = '';
                  $classvar = "";
                endif;
                //echo "<td$class><div$class_number>$actday</div> $icerik</td>";
                 echo '<td class="'.$class.' '.$classvar.'"><div'.$class_number.'>'.$actday.' </div>'.$icerik.' </td>';
            }
            else {
                $classvar = "";
                echo '<td class="'.$class.' '.$classvar.'"><div'.$class_number.'>  </div></td>';
                $sont++;
            }
        }
        
        
        echo '</tr>';
    }
    
    echo '</table>';

    echo '   </div>
          </div>
        </div>
    </div>';
}

function showCalendar_liste($ay,$yil){

  $gun_dizi = array(
      'Monday'    => 'Pazartesi',
      'Tuesday'   => 'Salı',
      'Wednesday' => 'Çarşamba',
      'Thursday'  => 'Perşembe',
      'Friday'    => 'Cuma',
      'Saturday'  => 'Cumartesi',
      'Sunday'    => 'Pazar',
      'January'   => 'OCAK',
      'February'  => 'ŞUBAT',
      'March'     => 'Mart',
      'April'     => 'Nisan',
      'May'       => 'Mayıs',
      'June'      => 'Haziran',
      'July'      => 'Temmuz',
      'August'    => 'Ağustos',
      'September' => 'Eylül',
      'October'   => 'Ekim',
      'November'  => 'Kasım',
      'December'  => 'Aralık',
      'Mon'       => 'Pts',
      'Tue'       => 'Sal',
      'Wed'       => 'Çar',
      'Thu'       => 'Per',
      'Fri'       => 'Cum',
      'Sat'       => 'Cts',
      'Sun'       => 'Paz',
      'Jan'       => 'Oca',
      'Feb'       => 'Şub',
      'Mar'       => 'Mar',
      'Apr'       => 'Nis',
      'Jun'       => 'Haz',
      'Jul'       => 'Tem',
      'Aug'       => 'Ağu',
      'Sep'       => 'Eyl',
      'Oct'       => 'Eki',
      'Nov'       => 'Kas',
      'Dec'       => 'Ara',
  );

    // Get key day informations. 
    // We need the first and last day of the month and the actual day
  $today    = getdate();

  if($today['mon']==$ay):
    $firstDay = getdate(mktime(0,0,0,$today['mon'],1,$today['year']));
    $lastDay  = getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));
  else:
    $carpan = $ay - $today['mon']; 
    $plusmonth = 2678400 * $carpan;
    $gelecekAy = time() + $plusmonth;

    $today    = getdate($gelecekAy);
    $firstDay = getdate(mktime(0,0,0,$today['mon'],1,$today['year']));
    $lastDay  = getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));
  endif;

/*echo "<pre>";
print_r($firstDay);
echo "</pre>";
echo "<pre>";
print_r($lastDay);
echo "</pre>";*/

/*<div class="event-desc">
          Mangal Keyfi
          <hr>
        </div>
        <div class="event-desc">
          Kenwood ile Temel Pastacılık 4 Haftalık 2. ders
        </div>*/

    /*if($today['mon'] == 1):

    else:*/
    $oncekiAyisim1 = date("F", mktime(null, null, null, $today['mon']-1, 1));
    $sonrakiAyisim1 = date("F", mktime(null, null, null, $today['mon']+1, 1));

    $oncekiAyisim = $gun_dizi[$oncekiAyisim1];
    $sonrakiAyisim = $gun_dizi[$sonrakiAyisim1];
    
    $oncekiay = $today['mon']-1;
    $oncekiyil = $today['year'];

    $sonrakiay = $today['mon']+1;
    $sonrakiyil = $today['year'];

    if($oncekiay<1):
      $oncekiay  = 12;
      $oncekiyil = $oncekiyil-1;
    endif;

    if($sonrakiay>12):
      $sonrakiay  = 1;
      $sonrakiyil = $sonrakiyil+1;
    endif;
    //endif;
    $bas = $yil."-".$ay."-".$firstDay['mday'];
    $bit = $yil."-".$ay."-".$lastDay['mday'];
    
    $worklist = aylik_workshop_liste($bas,$bit,"");

    
    echo '<div class="container">
        <div class="row">
          <div class="col-lg-2">
            <p class="aylist-geri"><span></span> <a href="workshop-takvimi.php?ay='.$oncekiay.'&yil='.$oncekiyil.'">'.$oncekiAyisim.'</a> </p>
          </div>
          <div class="col-lg-8">
              <h4 class="takvim-workshop text-center">Workshoplar</h4>
              <p class="takvim-workshop-ay text-center">'.$gun_dizi[$today['month']].' '.$today['year'].'</p>
          </div>
          <div class="col-lg-2">
            <p class="aylist-ileri text-right"><a href="workshop-takvimi.php?ay='.$sonrakiay.'&yil='.$sonrakiyil.'">'.$sonrakiAyisim.'</a> <span></span></p>
          </div>
        </div>
          <div class="seperator"></div>
        <div class="element">
          <div class="row">
            <div class="toolbox">
              <div class="t-item"> 
                <span class="toolbox-title">Takvim Görünümü</span> <a href="workshop-takvimi.php"><img src="img/workshop/liste-icon.png"></a>
              </div>
              <div class="t-item">
                <span class="toolbox-title">  Filtreler </span><a href="workshop-takvimi.php"><img src="img/workshop/filtre-icon.png"></a>
              </div>
              <div class="t-item">
                  <span class="toolbox-title">Tükenenleri Gizle </span>
                  <label class="switch"> 
                    <input type="checkbox" class="success">
                    <span class="slider round"></span>
                  </label>
              </div>
            </div>
            <div class="col-lg-12">';
            //$bugunsayi = date('d');
    // Create a table with the necessary header informations
                  $bugunsayi = strtotime(date('Y-m-d'));
                   echo ' <ul class="takvim-list">';

                   foreach ($worklist as $key => $v) {
                      $trh = strtotime(date($v["tarih"]));
                      echo ' <li>';
                                  if($bugunsayi>$trh):
                                  echo '<div class="cubuk-gri"></div>';
                                  else:
                                  echo '<div class="cubuk-red"></div>';  
                                  endif;
                                  echo '  <div class="takvim-list-alan">
                                      <div class="sola-dayali-tarih">
                                        <h2 class="text-center">'.$v['d'].'</h2>
                                        <h4 class="text-center">'.$gun_dizi[$today['month']].'</h4>
                                      </div>
                                      <a href="workshoplar-gunluk.php?work_id='.$v['id'].'"><div class="takvim-list-egitim">
                                      <h3 class="text-uppercase">'.$v['baslik'].'</h3>
                                      </div></a>
                                    </div>  
                                </li>';
                   }
                   echo '</ul>';

    echo '   </div>
          </div>
        </div>
    </div>';
}
function aylik_workshop_liste($bas,$bit,$catid){
if($catid):
   $eksql="catid='".$catid."' AND ";
 else:
  $eksql="";
endif;
$res = ORM::raw_execute("SELECT *,DAY(tarih) as d 
FROM  `workshop_takvim` 
WHERE $eksql
tarih
BETWEEN  '".$bas."'
AND  '".$bit."' order by tarih ");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }

  foreach ($rows as $key => $v) {
    $works[$v['d']] = $v;
  }

  return $works;
}
function aylik_workshop_liste_limit($bas,$bit,$limit){

$res = ORM::raw_execute("SELECT *,DAY(tarih) as d 
FROM  `workshop_takvim` 
WHERE tarih
BETWEEN  '".$bas."'
AND  '".$bit."' order by rand() limit 3");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }

  foreach ($rows as $key => $v) {
    $works[$v['d']] = $v;
  }

  return $works;
}

function getGunlukWorkshopDetay($work_id){
  $res = ORM::raw_execute("select * from workshop_takvim  where id = $work_id");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function workshop_hakkinda_getir(){
$res = ORM::raw_execute("select * from workshoplar_sss  order by sss_sira");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}

function pro_egitim_ara($word){
    $res = ORM::raw_execute("select * from pro_egitimler where egitim_ad like '%".$word."%' or egitim_aciklama like '%".$word."%' ");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}
function workshop_ara($word){
    $res = ORM::raw_execute("select * from workshoplar where egitim_ad like '%".$word."%' or egitim_aciklama like '%".$word."%' ");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}

function workshop_gunluk_ara($word){
    $res = ORM::raw_execute("select * from workshop_takvim where baslik like '%".$word."%' or aciklama like '%".$word."%' ");
    $statement = ORM::get_last_statement();
    $rows = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row; 
    }
    return $rows;
}

function arama_sonuclar($word){

$sonuc['pro_egitim'] = pro_egitim_ara($word);
$sonuc['workshop'] = workshop_ara($word);
$sonuc['work-takvim'] = workshop_gunluk_ara($word);

return $sonuc;

}

function hl($inp, $words)
{
  $replace=array_flip(array_flip($words)); // remove duplicates
  $pattern=array();
  foreach ($replace as $k=>$fword) {
     $pattern[]='/\b(' . $fword . ')(?!>)\b/i';
     $replace[$k]='<span class="highlight">$1</span>';
  }
  return preg_replace($pattern, $replace, $inp);
}

function highlight($text_highlight, $text_search) {
$str = preg_replace('#'. preg_quote($text_highlight) .'#i', '<span style="background-color:#FFFF66; color:#FF0000;">\\0</span>', $text_search);
return $str;
}

function highlight_word( $content, $word, $color ) {
    $replace = '<span style="background-color: ' . $color . ';">' . $word . '</span>'; // create replacement
    $content = str_replace( $word, $replace, $content ); // replace content

    return $content; // return highlighted data
}

function highlight_words( $content, $words, $colors ) {
    $color_index = 0; // index of color (assuming it's an array)

    // loop through words
    foreach( $words as $word ) {
        $content = highlight_word( $content, $word, $colors[$color_index] ); // highlight word
        $color_index = ( $color_index + 1 ) % count( $colors ); // get next color index
    }

    return $content; // return highlighted data
}


function get_img($url) {         
  $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';              
  $headers[] = 'Connection: Keep-Alive';         
  $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';         
  $user_agent = 'php';         
  $process = curl_init($url);         
  curl_setopt($process, CURLOPT_HTTPHEADER, $headers);         
  curl_setopt($process, CURLOPT_HEADER, 0);         
    curl_setopt($process, CURLOPT_USERAGENT, $user_agent); //check here         
    curl_setopt($process, CURLOPT_TIMEOUT, 30);         
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);         
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);         
    $return = curl_exec($process);         
    curl_close($process);         
    return $return;     
  }

  function save_img($url){
  //$filenameIn  = $_POST['text'];
    $filenameOut ='posts/' . basename($url);

    $contentOrFalseOnFailure   = file_get_contents($url);
    $byteCountOrFalseOnFailure = file_put_contents($filenameOut, $contentOrFalseOnFailure);

    if($byteCountOrFalseOnFailure){
      return true;
    }
    else{
      return false;
    }
  }

  function instagram_link_ekle($code){

    $source = file_get_contents("https://www.instagram.com/p/".$code);
//$source = file_get_contents("https://www.instagram.com/p/BaVppqXBfKd/");

    preg_match('/<script type="text\/javascript">window\._sharedData =([^;]+);<\/script>/', $source, $matches);

    if (!isset($matches[1]))
      return false;

    $r = json_decode($matches[1]);


    $data['username'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->owner->username;
    $data['display_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->display_url;
    $data['post_text'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->edge_media_to_caption->edges[0]->node->text;
    $data['profile_pic_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->owner->profile_pic_url;
    $data['like_count'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->edge_media_preview_like->count;
    $data['postimg_local_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->display_url;
    $data['profilepic_local_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->owner->profile_pic_url;
    $data['ip']="";

    return $data;
  }

function redirect($url){
   header("Location: ".$url);
   exit;
}

 function cleanInput($input) { 

  $search = array( 
      '@<script[^>]*?>.*?</script>@si', // Strip out javascript 
      '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags 
      '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly 
      '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments 
    ); 
  
  $output = preg_replace($search, '', $input); 
  return $output; 

}

function sanitize($input) { 
  
  if (is_array($input)) { 
    foreach($input as $var=>$val) { $output[$var] = sanitize($val); } 
  } else { 
    if (get_magic_quotes_gpc()) { $input = stripslashes($input); } 
    $input = cleanInput($input); 
    $output = mysql_real_escape_string($input); 
  } 
  return $output; 

}







function updateOrder($id_array){
  $count = 1;
  foreach ($id_array as $id){
      //$update = $this->db->query("UPDATE ".$this->imgTbl." SET img_order = $count WHERE id = $id");
    $count ++;    
  }
  return TRUE;
}


####################################################
function post_dbekle($data){

  $hashtagEkle = ORM::for_table('posts')->create();
  $hashtagEkle->username = $data['username'];
  $hashtagEkle->display_url = $data['display_url'];
  $hashtagEkle->post_text = $data['post_text'];
  $hashtagEkle->profile_pic_url = $data['profile_pic_url'];
  $hashtagEkle->like_count = $data['like_count'];
  $hashtagEkle->postimg_local_url = $data['postimg_local_url'];
  $hashtagEkle->profilepic_local_url = $data['profilepic_local_url'];
  $hashtagEkle->credate = date("Y-m-d H:i:s");
  $hashtagEkle->ip = $data['ip'];

  $hashtagEkle->save();

  return $hashtagEkle->id();
}

function post_getir($link){
  //$source = file_get_contents("https://www.instagram.com/p/BadY4LNlWQz");
  $source = file_get_contents($link);

  preg_match('/<script type="text\/javascript">window\._sharedData =([^;]+);<\/script>/', $source, $matches);

  if (!isset($matches[1]))
    return false;

  $r = json_decode($matches[1]);

  $data['username'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->owner->username;
  $data['display_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->display_url;
  $data['post_text'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->edge_media_to_caption->edges[0]->node->text;
  $data['profile_pic_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->owner->profile_pic_url;
  $data['like_count'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->edge_media_preview_like->count;
  $data['postimg_local_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->display_url;
  $data['profilepic_local_url'] = $r->entry_data->PostPage[0]->graphql->shortcode_media->owner->profile_pic_url;
  //$data['ip']="";

  return $data;
}