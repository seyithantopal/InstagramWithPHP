<?php
require_once 'config.php';
if(!isset($_COOKIE['id'])) header('location:index.php');
if($_GET['id'] == $_COOKIE['id']) header('location:anasayfa.php');
else{
	$id_ = $_GET['id'];
	$query_ = $db->prepare("SELECT * FROM kisiler WHERE id = :id");
	$query_->bindParam(':id',$id_);
	$query_->execute();
	$row_ = $query_->fetch(PDO::FETCH_ASSOC);
	$ad_soyad_ = $row_['adsoyad'];
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
		<div class="col-xs-8 span3 centering aa">
		<h3><?php echo $ad_soyad_ ?></h3><hr>
			<div class="messageBox" style="border:1px solid #aaa;height:600px;margin-bottom:15px;padding:20px;overflow-y:scroll;">
			
				
			
			</div>
			<textarea rows="7" cols="70" placeholder="Mesajınızı yazınız" class="message"></textarea>
		</div>
		<input type="hidden" value="<?php echo $_GET['id']; ?>" class="gonderilen_id">
		<input type="hidden" value="<?php echo $id; ?>" class="gonderen_id">
		<script>
			$(document).ready(function(){
				$(".message").focus();
				var gonderilen_id = $(".gonderilen_id").val();
				var gonderen_id = $(".gonderen_id").val();
				$.mesaj_goster(gonderen_id,gonderilen_id,'');
			});
		</script>
	</div>
</div>	