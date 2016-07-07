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

$user = $db->prepare("SELECT * FROM user ORDER BY data_check DESC LIMIT 1");
$user->execute();
$usuario = $user->fetchAll(PDO::FETCH_ASSOC);



$busca = $db->prepare("SELECT *, MAX(totalLikes) AS 'atual_Likes', MAX(totalComentarios) AS 'atual_Comentarios', MAX(data_check) AS 'atual_Data' FROM medias GROUP BY id_media ORDER BY tempo_criacao DESC;");
$busca->execute();
$resultados = $busca->fetchAll(PDO::FETCH_ASSOC);

		
		
$labels = array();

$labels[] ="ID Mídia";
$labels[] ="Tags";
$labels[] ="Tipo";
$labels[] ="Comentários";
//$labels[] ="Filtro";
$labels[] ="Tempo de criação";
$labels[] ="Likes";
$labels[] ="Imagem/Post";
$labels[] ="Texto da rubrica";
$labels[] ="Coletado";


$campos = array();

$campos[] =  "id_media";
$campos[] =  "tags";
$campos[] =  "tipo";
$campos[] =  "atual_Comentarios";
//$campos[] =  "filtro";
$campos[] =  "tempo_criacao";
$campos[] =  "link";
$campos[] =  "atual_Likes";
$campos[] =  "imagem_640";
$campos[] =  "texto_rubrica";
$campos[] =  "atual_Data";


?>


<div class="container" style="width:99%;">
  <div class="row">
  
  
      <div class="panel-heading">
        <table>
        	<tr>
            
            
			<?php 
				foreach($usuario as $dados_usuario){
					
								echo '<td style="width: 160px  !important;">';	
									echo '<img src="'.$dados_usuario['profile_picture'].'" width="150" height="150"/>';
									
								echo "</td>"; 
								
								echo '<td style="width: 135px !important;">';
									echo $dados_usuario['full_name'];
									echo "<br />"; 
									echo $dados_usuario['username']." <br /> ID:".$dados_usuario['id_user'];
									echo "<br />"; 
									echo $dados_usuario['bio'];
									echo "<br />"; 
									echo $dados_usuario['website'];
									
								echo "</td>"; 
									
								
								echo '<td style="width: 180px !important; padding-left:20px;">';
								
									echo 'Dados Atualizado em: '.date('d/m/Y', strtotime($dados_usuario['data_check']));
									echo "<br />"; 
									
									echo '<button class="btn btn-primary" type="button">';
									echo '  Mídias <span class="badge">'.$dados_usuario['media'].'</span>';
									echo '</button>';
								echo "</td>"; 
								
								
								echo '<td style="width: 180px !important; padding-left:20px;">';
								
									echo 'Dados Atualizado em: '.date('d/m/Y', strtotime($dados_usuario['data_check']));
									echo "<br />"; 
									
									echo '<button class="btn btn-primary" type="button">';
									echo '  Seguido por <span class="badge">'.$dados_usuario['followed_by'].'</span>';
									echo '</button>';
								echo "</td>"; 
								
								
								echo '<td style="width: 180px !important; padding-left:20px;">';
								
									echo 'Dados Atualizado em: '.date('d/m/Y', strtotime($dados_usuario['data_check']));
									echo "<br />"; 
									
									echo '<button class="btn btn-primary" type="button">';
									echo '  Seguindo <span class="badge">'.$dados_usuario['follows'].'</span>';
									echo '</button>';
								echo "</td>"; 
								
										
					
				}
			
			?>
            
            
            
            </tr>
        
        </table>
       
      </div>




    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">Instagram</h3>
        <div class="pull-right">
          <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtro</button>
        </div>
      </div>
      <table class="table">
        <thead>
          <tr class="filters">
            <?php foreach($labels as $label){
							
							if($label=='Texto da rubrica' || $label=='Tags'){
								
								echo '<th style="width: 420px !important;">';
								echo "<input type='text' class='form-control' placeholder='$label' disabled>";
								echo "</th>"; 	
								
							}elseif($label=='Tipo' || $label=='Comentários' || $label=='Likes' || $label=='Imagem/Post'){
								
								echo '<th style="width: 135px !important;">';
								echo "<input type='text' class='form-control' placeholder='$label' disabled>";
								echo "</th>"; 	
								
								}
							elseif($label=='Tempo de criação'){
								echo '<th>';
								echo "<input type='text' class='form-control' placeholder='Dia' disabled>";
								echo "</th>";
								
								echo '<th>';
								echo "<input type='text' class='form-control' placeholder='Mês' disabled>";
								echo "</th>";
								
								
								echo '<th>';
								echo "<input type='text' class='form-control' placeholder='Ano' disabled>";
								echo "</th>"; 		
								}
								elseif($label=='Coletado'){
								
								echo '<th style="width: 135px !important;">';
								echo "<input type='text' class='form-control' placeholder='$label' disabled>";
								echo "</th>"; 	
								
								}
                            	
								
							
                        
                         }?>
          </tr>
        </thead>
        <tbody>
          <?php 
		  
		  foreach($resultados as $dado){
			  $link_media = trim($dado['id_media']);
			  ?>
          <tr>
            <?php 
                    	foreach($campos as $campo){
							
							
							
							if($campo=='link'){
								
									}else{
									
										if($campo=='texto_rubrica' || $campo=='tags' ){
											echo '<td style="width: 420px  !important;">';	
											echo $dado[$campo];
											echo "</td>"; 
										
										}elseif($campo=='tipo' || $campo=='atual_Comentarios' || $campo=='atual_Likes' ){
											echo '<td style="width: 135px !important;">';	
											echo $dado[$campo];
											echo "</td>"; 
											}
										elseif($campo=="imagem_640"){
													echo '<td style="width: 135px !important;">';	
													echo '<a href="post.php?id='.$link_media.'"><img src="'.$dado[$campo].'" width="100" height="100"/></a>';
													echo "</td>"; 
										}elseif($campo=='tempo_criacao'){
													echo '<td style="width: 135px !important;">';	
													echo date('j', $dado[$campo]);
													echo "</td>"; 
													
													echo '<td style="width: 135px !important;">';	
													echo date('m', $dado[$campo]);
													echo "</td>"; 
													
													echo '<td style="width: 135px !important;">';	
													echo date('Y', $dado[$campo]);
													echo "</td>"; 
													
										}
										elseif($campo=='atual_Data' ){
											echo '<td>';	
											echo  date('d/m/Y', strtotime($dado[$campo]));
											echo "</td>"; 
										}
										
								}
                          }?>
          </tr>
          <?php }
		  ?>
        </tbody>
      </table>
      
      
    </div>
  </div>
</div>
</body>
</html>