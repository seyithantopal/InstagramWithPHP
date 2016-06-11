<?php
require_once 'config.php';
if(!isset($_COOKIE['id'])) header('location:index.php');
if(!isset($_GET['id'])) header('location:anasayfa.php');
else{
	$id = $_COOKIE['id'];
	$query = $db->prepare("SELECT * FROM kisiler WHERE id = :id");
	$query->bindParam(":id",$id);
	$query->execute();
	$row = $query->fetch(PDO::FETCH_ASSOC);
	$ad_soyad = $row['adsoyad'];
	
	$id_ = $_GET['id'];
	$query5 = $db->prepare("SELECT * FROM kisiler WHERE id = :id");
	$query5->bindParam(":id",$id_);
	$query5->execute();
	$row5 = $query5->fetch(PDO::FETCH_ASSOC);
	
	$ad_soyad_ = $row5['adsoyad'];
	$kadi = $row5['kadi'];
	$profil_foto = $row5['profil_fotografi'];
	$biyografi = $row5['biyografi'];
	$query2 = $db->prepare("SELECT COUNT(*) AS count FROM takipler WHERE edilen_id = :id");
	$query2->bindParam(":id",$id_);
	$query2->execute();
	$row2 = $query2->fetch(PDO::FETCH_ASSOC);
	$takipciler = $row2['count'];
	
	$query3 = $db->prepare("SELECT COUNT(*) AS count FROM takipler WHERE eden_id = :id");
	$query3->bindParam(":id",$id_);
	$query3->execute();
	$row3 = $query3->fetch(PDO::FETCH_ASSOC);
	$takip = $row3['count'];
	
	$query4 = $db->prepare("SELECT COUNT(*) AS count FROM paylasilanlar WHERE kisi_id = :id");
	$query4->bindParam(":id",$id_);
	$query4->execute();
	$row4 = $query4->fetch(PDO::FETCH_ASSOC);
	$gonderiler = $row4['count'];
	$query_liked = $db->prepare("SELECT * FROM takipler WHERE edilen_id = :edilen_id AND eden_id = :eden_id");
	$query_liked->bindParam(':eden_id',$id);
	$query_liked->bindParam(':edilen_id',$id_);
	$query_liked->execute();
	$count = $query_liked->rowCount();
	require 'header.php';
}
?>
	<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-7 span3 centering aa">
		<div class="shareBox">
			<table border="0" class="bilgiler">
				<tr>
					<td><img src="images/users/<?php echo $profil_foto;?>" class="profil_foto"></td>
					<td valign="top" style="padding:10px;">
						<div class="nickname"><?php echo $row5['kadi']; ?> <span style="display:<?php if($_COOKIE['id'] != $_GET['id']) echo 'none;'; ?>">- <a href="ayarlar.php"><input type="button" value="Profili Düzenle" class="btn btn-primary"></input></a></span> <span style="display:<?php if($_COOKIE['id'] == $_GET['id']) echo 'none;'; ?>">- <a href="mesajlar.php?id=<?php echo $id_;?>"><input type="button" value="Mesaj Gönder" class="btn btn-primary"></input></a></span><span style="<?php if($_COOKIE['id'] == $_GET['id']) echo 'display:none;';?>"> - <input type="button" value="<?php if($count == 1) echo 'Takibi bırak';else if($count == 0) echo 'Takip Et'?>" class="btn btn-primary follow_string" onclick="$.takip('<?php $eden_id =$_COOKIE['id']; echo $eden_id; ?>','<?php $edilen_id = $_GET['id'];echo $edilen_id; ?>','')"></input></span></div>
						<div class="name"><?php echo $ad_soyad_; ?> - <?php echo $biyografi; ?></div>
						<div class="follows"><?php echo $gonderiler; ?> Gönderi - <span class="takipciler"><?php echo $takipciler; ?></span> Takipçi - <?php echo $takip; ?> Takip</div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
		<div class="gonderiler">
			<div class="dev-row">
		<?php
		function convertDate($date_){
					$a_ = explode('-',$date_);
					$year = $a_[0];
					$month = $a_[1];
					$day = substr($a_[2],0,2);
					$date = strtotime($day.'.'.$month.'.'.$year);
					return $date;
				}
				function convertTime($time){
					$a = explode(':',$time);
					$m = $a[1];
					$s = $a[2];
					$h = substr($a[0],11) -1;
					$saat = ($h.':'.$m.':'.$s);
					return $saat;
				}
			//$query = $db->prepare("SELECT p.id,p.kisi_id,p.resim,p.zaman,p.aciklama,k.id,k.adsoyad,k.profil_fotografi,b.id,b.begenilen_id,b.begenen_id,b.gonderi_id,COUNT(b.begenilen_id) AS count FROM paylasilanlar as p,kisiler as k,begeniler b WHERE p.kisi_id = :id AND k.id = :id AND (b.begenilen_id = :id AND p.id = b.gonderi_id) GROUP BY b.begenilen_id");
			//$query = $db->prepare("SELECT p.id,p.kisi_id,p.resim,p.zaman,p.aciklama,k.id,k.adsoyad,k.profil_fotografi,h.gonderi_id,h.hashtag FROM paylasilanlar as p,kisiler as k,hashtag h WHERE p.kisi_id = :id AND k.id = :id OR h.gonderi_id = p.id ORDER BY p.id DESC"); Tüm paylaşımlar
			$query = $db->prepare("SELECT * FROM paylasilanlar WHERE kisi_id = :id ORDER BY p_id DESC");
			$query->bindParam(':id',$id_);
			$query->execute();
			/*$query2 = $db->prepare("SELECT p.id,p.gonderi_id,COUNT(b.begenilen_id) AS count FROM begeniler b,paylasilanlar p WHERE b.begenilen_id = :id AND p.id = b.gonderi_id GROUP BY b.begenilen_id");
			$query2->bindParam(':id',$id);
			$query2->execute();*/
			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$first = convertDate($row['zaman']);
				$second = convertDate(date('Y-m-d'));
				$dd = (($second - $first) / 86400) + 1;
				
				$firstTime = convertTime($row['zaman']);
				$lastTime = convertTime(date('H:i:s'));
				$td = (time() - strtotime($firstTime));
				$td_ = abs(round($td/60));
				$br = 'd';

		?>
			
				<a href="detay.php?id=<?php echo $row['p_id'];?>"><img src="images/shared/<?php echo $row['resim'];?>" width="30%" height="30%" style="margin-top:10px;padding:10px;"></a>
			
			<?php
			}
			?>
			</div>
			<input type="hidden" value="<?php echo $id_; ?>" class="edilen_id">
		</div>
	</div>
</body>
</html>
