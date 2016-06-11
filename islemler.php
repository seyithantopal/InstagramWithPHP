<?php
require 'config.php';
require 'class.phpmailer.php';

if(isset($_POST['giris'])){
	$kadi = $_POST['username'];
	$sifre = $_POST['password'];
	$query = $db->prepare("SELECT * FROM kisiler WHERE kadi=:kadi AND sifre=:sifre");
	$query->bindParam(':kadi',$kadi,PDO::PARAM_STR);
	$query->bindParam(':sifre',$sifre,PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 0){
		$row = $query->fetch(PDO::FETCH_ASSOC);
		setcookie('id',$row['id'],time()+(24*60*60));
		header('location:anasayfa.php');
	}else header('location:index.php');
}
if(isset($_POST['kaydol'])){
	$kadi = $_POST['kadi'];
	$eposta = $_POST['eposta'];
	$sifre = $_POST['sifre'];
	$adsoyad = $_POST['ad_soyad'];
	$query2 = $db->prepare("SELECT * FROM kisiler WHERE kadi=:username");
		$query2->bindParam(':username',$kadi,PDO::PARAM_STR);
		$query2->execute();
		if($query2->rowCount() == 0){
			$query = $db->prepare("INSERT INTO kisiler VALUES ('',:eposta,:adsoyad,:kadi,:sifre,'','','')");
			$query ->bindParam(':adsoyad',$adsoyad,PDO::PARAM_STR);
			$query ->bindParam(':kadi',$kadi,PDO::PARAM_STR);
			$query ->bindParam(':eposta',$eposta,PDO::PARAM_STR);
			$query ->bindParam(':sifre',$sifre,PDO::PARAM_STR);
			$query->execute();
			$error = $query->errorInfo();
			echo $error[2];
			header("location:index.php");
		}
}
if(isset($_POST['paylas'])){
	$aciklama = $_POST['aciklama'];	
	$uzantilar = array('jpg','png','gif');
	$resimAdi = $_FILES['file']['name'];
	$uzanti = substr($_FILES['file']['type'],6);
	$dizin = 'images/shared/'.$resimAdi;
	move_uploaded_file($_FILES['file']['tmp_name'],$dizin);
	$id = $_COOKIE['id'];
	$h_pos = strpos($aciklama,' ');
	$aciklama_ = substr($aciklama,$h_pos);
	$query = $db->prepare("INSERT INTO paylasilanlar VALUES ('',:id,:resim,now(),:aciklama)");
	$query->bindParam(':id',$id);
	$query->bindParam(':resim',$resimAdi);
	$query->bindParam(':aciklama',$aciklama_);
	$query->execute();
	$hashtag_ = explode('#',$aciklama);
	$lastID = $db->lastInsertId();
	for($i = 1;$i<count($hashtag_);$i++){
		$hashtag = $hashtag_[$i];
		$query = $db->prepare("INSERT INTO hashtag VALUES ('',:gonderi_id,:hashtag)");
		$query->bindParam(':gonderi_id',$lastID);
		$query->bindParam(':hashtag',$hashtag);
		$query->execute();
	}
	header('location:anasayfa.php');
}
if(isset($_POST['tkp_blank'])){
	$query_follow = $db->prepare("SELECT * FROM takipler WHERE edilen_id = :edilen_id AND eden_id = :eden_id");
	$query_follow->bindParam(':eden_id',$_POST['eden_id']);
	$query_follow->bindParam(':edilen_id',$_POST['edilen_id']);
	$query_follow->execute();
	$count = $query_follow->rowCount();
	if($count == 1) $do_follow = $db->prepare("DELETE FROM takipler WHERE eden_id = :eden_id AND edilen_id = :edilen_id");
	else $do_follow = $db->prepare("INSERT INTO takipler VALUES ('',:edilen_id,:eden_id)");
	$do_follow->bindParam(':eden_id',$_POST['eden_id']);
	$do_follow->bindParam(':edilen_id',$_POST['edilen_id']);
	$do_follow->execute();
	echo $count;
}
function sendMail($email,$nameSurname,$password){
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->Username = 'info.instagramm@gmail.com';
	$mail->Password = 'instagraminfo123';
	$mail->SetFrom($mail->Username,$nameSurname);
	$mail->CharSet = 'UTF-8';
	$mail->AddAddress($email);
	$mail->Subject = 'Şifre Yenileme';
	$content = 'Şifre :'.$password;
	$mail->MsgHTML($content);
	if($mail->Send()){
		echo 'E-Posta Gönderildi';
	}
	else echo $mail->ErrorInfo();
}


if(isset($_POST['blank'])){
	$query2 = $db->prepare("SELECT * FROM begeniler WHERE gonderi_id = :gonderi_id AND begenilen_id = :begenilen_id AND begenen_id = :begenen_id");
	$query2->bindParam(':begenen_id',$_POST['begenen_id']);
	$query2->bindParam(':begenilen_id',$_POST['begenilen_id']);
	$query2->bindParam(':gonderi_id',$_POST['gonderi_id']);
	$query2->execute();
	
	if($query2->rowCount() == 0) $query = $db->prepare("INSERT INTO begeniler VALUES ('',:begenen_id,:begenilen_id,:gonderi_id)");
	else if($query2->rowCount() > 0) $query = $db->prepare("DELETE FROM begeniler WHERE begenen_id = :begenen_id AND begenilen_id = :begenilen_id AND gonderi_id = :gonderi_id");

	$query->bindParam(':begenen_id',$_POST['begenen_id']);
	$query->bindParam(':begenilen_id',$_POST['begenilen_id']);
	$query->bindParam(':gonderi_id',$_POST['gonderi_id']);
	$query->execute();
}
if(isset($_POST['msg_blank'])){
	$query = $db->prepare("INSERT INTO mesajlar VALUES ('',:gonderen_id,:gonderilen_id,:mesaj,now())");
	$query->bindParam(':gonderen_id',$_POST['gonderen_id']);
	$query->bindParam(':gonderilen_id',$_POST['gonderilen_id']);
	$query->bindParam(':mesaj',$_POST['mesaj']);
	$query->execute();
}
if(isset($_POST['yrm_blank'])){
	$query = $db->prepare("INSERT INTO yorumlar VALUES ('',:kisi_id,:gonderi_id,:yorum,now())");
	$query->bindParam(':kisi_id',$_POST['kisi_id']);
	$query->bindParam(':gonderi_id',$_POST['gonderi_id']);
	$query->bindParam(':yorum',$_POST['yorum']);
	$query->execute();
	echo 'aa';
}
if(isset($_POST['msg_goster'])){
	$query = $db->prepare("SELECT m.*,k.* FROM mesajlar as m,kisiler as k WHERE (m.gonderen_id = :gonderen_id OR m.gonderen_id = :gonderilen_id) AND (m.gonderilen_id = :gonderen_id OR m.gonderilen_id = :gonderilen_id) AND (m.gonderen_id = k.id) ORDER BY m.id DESC");
	$query->bindParam(':gonderen_id',$_POST['gonderen_id']);
	$query->bindParam(':gonderilen_id',$_POST['gonderilen_id']);
	$query->execute();
	$aa="<?xml version='1.0' encoding='utf-8'?>
<users>";
	header('Content-type: text/xml');
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$aa.="
   <person>
	  <id>".$row['id']."</id>
      <kadi>".$row['kadi']."</kadi>
      <mesaj>".$row['mesaj']."</mesaj>
      <photo>".$row['profil_fotografi']."</photo>
      <datetime>".$row['tarih']."</datetime>
   </person>";
	}
	echo $aa.="</users>";
}
if(isset($_POST['yrm_goster'])){
	$query = $db->prepare("SELECT k.*,y.* FROM kisiler as k,yorumlar as y WHERE (k.id = y.kisi_id) AND (y.gonderi_id = :gonderi_id) LIMIT 5");
	$query->bindParam(':gonderi_id',$_POST['gonderi_id']);
	$query->execute();
	$aa="<?xml version='1.0' encoding='utf-8'?>
<users>";
	header('Content-type: text/xml');
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$aa.="
   <person>
	  <id>".$row['id']."</id>
      <kadi>".$row['kadi']."</kadi>
      <yorum>".$row['yorum']."</yorum>
   </person>";
	}
	echo $aa.="</users>";
}

if(isset($_POST['blank2'])){
	$query2 = $db->prepare("SELECT * FROM begeniler WHERE begenilen_id = :begenilen_id ORDER BY b_id DESC");
	$query2->bindParam(':begenilen_id',$_POST['begenilen_id']);
	$query2->execute();
	$aa="<?xml version='1.0' encoding='utf-8'?>
<users>";
	header('Content-type: text/xml');
	while($row = $query2->fetch(PDO::FETCH_ASSOC)){
		$query3 = $db->prepare("SELECT * FROM kisiler WHERE id = :begenen_id");
		$query3->bindParam(':begenen_id',$row['begenen_id']);
		$query3->execute();
		while($row2 = $query3->fetch(PDO::FETCH_ASSOC)){
			$aa.="
   <person>
      <id>".$row2['id']."</id>
      <name>".$row2['kadi']."</name>
      <photo>".$row2['profil_fotografi']."</photo>
	  <shared>".$row['gonderi_id']."</shared>
   </person>";	
		}
	}
	echo $aa.="</users>";
}

if(isset($_POST['yrm_goster_blank'])){
	$query2 = $db->prepare("SELECT * FROM yorumlar WHERE kisi_id = :begenilen_id ORDER BY b_id DESC");
	$query2->bindParam(':begenilen_id',$_POST['begenilen_id']);
	$query2->execute();
	$aa="<?xml version='1.0' encoding='utf-8'?>
<users>";
	header('Content-type: text/xml');
	while($row = $query2->fetch(PDO::FETCH_ASSOC)){
		$query3 = $db->prepare("SELECT * FROM kisiler WHERE id = :begenen_id");
		$query3->bindParam(':begenen_id',$row['begenen_id']);
		$query3->execute();
		while($row2 = $query3->fetch(PDO::FETCH_ASSOC)){
			$aa.="
   <person>
      <id>".$row2['id']."</id>
      <name>".$row2['kadi']."</name>
      <photo>".$row2['profil_fotografi']."</photo>
	  <shared>".$row['gonderi_id']."</shared>
   </person>";	
		}
	}
	echo $aa.="</users>";
}
if(isset($_POST['yenile'])){
	$email=$_POST['email'];
	$query = $db->prepare("SELECT * FROM kisiler WHERE e_posta = :mail");
	$query->bindParam(':mail',$email);
	$query->execute();
	if($query->rowCount() != 0)
	{
		$row = $query->fetch(PDO::FETCH_ASSOC);
		sendMail($email,$row['adsoyad'],$row['sifre']);
	}
	else echo 'Sistemde böyle bir E-Posta tanımlı değil';
}
if(isset($_POST['bilgi_duzenle'])){
	$id=$_COOKIE['id'];
	$ad_soyad=$_POST['ad_soyad'];
	$kadi = $_POST['kadi'];
	$cinsiyet = $_POST['cinsiyet'];
	$biyografi=$_POST['biyografi'];
	$e_posta=$_POST['e_posta'];
	$profil_fotografi = '';
	if($_FILES['file']['name'] != ''){
		$query = $db->prepare("UPDATE kisiler SET adsoyad=:adsoyad,e_posta=:e_posta,kadi=:kadi,cinsiyet=:cinsiyet,biyografi=:biyografi,profil_fotografi = :profil_fotografi WHERE id=:id");
		$resimAdi = $_FILES['file']['name'];
		$query ->bindParam(':profil_fotografi',$resimAdi,PDO::PARAM_STR);
		$uzanti = substr($_FILES['file']['type'],6);
		$dizin = 'images/users/'.$resimAdi;
		move_uploaded_file($_FILES['file']['tmp_name'],$dizin);
	}else{
		$query = $db->prepare("UPDATE kisiler SET adsoyad=:adsoyad,e_posta=:e_posta,kadi=:kadi,cinsiyet=:cinsiyet,biyografi=:biyografi WHERE id=:id");
	}
		$query ->bindParam(':id',$id,PDO::PARAM_STR);
		$query ->bindParam(':adsoyad',$ad_soyad,PDO::PARAM_STR);
		$query ->bindParam(':kadi',$kadi,PDO::PARAM_STR);
		$query ->bindParam(':cinsiyet',$cinsiyet,PDO::PARAM_STR);			
		$query ->bindParam(':biyografi',$biyografi,PDO::PARAM_STR);			
		$query ->bindParam(':e_posta',$e_posta,PDO::PARAM_STR);		
		$query->execute();
		header("location:ayarlar.php");
			
}
if(isset($_POST['ara'])){
	$icerik = $_POST['ara'];
	$query = $db->prepare("SELECT * FROM kisiler WHERE kadi LIKE '".$icerik."%'");
	$query->execute();
	//$row = $query->fetch(PDO::FETCH_ASSOC);
	$aa="<?xml version='1.0' encoding='utf-8'?>
<users>";
	header('Content-type: text/xml');
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$aa.="
   <person>
      <id>".$row['id']."</id>
      <name>".$row['kadi']."</name>
      <photo>".$row['profil_fotografi']."</photo>
   </person>";
	}
	echo $aa.="</users>";
}
if(isset($_POST['sifre_duzenle'])){
	$id=$_COOKIE['id'];
	$e_sifre=$_POST['e_sifre'];
	$y_sifre=$_POST['y_sifre'];
	$yt_sifre=$_POST['yt_sifre'];
	$query = $db->prepare("UPDATE kisiler SET sifre=:sifre WHERE id=:id AND sifre=:e_sifre");
			$query ->bindParam(':id',$id,PDO::PARAM_STR);
			$query ->bindParam(':e_sifre',$e_sifre,PDO::PARAM_STR);
			$query ->bindParam(':sifre',$y_sifre,PDO::PARAM_STR);
			$query->execute();
			if($query->rowCount() == 0) echo 'Eski şifreniz yanlış';
			else header("location:ayarlar.php");
}	

if(isset($_GET['exit'])){
	setcookie('id','',time()-1);
	header('location:index.php');
}
?>

