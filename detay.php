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
	
	$p_id = $_GET['id'];
	$query2 = $db->prepare("SELECT * FROM paylasilanlar WHERE p_id = :id");
	$query2->bindParam(":id",$p_id);
	$query2->execute();
	$row2 = $query2->fetch(PDO::FETCH_ASSOC);
	$resim = $row2['resim'];
	$aciklama = $row2['aciklama'];
	$hashtag = $row2['resim'];
	$first = convertDate($row2['zaman']);
	$second = convertDate(date('Y-m-d'));
	$dd = (($second - $first) / 86400)+1;
	$firstTime = convertTime($row2['zaman']);
	$lastTime = convertTime(date('H:i:s'));
	$td = (time() - strtotime($firstTime));
	$td_ = abs(round($td/60));
	$br = 'd';
	if($td_>60){
		$td_ = (round($td_/60));
		$br = 's';
	}
	if(abs(round(($td/60)/60)) >= 23){
		$td_ = (24*$dd)/24;
		$br = 'g';
	}
	
	/*if($td_ > 60){
		$td_ = abs(round($td_/60)*$dd);
		$br = 's';
	}*/
	require 'header.php';
}
?>
	<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-5 span3 centering aa">
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
			
			
			
			/*$query2 = $db->prepare("SELECT p.id,p.gonderi_id,COUNT(b.begenilen_id) AS count FROM begeniler b,paylasilanlar p WHERE b.begenilen_id = :id AND p.id = b.gonderi_id GROUP BY b.begenilen_id");
			$query2->bindParam(':id',$id);
			$query2->execute();*/
			$query4 = $db->prepare("SELECT * FROM kisiler WHERE id = :id");
			$query4->bindParam(':id',$row2['kisi_id']);
			$query4->execute();
			$row4 = $query4->fetch(PDO::FETCH_ASSOC);
			$query3 = $db->prepare("SELECT * FROM begeniler WHERE gonderi_id =".$p_id." AND begenilen_id = ".$row2['kisi_id']." AND begenen_id = ".$id."");
			$query3->execute();
			$query_like = $db->prepare("SELECT COUNT(*) AS count FROM begeniler WHERE gonderi_id = :gonderi_id AND begenilen_id = :begenilen_id");
			$query_like->bindParam(':gonderi_id',$p_id);
			$query_like->bindParam(':begenilen_id',$row2['kisi_id']);
			$query_like->execute();
			$row_like = $query_like->fetch(PDO::FETCH_ASSOC);
			$query_like_names = $db->prepare("SELECT k.*,b.* FROM kisiler as k,begeniler as b WHERE b.begenen_id = k.id AND b.gonderi_id = :gonderi_id");
			$query_like_names->bindParam(':gonderi_id',$p_id);
			$query_like_names->execute();
			$liked = '';
			while($row_like_names = $query_like_names->fetch(PDO::FETCH_ASSOC)){
				$liked .= ("<a href='profil.php?id=".$row_like_names['id'] ."'>".$row_like_names['kadi']."</a>") . ',';
			}
			$hashtag = '';
			$query_hashtag = $db->prepare("SELECT * FROM hashtag WHERE gonderi_id = :gonderi_id");
			$query_hashtag->bindParam(':gonderi_id',$p_id);
			//$query_hashtag->bindParam(':begenilen_id',$row['id']);
			$query_hashtag->execute();
			while($row_hashtag = $query_hashtag->fetch(PDO::FETCH_ASSOC)){
				$hashtag .= ("<a href='kesfet.php?hashtag=".$row_hashtag['hashtag']."'>#".$row_hashtag['hashtag']."</a>");
			}			
		?>
			<div class="gonderiler" ondblclick="$.begen('<?php echo $id; ?>','<?php echo $row2['kisi_id']; ?>','<?php echo $p_id ?>','')">
			<div class="dev-row">
				<div class="col-xs-6"><div class="ust"><img src="images/users/<?php echo $row4['profil_fotografi'];?>" class="profil_fotografi"> <a href="profil.php?id=<?php echo $row2['kisi_id']; ?>"><?php echo $row4['kadi'];?></a></div></div>
				<div class="col-xs-6"><div class="zaman" style="text-align:right;"><?php echo $td_.' '.$br;?></div></div>
			</div>
				<div class="a"><img src="images/shared/<?php echo $resim;?>" width="100%" style="margin-top:10px;"></div>
				<div class="begenme" style="margin-top:10px;"><span class="count"><?php echo $row_like['count'];?></span> Beğenme</div>
				<div class="begenenler"><?php echo $liked; ?></div>
				<div class="aciklama"><?php echo $aciklama;?></div>
				<div class="hashtag"><?php echo $hashtag;?></div><hr style="margin-top:10px;margin-bottom:5px;">
				<div class="yorumlar">
					<table border="0" class="yorumlar">
						<?php
						$query_comments = $db->prepare("SELECT k.*,y.* FROM kisiler as k,yorumlar as y WHERE (k.id = y.kisi_id) AND (y.gonderi_id = :gonderi_id) LIMIT 5");
						$query_comments->bindParam(':gonderi_id',$p_id);
						$query_comments->execute();
						while($row_comments = $query_comments->fetch(PDO::FETCH_ASSOC)){
						?>
						<tr>
							<td><?php echo '<a href=\'profil.php?id='.$row_comments['id'].'\'><img class=\'profil_fotografi\' src=\'images/users/'.$row_comments['profil_fotografi'].'\'> <b>'.$row_comments['kadi'].'</b></a> : '.$row_comments['yorum'];?></td>
						</tr>	
						<?php
						}
						?>
						<tr>
							<td><textarea rows="3" cols="100%" id = "yorum_yap" class="form-control yorum_yap" onkeydown="if(event.keyCode == 13) $.gonderiBul('<?php echo $p_id; ?>')"></textarea></td>
						</tr>
					</table>
				</div>
				<hr>
				<div class="kalp"><img src="images/logos/<?php if($query3->rowCount()>0) echo 'full-heart.png';else echo 'free-heart.png'; ?>" width="50"></div>
				<div class="yorumyap"></div>
			</div>
		</div>
	</div>
</body>
</html>
