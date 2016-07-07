<?php
	require_once("functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>API INSTAGRAN</title>

<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="bootstrap/css/filtros.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="bootstrap/js/codes.js"></script>
</head>
<body>
<?php 

$db = dbConnect();
$db->beginTransaction();

$stmt = $db->prepare("SELECT * FROM medias WHERE id_media=:id_media ORDER BY data_check DESC");
$stmt->bindValue(':id_media', $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);



$campos = array();

$campos[] =  "id";
$campos[] =  "id_media";
$campos[] =  "user_username";
$campos[] =  "user_profile_picture";
$campos[] =  "user_full_name";
$campos[] =  "user_id";
$campos[] =  "atribuicao";
$campos[] =  "tags";
$campos[] =  "tipo";
$campos[] =  "localizacao_id";
$campos[] =  "localizacao_name";
$campos[] =  "localizacao_lat";
$campos[] =  "localizacao_lon";
$campos[] =  "atual_Comentarios";
$campos[] =  "filtro";
$campos[] =  "tempo_criacao";
$campos[] =  "link";
$campos[] =  "Likes";
$campos[] =  "imagem_320";
$campos[] =  "imagem_150";
$campos[] =  "imagem_640";
$campos[] =  "tempo_criacao_rubrica";
$campos[] =  "texto_rubrica";
$campos[] =  "id_rubrica";
$campos[] =  "user_has_liked";
$campos[] =  "users_in_photo";
$campos[] =  "data_check";


$labels = array();
$labels[] ="ID na Tabela";
$labels[] ="ID Mídia";
$labels[] ="Username";
$labels[] ="Foto do Perfil";
$labels[] ="Nome do Usuáriio";
$labels[] ="ID do Usuáriio";
$labels[] ="Atribuição"; 
$labels[] ="Tags";
$labels[] ="Tipo";
$labels[] ="localização - ID";
$labels[] ="localização - Nome";
$labels[] ="localização - Latitude";
$labels[] ="localização - Longitude";
$labels[] ="Comentários";
$labels[] ="Filtro";
$labels[] ="Tempo de criação";
$labels[] ="Link";
$labels[] ="Likes";
$labels[] ="Imagem 320";
$labels[] ="Imagem 150";
$labels[] ="Imagem";
$labels[] ="Tempo criação da rubrica";
$labels[] ="Texto da rubrica";
$labels[] ="Id da rubrica";
$labels[] ="Like do Usuário";
$labels[] ="Usuário marcados ns Mídia";
$labels[] ="Coletado";


?>

<div class="container_post">
        	
	<?php 
	$count = 0;
	foreach($resultados as $dado){
		if($count==0){
		
		$imagem640 = $dado['imagem_640'];
 
		$imagem = strtok($imagem640, '?');
		?>
    	
       		 <div class="img_post">
            	<img src="<?php echo $imagem;?>" width="640" alt=""/>
               <?php 
			   
                $busca_comments = $db->prepare("SELECT * FROM comments WHERE id_media=:id_media GROUP BY id_comment ORDER BY data_check DESC");
				$busca_comments->bindValue(':id_media', $_GET['id'], PDO::PARAM_STR);
				$busca_comments->execute();
				$comments = $busca_comments->fetchAll(PDO::FETCH_ASSOC);


			   if($comments){
               
			   foreach($comments as $comentario){
				   ?>
				  <a href="#" class="list-group-item">
                        <span class="glyphicon glyphicon-file"></span> Comentário <br /><br />
                        <p><?php echo $comentario['full_name'];?> - ( <?php echo $comentario['username'];?> )</p>
                        <p><?php echo $comentario['texto'];?></p>
                        <p><?php echo date('j/m/Y H:i:s', $comentario['tempo_criacao']);;?></p>
                        
                        
                    </a>      
			   
			   <?php }
			   }
				?>
            </div>
         <?php $count++; }?>   
            
           <div class="info_post">
           
           
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        <span class="glyphicon glyphicon-thumbs-up"></span> Likes <span class="badge"><?php echo $dado['totalLikes'];?></span>
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="glyphicon glyphicon-file"></span> Texto <br /><br /> <p><?php echo $dado['texto_rubrica'];?></p>
                    </a>

                    <a href="#" class="list-group-item">
                        <span class="glyphicon glyphicon-film"></span>  <span class="glyphicon glyphicon-picture"></span> Tipo <span class="badge"><?php echo $dado['tipo'];?></span>
                    </a>
                    
                     <a href="#" class="list-group-item">
                        <span class="glyphicon glyphicon-ok"></span> coletado em  <span class="badge"><?php echo  date('d/m/Y', strtotime($dado['data_check']));?></span>
                    </a>
                    
                </div>
                
            </div>
        
        
    <?php }?>
    
    </div>
</body>
</html>