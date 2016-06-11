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
	$kadi=$row['kadi'];
	$cinsiyet=$row['cinsiyet'];
	$biyografi=$row['biyografi'];
	$e_posta=$row['e_posta'];
	require 'header.php';
}
?>
	<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-7 span3 centering aa">
		<table border="0" class="hepsi" height="300">
			<tr>
			<td>
				<ul>
			<li>Profili Düzenle</li>
			<li>Şifre Değiştir</li>
		</ul>
			</td>
				<td>
					<form action="islemler.php" method="post" enctype="multipart/form-data">
					<table border="0" class="bilgi">
						<tr>
							<td><input type="text" placeholder="Ad" class="form-control" name="ad_soyad" value="<?php echo $ad_soyad; ?>"></td>
						</tr>
						<tr>
							<td><input type="text" placeholder="E-Posta" class="form-control" name="e_posta" value="<?php echo $e_posta; ?>" ></td>
						</tr>
						<tr>
							<td><input type="text" placeholder="Kullanıcı Adı" class="form-control" name="kadi" value="<?php echo $kadi; ?>"></td>
						</tr>
						<tr>
							<td><input type="text" placeholder="Cinsiyet" class="form-control" name="cinsiyet" value="<?php echo $cinsiyet; ?>"></td>
						</tr>
						<tr>
							<td><textarea rows ="7" cols="70" placeholder="Biyografi" name="biyografi" style="padding:5px 0 0 20px;"><?php echo $biyografi; ?></textarea></td>
						</tr>
						<tr>
							<td><span class="btn btn-primary btn-file" style="margin-bottom:10px;">Profil Fotoğrafı Seç<input type="file" name="file" class=""></input></span></td>
						</tr>
						<tr>
							<td><input value="Kaydet" class="btn btn-primary" type="submit" name="bilgi_duzenle"></td>
						</tr>
					</table>
				</form>
				</td>
				<td>
					<form action="islemler.php" method="post" id="form2">
					<table border="0" class="sifre">
						<tr>
							<td><input type="password" placeholder="Eski Şifre" class="form-control e_sifre" name="e_sifre"></td>
						</tr>
						<tr>
							<td><input type="password" placeholder="Yeni Şifre" class="form-control y_sifre" name="y_sifre"></td>
						</tr>
						<tr>
							<td><input type="password" placeholder="Yeni Şifre Tekrarı" class="form-control yt_sifre" name="yt_sifre"></td>
						</tr>
						<tr>
							<td><input class="btn btn-primary" value="Şifre Değiştir" type="submit" name="sifre_duzenle" class="sifre_duzenle"></td>
						</tr>
					</table>
					</form>
				</td>
			</tr>
		</table>
		</div>
	</div>
</body>
</html>
