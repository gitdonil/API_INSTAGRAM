<?php
	require_once("functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>API INSTAGRAN</title>

</head>
<body>
<?php 

// ATUALIZANDO OS DADOS USUÁRIO 
$user = go_api("https://api.instagram.com/v1/users/self/?access_token=__$ACCESS_TOKEN");
$dados_user_atualizados = goto_save_user($user);


// ATUALIZANDO MEDIAS
$url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=__$ACCESS_TOKEN#';
$obj = go_api($url);

$dados_atualizados = goto_save($obj);


// ATUALIZANDO COMENTÀRIOS RECENTES
$url_media ='https://www.instagram.com/__USER_INSTAGRAM__/media/';
$obj_url_media = go_api($url_media);

$dados_comments = goto_save_comments($obj_url_media);

$db = dbConnect();

$tabela = $db->prepare("SELECT *, MAX(totalLikes) AS 'atual_Likes', MAX(totalComentarios) AS 'atual_Comentarios', MAX(data_check) AS 'atual_Data' FROM medias GROUP BY id_media ORDER BY data_check DESC");
$tabela->execute();
$data_2015 = $tabela->fetchAll(PDO::FETCH_ASSOC);


// EXPORTANTO PLANILHA COM MEDIAS DO BANCO DE DADOS
$html = '';	
if($dados_atualizados){
	
	$html .= '<table style="width:100%" border="1">';
	$html .= '<tr bgcolor="#71bf44" >
			<td>ID na Tabela</td>
			<td>Dia do Post</td>
			<td>Mês do Post</td>
			<td>Ano do Post</td>
			<td>ID Mídia</td>
			<td>Username</td>
			<td>Nome do Usuáriio</td>
			<td>ID do Usuáriio</td>
			<td>Tags</td>
			<td>Tipo</td>
			<td>Total de comentários</td>
			<td>Filtro</td>
			<td>Link</td>
			<td>Total de Likes</td>
			<td>Texto da rubrica</td>
			<td>Data de Coleta</td>
			
		  </tr>';
		  
	foreach($data_2015 as $dado){
		$html .= "<tr>
				<td>".$dado['id']."</td>
				<td>".date('j', $dado['tempo_criacao'])."</td>
				<td>".date('m', $dado['tempo_criacao'])."</td>
				<td>".date('Y', $dado['tempo_criacao'])."</td>
				<td>".$dado['id_media']."</td>
				<td>".$dado['user_username']."</td>
				<td>".$dado['user_full_name']."</td>
				<td>".$dado['user_id']."</td>
				<td>".$dado['tags']."</td>
				<td>".$dado['tipo']."</td>
				<td>".$dado['atual_Comentarios']."</td>
				<td>".$dado['filtro']."</td>
				<td>".$dado['link']."</td>
				<td>".$dado['atual_Likes']."</td>

				<td>".$dado['texto_rubrica']."</td>
				<td>".$dado['atual_Data']."</td>
				
		  </tr>";
	}
	
	$html .= '</table>';



        header("Content-type: application/vnd.ms-excel");
		header("Content-type: application/force-download");
		header("Content-Disposition: attachment; filename=tabela_instagram.xls");
		header("Pragma: no-cache");
	
		echo $html;
	}	
		?>
		

</body>
</html>