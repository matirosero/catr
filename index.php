<?php

//Resultados de busquedas en flickr son completamente diferentes, por qué????



if (isset($_GET['by'])) {
	$get_by = $_GET['by'];
	if ($get_by == 'location') {
		if (isset($_GET['longitude'])) {
			$longitude = $_GET['longitude'];
		}
		if (isset($_GET['latitude'])) {
			$latitude = $_GET['latitude'];
		}		
	}
}
if (isset($_GET['extra_tags'])) {
	$extra_tags = str_replace(' ','',$_GET['extra_tags']);
}




?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8" /> <!--nuevo tambien-->
	<title>Catr | Buscador de gatos en Flickr</title>
	
	<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700|Oswald:400,300,700|Open+Sans+Condensed:300,700,300italic' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/normalize.css" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/font-awesome.min.css">
	
		
</head>
	
<body>
<div id="wrap">
	<header id="site-header" class="clearfix">
		<h1 id="site-title">
			<a href="index.php"><img src="images/logo_catr.svg" alt="catr" /></a>
		</h1><!-- /#site-title -->
		
			<?php if ( (isset($_GET['by'])) || (isset($_GET['extra_tags'])) ) {
				echo '<h5>Nueva búsqueda:</h5>';
			} else {
				echo '<h5 class="center">Escogé tus parametros para buscar gatos en Flickr:</h5>';
			} ?>		
		
		
		<div id="search-form">


			<form>
				<div id="field1">
					<label for="by">Gatos</label>
					<span class="input-group">
						<input type="radio" name="by" value="interestingness" <?php if ( (!isset($get_by)) || ( (isset($get_by)) && ($get_by=='interestingness') ) ) { echo 'checked'; } ?> /> <label>más interesantes*</label>
						<br /><input type="radio" name="by" value="location" class="disabled" <?php if ( (isset($get_by)) && ($get_by=='location') ) { echo 'checked'; } ?> /> <label class="disabled">más cercanos</label>
						<span class="hidden-fields"><?php 
							if ( (isset($get_by)) && ($get_by=='location') ) {
								echo '<input type="hidden" name="latitude" id="latitude" value="' . $latitude . '">'.
									'<input type="hidden" name="longitude" id="longitude" value="' . $longitude . '">';
							}
						?></span>
					
						<br />
						<input type="radio" name="by" value="recent" <?php if ( (isset($get_by)) && ($get_by=='recent') ) { echo 'checked'; } ?> /> <label>más recientes</label>
						<br />
						<small>*Según Flickr</small>
					</span>
				</div>
				
				<div id="field2">
					<label for="extra_tags">Tags adicionales (separados por coma)</label><br />
					<input type="text" name="extra_tags" <?php if ( (isset($extra_tags)) && ($extra_tags!="") ) { echo 'value="' . $extra_tags . '"'; } else { echo 'placeholder="siamese,tabby,black,etc"'; } ?> />
					
				</div>
				
				<div id="field3">
					<button type="submit" formaction="<?php echo $_SERVER['PHP_SELF'] ?>" formmethod="get">Buscar gatos</button>
				</div>
			</form>			
		</div>
	</header><!-- /#site-header -->

	<div id="page-content">
		
			

			

<?php 

	$per_page=25;
	if ( (isset($_GET['page'])) && ($_GET['page'] > 0) ) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	$next_page=$page+1;
	$previous_page=$page-1;
	$apikey="8db494b883e998593368096f0c3811f3";
	$tag="cats";
	$extras = "description,date_upload,owner_name,geo,tags,views,url_m";
	$sort="interestingness-desc";
	
	$about_search ="";
	
	
	if ((isset($get_by)) || (isset ($extra_tags))) {

		if ($extra_tags != "" ) {
			
			$tag.=",".$extra_tags;
			
			$about_search .= 'tagueados con <strong>'.$extra_tags.'</strong>';
			
		} 
		
		$args = '&per_page='.$per_page.'&page='.$page.'&api_key='.$apikey."&tags=".$tag.'&extras='.$extras.'&tag_mode=all';
		
		if (isset($get_by)) {
				
			//if get_by exists
		
			if ($get_by == 'interestingness') {
				$sort = 'interestingness-desc';
			
				$args .= '&sort='.$sort;
			
				if ($extra_tags != "" ) {
					$about_search .= ', ';
				} 
				$about_search .= 'que fueran de los más interesantes (según Flickr)'; 
			
			
			} elseif ($get_by == 'recent') {
				$sort = 'date-posted-desc';
			
				$args .= '&sort='.$sort;
			
				if ($extra_tags != "" ) {
					$about_search .= ', ';
				} 
				$about_search .= 'subidos recientmente'; 
			
			
			} elseif ($get_by == 'location') {
				$location = '&has_geo=1&lat='.$latitude.'&lon='.$longitude.'&radius=2';
				$sort="relevance"; //hay que quitar el sort=relevance?
			
				$args .= '&sort='.$sort.$location;
			
				if ($extra_tags != "" ) {
					$about_search .= ', ';
				}
				$about_search .= 'cerca de tu posición actual (latitud '.$latitude.', longitud '.$longitude.')';
			}

		}
		
		$args.="&format=json&nojsoncallback=1";
		
		$uri='https://api.flickr.com/services/rest/?method=flickr.photos.search'.$args;
		//$uri="https://api.flickr.com/services/rest/?method=flickr.photos.search&per_page=50&api_key=".$apikey."&tags=".$tag;
		//$uri.="&extras=".$extras."&tag_mode=all".$sort.$location."&format=json&nojsoncallback=1";
		
		
		
		$data=file_get_contents($uri);
		$object = json_decode( $data ); // stdClass object

		//aqui ya tengo los datos en un array de php
		
		?>
		
		<p class="center">
			Buscaste gatos <?php echo $about_search; ?>. [<a href="<?php echo $uri; ?>">Ver JSON</a>]
		</p>
		
		<ul id="kitties">
		
		<?php
		
		
		foreach($object->photos->photo as $p){
			if (isset($p->url_m)) {
			$link="http://flickr.com/photo.gne?id=".$p->id;

			print '<li class="kitty"><a href="'.$link.'"><img src="'.$p->url_m.'"/></a>'.
				'<div class="photo-info">'.
				'<ul>'.
				'<li><h6>'.$p->title.'</h6></li>'.
				'<li><i class="fa fa-user fa-fw"></i> <strong>'.$p->ownername.'</strong></li>'.
				'<li><i class="fa fa-clock-o fa-fw"></i> '.date("Y m d", $p->dateupload).'</li>'. 
				'<li><i class="fa fa-tags fa-fw"></i> '.$p->tags.'</li>';
				
			if ((isset($get_by)) && ($get_by == 'location')) {
				print '<li><i class="fa fa-map-marker fa-fw"></i> '.$p->latitude.', '.$p->longitude.'</li>';
			}
			print '<li><i class="fa fa-eye fa-fw"></i> '.$p->views.'</li>'.
				'</ul></div></li>';
			}
	}
		
		?>
		
		</ul>
		
		<?php 
			
			$pattern = '/&page=\d*/';
			$replacement = '';
			$link_next_page = preg_replace($pattern, $replacement, $_SERVER['REQUEST_URI']);
			
			echo '<p class="center pagination">';
			if ($page > 1) {
				echo '<a href="'.$link_next_page.'&page='.$previous_page.'">Página anterior</a> | ';
			} 
			echo 'Página '.$page.' | <a href="'.$link_next_page.'&page='.$next_page.'">Siguiente página</a></p>';
		
		
	} else {
		
		
	}

	?>

				
		
	</div><!-- /#page-content /.wrap -->





<div id="site-footer">
	<p>
		<span>Matilde Rosero</span> | <span><a href="mailto:gekidasa@gmail.com">gekidasa@gmail.com</a></span>
	</p>
</div>

</div>



	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="js/catr.js"></script>
	<script src="js/modernizr.js"></script>
		<script src="js/masonry.pkgd.min.js"></script>
		<script src="js/imagesloaded.js"></script>
		<script>
			var $container = $('#kitties');
			// initialize
			$container.imagesLoaded( function() {
				$container.masonry({
					columnWidth: 230,
					itemSelector: '.kitty'
				});
			});
		</script>


</body>
</html>