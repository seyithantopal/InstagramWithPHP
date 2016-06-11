<?php
$id = $_COOKIE['id'];
$query = $db->prepare("SELECT * FROM kisiler WHERE id = :id");
$query->bindParam(':id',$id);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
$kadi = $row['kadi'];
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <script src="jquery.min.js"></script>
  <script type="text/javascript" src="modernizr.js"></script>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
	<script src="bootstrap/js/ajax.js"></script>
	<script src="js/javascript.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/font-awesome.css">
	<script src="bootstrap/js/ajax.js"></script>
	<script src="js/min/jquery-v1.10.2.min.js" type="text/javascript"></script>
    <script src="js/min/modernizr-custom-v2.7.1.min.js" type="text/javascript"></script>
    <script src="js/min/hammer-v2.0.3.min.js" type="text/javascript"></script>
</head>
<style>
*
{
	margin:0;
	padding:0;
}
.hepsi ul li{
	margin-bottom:30px;
	cursor:pointer;
}
ul li{
	list-style-type:none;
}

.bilgi .form-control,.sifre .form-control{
	padding:20px;
	margin-bottom:15px;
}
.tarih{
	float:right;
}
.bilgi,.sifre{
	width:500px;
	margin-left:10px;
	margin-right:10px;
}
.ara{
	margin-top:10px;
}
.name i{
	margin-left:30px;
}

.container-fluid{
  height:100%;
  display:table;
  width: 100%;
  padding: 0;
}

.row-fluid {height: 100%; display:table-cell; vertical-align: middle;}

.centering {
  float:none;
  margin:0 auto;
}

.aa{
	margin-top:70px;
}
.listeler table{
	margin-top:6px;
	margin-bottom:10px;
}
.bildirimler table{
	margin-top:5px;
	margin-bottom:10px;
}
.message{
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	-o-border-radius:4px;
	-webkit-box-shadow:0 0 5px #1762ca;
	border-radius:4px;
	width:100%;
	padding:5px 0 0 5px;
	
}
.profil_ad{
	font:21px Arial;
	margin-left:120px;
}
.name a{
	color:#fff;
}
.profil_fotografi{
	width:30px;
	height:30px;
	-webkit-border-radius:50%;
	-moz-border-radius:50%;
	-o-border-radius:50%;
	border-radius:50%;
}
.gonderiler{
	border:1px solid #AAA;
	padding:10px;
	margin-bottom:20px;
	}
.shareBox{
	border:1px solid #AAA;
	margin-bottom:10px;
	padding:20px;
}
.shareBox input[type=submit]{
	margin-top:10px;
}
.btn-file {
	margin-top:10px;
		position: relative;
		overflow: hidden;
		}
		.btn-file input[type=file] {
			position: absolute;
			top: 0;
			right: 0;
			min-width: 100%;
			min-height: 100%;
			font-size: 100px;
			text-align: right;
			filter: alpha(opacity=0);
			opacity: 0;
			outline: none;
			background: white;
			cursor: inherit;
			display: block;
		}
		
.a img {
	
}
.profil_fotografi{
	width:30px;
	height:30px;
	-webkit-border-radius:50%;
	-moz-border-radius:50%;
	-o-border-radius:50%;
	border-radius:50%;
}
.profil_foto{
	width:160px;
	height:160px;
	-webkit-border-radius:50%;
	-moz-border-radius:50%;
	-o-border-radius:50%;
	border-radius:50%;
}
.nickname{
	margin-top:15px;
}
.bilgiler{
	font:18px Arial;
}
.yorumlar td{
	padding-top:10px;
}
.name{
	margin-top:15px;
}
.follows{
	margin-top:15px;
}
.header{
		position:fixed;
		z-index:9999999999;
}
@media screen and (max-width:780px){
			.aa{
				width:90%;
			}
			.name a{
				font-size:16px;
			}
			#listeler,#bildirimler{
				position:fixed;
			}
		}
</style>
<script>
$(document).ready(function(){
	$.begen = function(begenen_id,begenilen_id,gonderi_id,blank){
		//alert(gonderi_id);
		$.ajax({
				type:"POST",
				url:"islemler.php",
				data:{begenen_id,begenilen_id,gonderi_id,blank},
				success:function(suc){
					
				}
			});
	}
	$.takip = function(eden_id,edilen_id,tkp_blank){
		$.ajax({
			type:'POST',
			url:'islemler.php',
			data:{eden_id,edilen_id,tkp_blank},
			success:function(suc){
				var count = suc.trim();
				var follow_count = parseInt($('.takipciler').text());
				var follow_string_ = $('.follow_string').val().trim();
				var follow_string = '';
				if(count == 1){
					follow_count--;
					follow_string = 'Takip et';
				}
				else{
					follow_count++;
					follow_string = 'Takibi bırak';
				}
				$('.takipciler').text(follow_count);
				$('.follow_string').val(follow_string);
			}
		});
	}
	$.yorum_yap = function(kisi_id,gonderi_id,yorum,yrm_blank){
		//alert(gonderi_id);
		$.ajax({
				type:"POST",
				url:"islemler.php",
				data:{kisi_id,gonderi_id,yorum,yrm_blank},
				success:function(suc){
					if(window.location.pathname.substring(11).trim() == "anasayfa.php") window.location = "anasayfa.php";
					else{
						var point = window.location.href.indexOf('=') + 1;
						var id = window.location.href.substring(point);
						window.location = 'detay.php?id='+id;
					}
				}
			});
	}
	$.mesaj_gonder = function(gonderen_id,gonderilen_id,mesaj,msg_blank){
		
		$.ajax({
				type:"POST",
				url:"islemler.php",
				data:{gonderen_id,gonderilen_id,mesaj,msg_blank},
				success:function(suc){
					//window.location.href="mesajlar.php?id="+ gonderilen_id +"";
				}
			});
	}
	$.mesaj_goster = function(gonderen_id,gonderilen_id,msg_goster){
		$.ajax({
				type:"POST",
				url:"islemler.php",
				data:{gonderen_id,gonderilen_id,msg_goster},
				dataType:"xml",
						success:function(data){
							$(".mesajlar").remove();
							$(".tarih").remove();
							$(".ayrac").remove();
							$(data).find("person").each(function(){
								var photo = $(this).find("photo").text();
								var mesaj = $(this).find("mesaj").text();
								var kadi = $(this).find("kadi").text();
								var id = $(this).find("id").text();
								var tarih = $(this).find("datetime").text();
								var veri = "<table border='0' class='mesajlar'><tr>";
								veri += "<td><img src='images/users/"+ photo +"' class='profil_fotografi' id='profil'></td>";
								veri += "<td><div style='margin-left:10px;font:18px Arial;'><a href='profil.php?id="+ id +"'>"+ kadi +"</a></div></td>";
								veri += "</tr><td colspan = '2' style='padding-top:20px;padding-bottom:-10px;font-size:17px;'>"+  mesaj +"</td><span class='tarih' style='font-style:italic;'>"+ tarih +"</span></table><hr class='ayrac'>";
								$(".messageBox").append(veri);
							});
						}
			});		
	}
	setInterval(function(){
		var gonderilen_id = $(".gonderilen_id").val();
		var gonderen_id = $(".gonderen_id").val();
		$.mesaj_goster(gonderen_id,gonderilen_id,'');
		//alert("aa");		
	},1000);
	
	$(".message").keydown(function(e){
		if(e.keyCode == 13){
			if($('.message').val().trim() == '') {
				e.preventDefault();
				alert('Lütfen boş bırakmayınız');
			}
			else{
				var gonderilen_id = $(".gonderilen_id").val();
				var gonderen_id = $(".gonderen_id").val();
				var mesaj = $(".message").val();
				$.mesaj_gonder(gonderen_id,gonderilen_id,mesaj,'');
				$('.message').val('');
			}
		}
	});
	var gonderi_id_ = "";
	$.gonderiBul = function(gonderi_id){
		gonderi_id_ = gonderi_id;
	}
	$('.yorum_yap').keydown(function(e){
		if(e.keyCode == 13){
			var index = $(".yorum_yap").index(this);
			var yorum = $(".yorum_yap:eq("+ index +")").val();
			var kisi_id = $(".kisi_id").val();
			$.yorum_yap(kisi_id,gonderi_id_,yorum,'');
		}
	});
	$.bildirim_goster = function(begenilen_id,blank2){
		//alert(gonderi_id);
		$.ajax({
				type:"POST",
				url:"islemler.php",
				data:{begenilen_id,blank2},
				dataType:"xml",
						success:function(data){
							$(".user-ranking").hide(1);
							$(data).find("person").each(function(){
								var id = $(this).find("id").text();
								var photo = $(this).find("photo").text();
								var name = $(this).find("name").text();
								var veri = "<table border='0' class='user-ranking'><tr>";
								var gonderi = $(this).find("shared").text();
								veri += "<td><img src='images/users/"+ photo +"' class='profil_fotografi' id='profil'></td>";
								veri += "<td><div style='margin-left:10px;font:18px Arial;'><a href='profil.php?id="+ id +"'>"+ name +"</a> senin <a href='detay.php?id="+ gonderi +"'>fotoğrafını</a> beğendi</div></td>";
								//veri += "<td style='padding-left:5px;padding-right:5px;'><div class='ranking-name'>"+ name +"</div><hr class='ranking-name-hr'><a onclick='$.findID("+ id +")' class='link'>Ders İsteği Gönder</a><br /><a href='#' class='link'>Randevu Al</a></td>";
								veri += "</tr></table>";
								//$("table").append(veri);
								$(".bildirimler").append(veri);
							});
						}
			});
	}
	$(document).scroll(function(){
		$(".listeler").hide();
		$(".bildirimler").hide();
		$(".user-ranking").hide();
	});
	$(".listeler").hide();
	$(".bildirimler").hide();
	$('html').click(function (e) {
    if (e.target.id == 'listeler' || e.target.id == 'ara' || e.target.id == 'profil' || e.target.id == 'bars' || e.target.id == 'bildirimler') {
        
    } else {
        $(".listeler").hide();
		$(".user-ranking").hide();
		$(".bildirimler").hide();
    }
});
	$(".ara").keydown(function(e){
		if(e.keyCode == 13){
			e.preventDefault();
			if($(".ara").val() != '') var values = $("#form_ara").serialize();
			$.ajax({
				type:"POST",
				url:"islemler.php",
				data:values,
				dataType:"xml",
						success:function(data){
							//$(".user-ranking").html(data);
							$(".user-ranking").hide(1);
							$(data).find("person").each(function(){
								var id = $(this).find("id").text();
								var photo = $(this).find("photo").text();
								var name = $(this).find("name").text();
								var veri = "<table border='0' class='user-ranking'><tr>";
								veri += "<td><img src='images/users/"+ photo +"' class='profil_fotografi' id='profil'></td>";
								veri += "<td><a href='profil.php?id="+ id +"'><div style='margin-left:10px;font:18px Arial;'>"+ name +"</div></a></td>";
								//veri += "<td style='padding-left:5px;padding-right:5px;'><div class='ranking-name'>"+ name +"</div><hr class='ranking-name-hr'><a onclick='$.findID("+ id +")' class='link'>Ders İsteği Gönder</a><br /><a href='#' class='link'>Randevu Al</a></td>";
								veri += "</tr></table>";
								//$("table").append(veri);
								$(".listeler").append(veri);
							});
						}
			});
	}
				});
				$("#form2").submit(function(){
	var e_sifre = $(".e_sifre").val();
	var y_sifre = $(".y_sifre").val();
	var yt_sifre = $(".yt_sifre").val();
	if(e_sifre == "" || y_sifre == "" || yt_sifre == ""){
		alert("Boş alanları doldurunuz");
		return false;
	}else{
		if(yt_sifre != y_sifre){
			alert("Uyumsuz şifre");
			return false;
		}
	}
});
$(".sifre").hide();
$("ul li:eq(0)").click(function(){
	$(".sifre").hide();
	$(".bilgi").show();
});
$("ul li:eq(1)").click(function(){
	$(".sifre").show();
	$(".bilgi").hide();
});
var a = $(".gonderiler").width();
$(".ara").focus(function(){
	$(".listeler").show();
});
$(".bars").click(function(){
	var id = $('.kisi_id').val();
	$.bildirim_goster(id,'a');
	$(".bildirimler").show();
});

$(".gonderiler").dblclick(function(){
	 /*var self   = $(this),
      index  = self.index(),
      text   = self.text();

		alert(text + ' ' + index);*/
	var new_like,new_like_name;
	var index = ($(this).index()-1);
	//alert(index);
	var name_ = $(".kalp img:eq("+ index +")").attr('src');
	var name = name_.substring(13);
	var like_index = ($(this).index()-1);
	var like = parseInt($(".count:eq("+ index +")").text(),10);
	var like_name_index = ($(this).index()-1);
	var like_name = $(".begenenler:eq("+ index +")").text();
	var liked_name = $(".kisi_ad").val();
	var liked_name_index,lp;
	if(name == 'free-heart.png') {
		$(".kalp img:eq("+ index +")").attr('src','images/logos/full-heart.png');
		new_like = like + 1;
		new_like_name = ((like_name += liked_name) + ',');
	}
	else {
		$(".kalp img:eq("+ index +")").attr('src','images/logos/free-heart.png');
		new_like = like - 1;
		liked_name_index = like_name.indexOf(liked_name);
		new_like_name = like_name.substring(0,liked_name_index);
	}
	$(".count:eq("+ index +")").text(new_like);
	$(".begenenler:eq("+ index +")").text(new_like_name);
});
$("input[type=submit]").click(function(e){
	if($(".file").val() == "") {
		alert("Bir resim seçiniz..");
		e.preventDefault();
	}
  });
});
</script>
<body>
	<div class="dev-row">
		<div class="col-xs-12 header" style="background-color:#1762ca;height:60px;">
		<div class="col-xs-5">
			<a href="anasayfa.php"><img src="instagram2.png" width="120" style="margin-top:15px;"></a>
		</div>
		<div class="col-xs-2">
			<form action="" method="" id="form_ara">
				<input type="text" placeholder="Ara" class="form-control ara" name="ara" id="ara">
			</form>
		</div>
		<div class="col-xs-5 name" style="text-align:right;margin-top:10px;font:21px Arial;color:#fff;"><a href="kesfet.php"><i title="Keşfet" class="fa fa-table"></i></a><i class="fa fa-bars bars" id="bars" style="cursor:pointer;" title="Bildirimler"></i><a href="profil.php?id=<?php echo $id; ?>"><i title="Profil" class="fa fa-user"></i></a><a href="islemler.php?exit"><i title="Çıkış" class="fa fa-power-off"></i></a></div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="col-xs-2 span3 centering listeler" style="background-color:#F7F7F7;position:fixed;margin-left:160px;height:120px;z-index:9999999;margin-top:60px;-webkit-border-radius:4px;overflow-y:scroll;" id="listeler">
				<table>
					
				</table>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="col-xs-2 span3 centering bildirimler" style="background-color:#F7F7F7;position:fixed;margin-left:830px;height:180px;z-index:9999999;margin-top:60px;-webkit-border-radius:4px;overflow-y:scroll;" id="bildirimler">
				<table>
					
				</table>
			</div>
		</div>
	</div>
	<input type="text" value="<?php echo $_COOKIE['id']; ?>" class="kisi_id">
	<input type="text" value="<?php echo $kadi; ?>" class="kisi_ad">
</body>
</html>
