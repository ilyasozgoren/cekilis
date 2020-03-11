<?php require ('header.php'); ?>
<?php

$cekilisid = $_GET['cid'];
$all = $_GET['all'];
$section = $_GET['section'];
$ckontrol = cekilis_kontrol($cekilisid);

/*cekilise_katilamayanlar(1,5,0);
exit;*/

?>
        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-12">                    
                    <button class="btn btn-success float-right" id="btn-draw">ÇEKİLİŞİ GERÇEKLEŞTİR</button>
                    <!--<button class="btn btn-primary float-right mr-3" id="btn-load">Liste Yükle</button>-->
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <input type="hidden" name="select-range-1" id="select-range-1" value="6">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <?php
                        $klist = katilimci_list($section,$cekilisid,$all);
                        echo '<label for="input-list">Çekiliş Listesi : '.count($klist).'</label>';
                        foreach ($klist as $key => $v) {
                            $k.=$v['id'].' - '.$v['adsoyad'].'&#13;&#10;';
                        }
                        ?>
                        <textarea class="form-control" id="input-list" rows="90" placeholder="* Her Satır 1 Kişiyi Temsil Etmektedir !"><?php echo $k;?></textarea>
                    </div>
                </div>

            </div>
            <div class="row row-prin mt-3 d-none">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Kazanan Listesi</div>
                        <div class="card-body">
                            <div class="badge-prin-list"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-repl mt-3 d-none">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">YEDEK Kazanan Listesi</div>
                        <div class="card-body">
                            <div class="badge-repl-list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require ('footer.php'); ?>