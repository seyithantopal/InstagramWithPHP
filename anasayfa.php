<?php 
require_once 'config.php';
if(!isset($_COOKIE['id'])) header('location:index.php');
else{
	$id = $_COOKIE['id'];
	$query = $db->prepare("SELECT * FROM kisiler WHERE id = :id");
	$query->bindParam(":id",$id);
	$query->execute();
	$row = $query->fetch(PDO::FETCH_ASSOC);
	$ad_soyad = $row['adsoyad'];
	$kadi = $row['kadi'];
}
require 'header.php';
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-5 span3 centering aa">
		<div class="shareBox">
			<form action="islemler.php" method="post" enctype="multipart/form-data">
				<input type="text" name="aciklama" class="form-control" placeholder="Resme açıklama ekle">
				<span class="btn btn-primary btn-file">Resim Yükle<input type="file" name="file" class="file"></input></span>
				<input type="submit" class="btn btn-primary paylas" name="paylas" value="Paylaş">
			</form>
		</div>
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
			/*$query_paylasim = $db->prepare("SELECT * FROM paylasilanlar");
			$query_paylasim->execute();
			while($row_paylasim = $query_paylasim->fetch(PDO::FETCH_ASSOC)){
				$query_takip = $db->prepare("SELECT * FROM takipler WHERE (eden_id = :id AND edilen_id = :paylasim) OR (edilen_id = :id AND eden_id = :paylasim)");
				$query_takip->bindParam(':id',$id);
				$query_takip->bindParam(':paylasim',$row_paylasim['kisi_id']);
				$query_takip->execute();
				while($row_takip = $query_takip->fetch(PDO::FETCH_ASSOC)){
					echo $row_takip['eden_id'].'-';
				}
			}*/
			$hashtag = '#cide';
			$edilen_id = array();
			//$query = $db->prepare("SELECT p.*,k.*,b.*,h.*,t.* FROM paylasilanlar as p,kisiler as k,begeniler as b,hashtag as h,takipler as t WHERE (k.id = p.kisi_id) AND (p.kisi_id = :id) GROUP BY p.p_id ORDER BY p.p_id DESC");
			$query_takip_eden = $db->prepare("SELECT * FROM takipler WHERE eden_id = :id");
			$query_takip_eden->bindParam(':id',$id);
			$query_takip_eden->execute();
			while($row_takip_eden = $query_takip_eden->fetch(PDO::FETCH_ASSOC)){
				$edilen_id[] = $row_takip_eden['edilen_id'];
			}
			//$userId = "'".implode("','",$edilen_id)."'";
			$userId = implode(',',$edilen_id);
			$query = $db->prepare("SELECT p.*,k.*,b.*,h.* FROM paylasilanlar as p,kisiler as k,begeniler as b,hashtag as h WHERE (k.id = p.kisi_id) AND (p.kisi_id IN (".$userId.") OR (p.kisi_id = :id)) GROUP BY p.p_id ORDER BY p.p_id DESC");
			//$query->bindParam(':kisi_id',$userId);
			$query->bindParam(':id',$id);
			$query->execute();
			while($row = $query->fetch(PDO::FETCH_ASSOC)){

				$first = convertDate($row['zaman']);
				$second = convertDate(date('Y-m-d'));
				$dd = ($second - $first) / 86400;

				$firstTime = convertTime($row['zaman']);
				$lastTime = convertTime(date('H:i:s'));
				$td = (time() - strtotime($firstTime));
				$td_ = abs(round($td/60));
				$br = 'd';				
				$query2 = $db->prepare("SELECT * FROM begeniler WHERE gonderi_id =".$row['p_id']." AND begenilen_id = ".$row['id']." AND begenen_id = ".$id."");
				$query2->execute();
				$query_like = $db->prepare("SELECT COUNT(*) AS count FROM begeniler WHERE gonderi_id = :gonderi_id AND begenilen_id = :begenilen_id");
				$query_like->bindParam(':gonderi_id',$row['p_id']);
				$query_like->bindParam(':begenilen_id',$row['id']);
				$query_like->execute();
				$row_like = $query_like->fetch(PDO::FETCH_ASSOC);
				$query_like_names = $db->prepare("SELECT k.*,b.* FROM kisiler as k,begeniler as b WHERE b.begenen_id = k.id AND b.gonderi_id = :gonderi_id");
				$query_like_names->bindParam(':gonderi_id',$row['p_id']);
				$query_like_names->execute();
				$liked = '';
				while($row_like_names = $query_like_names->fetch(PDO::FETCH_ASSOC)){
					$liked .= ("<a href='profil.php?id=".$row_like_names['id'] ."'>".$row_like_names['kadi']."</a>") . ',';
				}
				$hashtag = '';
				$query_hashtag = $db->prepare("SELECT * FROM hashtag WHERE gonderi_id = :gonderi_id");
				$query_hashtag->bindParam(':gonderi_id',$row['p_id']);
				//$query_hashtag->bindParam(':begenilen_id',$row['id']);
				$query_hashtag->execute();
				while($row_hashtag = $query_hashtag->fetch(PDO::FETCH_ASSOC)){
					$hashtag .= ("<a href='kesfet.php?hashtag=".$row_hashtag['hashtag']."'>#".$row_hashtag['hashtag']."</a>");
				}
		?>
			<div class="gonderiler" ondblclick="$.begen('<?php echo $id; ?>','<?php echo $row['id']; ?>','<?php echo $row['p_id'] ?>','')">
			<div class="dev-row">
				<div class="col-xs-6"><div class="ust"><img src="images/users/<?php echo $row['profil_fotografi'];?>" class="profil_fotografi"> <a href="profil.php?id=<?php echo $row['id']; ?>"><?php echo $row['kadi'];?></a></div></div>
				<div class="col-xs-6"><div class="zaman" style="text-align:right;"><?php echo $td_.' '.$br;?></div></div>
			</div>
				<div class="a"><img src="images/shared/<?php echo $row['resim'];?>" width="100%" style="margin-top:10px;"></div>
				<div class="begenme" style="margin-top:10px;"><span class="count"><?php echo $row_like['count'];?></span> Beğenme</div>
				<div class="begenenler"><?php echo $liked; ?></div>
				<div class="aciklama"><?php echo $row['aciklama'];?></div>
				<div class="hashtag"><?php echo $hashtag;?></div><hr style="margin-top:10px;margin-bottom:5px;">
				<div class="yorumlar">
					<table border="0" class="yorumlar">
						<?php
						$query_comments = $db->prepare("SELECT k.*,y.* FROM kisiler as k,yorumlar as y WHERE (k.id = y.kisi_id) AND (y.gonderi_id = :gonderi_id) LIMIT 5");
						$query_comments->bindParam(':gonderi_id',$row['p_id']);
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
							<td><textarea rows="3" cols="100%" id = "yorum_yap" class="form-control yorum_yap" onkeydown="if(event.keyCode == 13) $.gonderiBul('<?php echo $row['p_id']; ?>')"></textarea></td>
						</tr>
					</table>
				</div>
				<hr>
				<div class="kalp"><img src="images/logos/<?php if($query2->rowCount()>0) echo 'full-heart.png';else echo 'free-heart.png'; ?>" width="50"></div>
				<div class="yorumyap"></div>
			</div>
			<?php			
			}
			?>
		</div>
	</div>
	</div>
</body>
</html>
