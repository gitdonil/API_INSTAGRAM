<?php
// CONEXÂO COM O BANDO DE DADOS
	function dbConnect() {
		 $host   = 'xxxx';
		 $dbname = 'xxxx';
		 $user   = 'xxxx';
		 $pass   = 'xxxx';
		 
		try
		{
			$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
			$options = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false);
			$db = new PDO($dsn, $user, $pass, $options);
			return $db;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
	}

// FUNÇÂO PARA ACESSAR DADOS VIA API
function go_api($url)
	{
		$usrAgent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
	
		$cReq = curl_init();
		curl_setopt( $cReq, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $cReq, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $cReq, CURLOPT_URL, $url );
		curl_setopt( $cReq, CURLOPT_USERAGENT, $usrAgent );
		$response = curl_exec( $cReq );
		curl_close( $cReq );
		$output = json_decode( $response );
	
		return $output;
	}





// FUNÇÂO PARA SALVAR DADOS NO BANCO DE DADOS

function save_db($dados, $tabela, $id_primary){
	
	$db = dbConnect();
	$db->beginTransaction();
	
	// string para os campos
	$campos =":$id_primary, ";
	
	foreach($dados as $campo=>$valor){
		$campos .= ":$campo, ";
	}
	
	// TIRANDO ULTIMO ESPAÇO E A ULTIMA VIRGULA
	$campos = substr($campos,0,-2);
	
	try
	{
		// CRIANDO O PREPARE USANDO CAMPOS E TABELA
		$stmt = $db->prepare("INSERT INTO ".$tabela." VALUES(".$campos.")");
		
		// SETANDO VALOR AOS CAMPOS
		$stmt->bindValue(':'.$id_primary , $db->lastInsertId());
		
			foreach($dados as $campo=>$valor){
				$stmt->bindValue(':'.$campo , $valor);
			}	
		
		$stmt->execute();
		$db->commit();
		$db = null;
	}
		catch(PDOException $e)
		{
			$name = "error_".time().".txt";
			$arquivo = fopen("logs/".$name, "w");
			$texto = $e->getMessage();
			fwrite($arquivo, $texto);
			fclose($arquivo);

		}

}



// FUNÇÂO PARA DEBUGAR 
function imprimir($array){
	echo "<div style='z-index: 100; position: absolute; background: #000; width: 80%; color:#60ff00'>";
	echo "<pre>";
	print_r($array);
	echo "</pre>";
	echo "</div>";
}



// FUNÇÂO PARA SALVAR AS MIDIAS DO INSTAGRAM NO BANCO DE DADOS 
function goto_save($obj){

		if($obj){
			
			$total = count($obj->data);
			$i = 0;

	
			//// GUARDANDO VALOR PARA PROXIMO LINK
			$paginacao = array();
			$paginacao['next_url']=$obj->pagination->next_url;
			$paginacao['next_max_id']=$obj->pagination->next_max_id;
	
			//// GUARDANDO VALORES DOS POSTS
			foreach($obj->data as $data){
				
				$i++;
				$array_medias = array();
				
				$array_medias['atribuicao'] = $data->attribution;
				$array_medias['tags'] = implode(", ", $data->tags);
				$array_medias['tipo'] = $data->type;
				$array_medias['localizacao_id'] = $data->location->id;
				$array_medias['localizacao_name'] = $data->location->name;
				$array_medias['localizacao_lat'] = $data->location->latitude;
				$array_medias['localizacao_lon'] = $data->location->longitude;
				$array_medias['totalComentarios'] = (int)$data->comments->count;
				$array_medias['filtro'] = $data->filter;
				$array_medias['tempo_criacao'] = $data->created_time;
				$array_medias['link'] = $data->link;
				$array_medias['totalLikes'] = (int)$data->likes->count;
				$array_medias['imagem_320'] = $data->images->low_resolution->url;
				$array_medias['imagem_150'] = $data->images->thumbnail->url;
				$array_medias['imagem_640'] = $data->images->standard_resolution->url;
				$array_medias['tempo_criacao_rubrica'] = $data->caption->created_time;
				$array_medias['texto_rubrica'] = $data->caption->text;
				$array_medias['id_rubrica'] = $data->caption->id;
				$array_medias['user_has_liked'] = $data->user_has_liked;
				$array_medias['id_media'] = $data->id;
				$array_medias['user_username'] = $data->user->username;
				$array_medias['user_profile_picture'] = $data->user->profile_picture;
				$array_medias['user_id'] = $data->user->id;
				$array_medias['user_full_name'] = $data->user->full_name;
				$array_medias['users_in_photo'] = substr($usuarios_in,0,-2);
				$array_medias['data_check'] = date('Y-m-d H:i:s');
		
		
		
				$id_media = $data->id;
				
				$db = dbConnect();
				$db->beginTransaction();

				$stmt = $db->prepare("SELECT * FROM medias WHERE id_media=:id_media ORDER BY data_check DESC");
				$stmt->bindValue(':id_media', $id_media, PDO::PARAM_STR);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$total_likes = (int)$data->likes->count;
				$total_comments = (int)$data->comments->count;
				
				
				if($result){
					   
					   if($result[0]['totalComentarios'] != $total_comments  || $result[0]['totalLikes'] != $total_likes ){
							
							$envia_media = save_db($array_medias, 'medias', 'id');
							
						}


				}else{
					
						$envia_media = save_db($array_medias, 'medias', 'id');
				}
				
				
				
				if($i == $total){
						if($paginacao['next_url']){
							$obj = go_api($paginacao['next_url']);
							goto_save($obj);

						}
					}
			
		
					
			} // FECHA IF(FOREACH(obj))
			
		} // FECHA IF(OBJ)
		
		$db = dbConnect();
		$db->beginTransaction();


		$busca = $db->prepare("SELECT * FROM medias GROUP BY id_media ORDER BY data_check DESC");
		$busca->execute();
		$resultados = $busca->fetchAll(PDO::FETCH_ASSOC);

		return $resultados ;
}

// FUNÇÂO PARA SALVAR AS DADOS DO USUÁRIO NO BANCO DE DADOS

function goto_save_user($obj){
		
		$array_dados = array();
		$array_dados['username'] =$obj->data->username;
        $array_dados['bio'] =$obj->data->bio;
        $array_dados['website'] =$obj->data->website;
        $array_dados['profile_picture'] =$obj->data->profile_picture;
        $array_dados['full_name'] =$obj->data->full_name;
    	$array_dados['media'] =$obj->data->counts->media;
        $array_dados['followed_by'] =$obj->data->counts->followed_by;
        $array_dados['follows'] =$obj->data->counts->follows;
		$array_dados['id_user'] =$obj->data->id;
		$array_dados['data_check'] = date('Y-m-d H:i:s');
		
		
		$envia_dados = save_db($array_dados, 'user', 'id');
	
			$db = dbConnect();
			$db->beginTransaction();
	
	
			$busca = $db->prepare("SELECT * FROM user ORDER BY data_check DESC");
			$busca->execute();
			$resultados = $busca->fetchAll(PDO::FETCH_ASSOC);
	

		return $resultados ;
}


// FUNÇÂO PARA SALVAR AS DADOS DE COMENTÀRIOS DAS MIDIAS NO BANCO DE DADOS

function goto_save_comments($obj){
		
		
		foreach($obj->items as $data){
			if($data->comments->data){
				
		
			
				foreach($data->comments->data as $comment){	
				
						$array_dados = array();
						$array_dados['id_media'] =$data->id;
						$array_dados['id_comment'] =$comment->id;
						$array_dados['tempo_criacao'] =$comment->created_time;
						$array_dados['texto'] =$comment->text;
						$array_dados['username'] =$comment->from->username;
						$array_dados['profile_picture'] =$comment->from->profile_picture;
						$array_dados['profile_id'] =$comment->from->id;
						$array_dados['full_name'] =$comment->from->full_name;
						$array_dados['link_media'] =$data->link;
						$array_dados['data_check'] = date('Y-m-d H:i:s');
						

						$envia_dados = save_db($array_dados, 'comments', 'id');
						
				}
			}

			
		
		}

	
			$db = dbConnect();
			$db->beginTransaction();
	
	
			$busca = $db->prepare("SELECT * FROM comments ORDER BY tempo_criacao DESC");
			$busca->execute();
			$resultados = $busca->fetchAll(PDO::FETCH_ASSOC);
	

		return $resultados ;
}

?>