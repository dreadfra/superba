<?php

/**
 * Controlla se esiste una o più chiavi all'interno di un array.
 *
 * @param array $arr
 * @param array $keys
 * @return boolean
 */
function _isset( $arr, $keys = array() ) {
	$keys = (array) $keys;

	foreach ( $keys as $key ) {
		if ( ! isset( $arr, $key ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Ritorna l'elenco degli slider creati con il plugin LayerSlider.
 *
 * @return array
 */
function thb_get_sliders() {
	global $wpdb;

	$results = $wpdb->get_results( "select id, name from {$wpdb->prefix}layerslider where flag_hidden = 0 and flag_deleted = 0" );
	$sliders = array();

	foreach ( $results as $result ) {
		$sliders[$result->id] = $result->name;
	}

	return $sliders;
}

/**
 * Ritorna l'URL di una pagina creata a partire dal suo template.
 *
 * @param string $template Il template della pagina.
 * @return string
 */
function thb_get_page_url_by_template( $template ) {
	$pages = get_posts( array(
		'posts_per_page' => -1,
		'post_type'      => 'page',
		'post_status'    => 'publish'
	) );

	foreach ( $pages as $page ) {
		if ( thb_get_page_template( $page->ID ) == $template )  {
			return get_permalink( $page->ID );
		}
	}

	return '';
}

/**
 * Ritorna un URL o un permalink a partire dall'ID di una risorsa.
 *
 * @param mixed $link URL or ID.
 * @return string
 */
function thb_get_link( $link ) {
	if ( is_numeric( $link ) ) {
		return get_permalink( $link );
	}

	return $link;
}

/**
 * Controllo che una stringa termini con una particolare sottostringa.
 *
 * @param string $haystack La stringa contenitore.
 * @param string $needle La stringa da ricercare.
 * @return boolean
 */
function thb_string_ends_with( $haystack, $needle ) {
	$length = strlen( $needle );

	if ( $length === 0 ) {
		return true;
	}

	return ( substr( $haystack, -$length ) === $needle );
}

/**
 * Funzione che si assicura che una stringa termini con una particolare sottostringa.
 *
 * @param string $haystack La stringa contenitore.
 * @param string $needle La stringa da ricercare.
 * @return string La stringa modificata, se necessario.
 */
function thb_string_ensure_right( $haystack, $needle ) {
	if ( ! thb_string_ends_with( $haystack, $needle ) ) {
		return $haystack . $needle;
	}

	return $haystack;
}

/**
 * Ottiene il contenuto di un template.
 *
 * @param string $file Il path completo al file di template.
 * @param array $data L'array di dati che devono essere passati al template.
 * @param boolean $echo Se "true", il template viene stampato direttamente a video.
 * @return string
 */
function thb_template( $path, $data = array(), $echo = true ) {
	$path = thb_string_ensure_right( $path, '.php' );

	if ( file_exists( $path ) ) {
		extract( $data );

		ob_start();
		include $path;
		$content = ob_get_contents();
		ob_end_clean();

		if( ! $echo ) {
			return $content;
		}
		else {
			echo $content;
		}
	}

	return '';
}

/**
 * Ottiene il contenuto di un template. Se ci si trova in un child theme, la funzione
 * ricercherà il file di template prima nella directory del child theme e successivamente
 * nel tema genitore.
 *
 * @param string $file Il path completo al file di template.
 * @param array $data L'array di dati che devono essere passati al template.
 * @param boolean $echo Se "true", il template viene stampato direttamente a video.
 * @return string
 */
function thb_get_template_part( $file, $data = array(), $echo = true ) {
	$file = thb_string_ensure_right( $file, '.php' );
	$path = locate_template( $file );

	return thb_template( $path, $data, $echo );
}

/**
 * Ritorna il template di pagina utilizzato da una risorsa.
 *
 * @param int $page_id L'ID della risorsa.
 * @return string
 */
function thb_get_page_template( $page_id = null ) {
	if( $page_id == null ) {
		$page_id = get_the_ID();
	}

	$template = '';

	if( $page_id === 0 ) {
		return $template;
	}

	$post_type = get_post_type( $page_id );

	if( $post_type == 'page' ) {
		$template = get_post_meta( $page_id, '_wp_page_template', true );

		if( ! $template ) {
			$template = 'default';
		}
	}
	else if( ! empty( $post_type ) ) {
		if( $post_type == 'post' ) {
			$template = 'single.php';
		}
		else {
			$template = 'single-' . $post_type . '.php';
		}
	}

	return $template;
}

/**
 * Ritorna il template di pagina utilizzato da una risorsa nell'area di amministrazione.
 *
 * @return string
 */
function thb_get_admin_template() {
	global $pagenow;
	$thb_page_id = 0;
	$post_type = 'post';

	if ( ! empty( $_POST ) ) {
		if ( isset( $_POST['post_ID'] ) ) {
			$thb_page_id = absint( $_POST['post_ID'] );
		}

		if ( isset( $_POST['post_type'] ) ) {
			$post_type = (string) $_POST['post_type'];
		}
	}
	elseif ( ! empty( $_GET ) ) {
		if ( isset( $_GET['post'] ) ) {
			$thb_page_id = absint( $_GET['post'] );
		}

		if ( isset( $_GET['post_type'] ) ) {
			$post_type = (string) $_GET['post_type'];
		}
	}

	if( ! $thb_page_id ) {
		if ( $post_type !== 'post' ) {
			if ( $post_type !== 'page' && $pagenow !== 'edit.php' ) {
				return 'single-' . $post_type . '.php';
			}
			else {
				return 'default';
			}
		}
		else {
			return 'single.php';
		}
	}
	else {
		return thb_get_page_template( $thb_page_id );
	}
}

/**
 * Ritorna l'URL di un'immagine scalata partendo dall'ID dell'attachment.
 *
 * @param int|string $id L'ID dell'attachment.
 * @param string $size L'image size desiderata; 'full' di default.
 * @return string L'URL di un'immagine scalata.
 **/
function thb_get_image( $id, $size = 'full' ) {
	if ( ! empty( $id ) && is_array( $image = wp_get_attachment_image_src( $id, $size ) ) ) {
		return esc_url( current( $image ) );
	}

	return '';
}

/**
 * Ritorna l'URL della featured image scalata partendo dall'ID dell'attachment.
 *
 * @param  string $size L'image size desiderata; 'full' di default.
 * @param  int $post_id L'ID del post.
 * @return string
 */
function thb_get_featured_image( $size = 'full', $post_id = null ) {
	if ( ! $post_id ) {
		global $post;
		$post_id = get_the_ID();
	}

	return thb_get_image( get_post_thumbnail_id( $post_id ), $size );
}

/**
 * Ritorna l'elenco dei comuni italiani categorizzati per regione e provincia.
 *
 * @return array
 */
function get_comuni() {
	$comuni_cache = get_option( 'manzotin_comuni' );
	$return = array();

	if ( $comuni_cache === false ) {
		$file = get_template_directory() . '/includes/concorso/comuni.txt';
		$data = fopen( $file, 'r' );

		if ( $data ) {
			while ( ( $line = fgets( $data ) ) !== false ) {
				$line = trim( $line );
				list( $comune, $provincia, $regione ) = explode( ',', $line );

				if ( ! array_key_exists( $regione, $return ) ) {
					$return[$regione] = array();
				}

				if ( ! array_key_exists( $provincia, $return[$regione] ) ) {
					$return[$regione][$provincia] = array();
				}

				$return[$regione][$provincia][] = $comune;
			}

			update_option( 'manzotin_comuni', $return );
		}
	}
	else {
		$return = $comuni_cache;
	}

	return $return;
}

/**
 * Ritorna l'elenco delle province italiane.
 *
 * @return array
 */
function get_province() {
	$province = array(
		"AO" => "Aosta",
                "AG" => "Agrigento",
		"AL" => "Alessandria",
		"AN" => "Ancona",
		"AR" => "Arezzo",
		"AP" => "Ascoli Piceno",
                "AQ" => "L'Aquila",
		"AT" => "Asti",
		"AV" => "Avellino",
		"BA" => "Bari",
		"BT" => "Barletta-Andria-Trani",
		"BL" => "Belluno",
		"BN" => "Benevento",
		"BG" => "Bergamo",
		"BI" => "Biella",
		"BO" => "Bologna",
		"BZ" => "Bolzano/Bozen",
		"BS" => "Brescia",
		"BR" => "Brindisi",
		"CA" => "Cagliari",
		"CL" => "Caltanissetta",
		"CB" => "Campobasso",
		"CI" => "Carbonia-Iglesias",
		"CE" => "Caserta",
		"CT" => "Catania",
		"CZ" => "Catanzaro",
		"CH" => "Chieti",
		"CO" => "Como",
		"CS" => "Cosenza",
		"CR" => "Cremona",
		"KR" => "Crotone",
		"CN" => "Cuneo",
		"EN" => "Enna",
		"FM" => "Fermo",
		"FE" => "Ferrara",
		"FI" => "Firenze",
		"FG" => "Foggia",
		"FC" => "Forlì-Cesena",
		"FR" => "Frosinone",
		"GE" => "Genova",
		"GO" => "Gorizia",
		"GR" => "Grosseto",
		"IM" => "Imperia",
		"IS" => "Isernia",
		"LT" => "Latina",
		"LE" => "Lecce",
		"LC" => "Lecco",
		"LI" => "Livorno",
		"LO" => "Lodi",
		"LU" => "Lucca",
		"MC" => "Macerata",
		"MN" => "Mantova",
		"MS" => "Massa-Carrara",
		"MT" => "Matera",
		"VS" => "Medio Campidano",
		"ME" => "Messina",
		"MI" => "Milano",
		"MO" => "Modena",
		"MB" => "Monza e della Brianza",
		"NA" => "Napoli",
		"NO" => "Novara",
		"NU" => "Nuoro",
		"OG" => "Ogliastra",
		"OT" => "Olbia-Tempio",
		"OR" => "Oristano",
		"PD" => "Padova",
		"PA" => "Palermo",
		"PR" => "Parma",
		"PV" => "Pavia",
		"PG" => "Perugia",
		"PU" => "Pesaro e Urbino",
		"PE" => "Pescara",
		"PC" => "Piacenza",
		"PI" => "Pisa",
		"PT" => "Pistoia",
		"PN" => "Pordenone",
		"PZ" => "Potenza",
		"PO" => "Prato",
		"RG" => "Ragusa",
		"RA" => "Ravenna",
		"RC" => "Reggio di Calabria",
		"RE" => "Reggio nell'Emilia",
		"RI" => "Rieti",
		"RN" => "Rimini",
		"RM" => "Roma",
		"RO" => "Rovigo",
		"SA" => "Salerno",
                "SP" => "La Spezia",
		"SS" => "Sassari",
		"SV" => "Savona",
		"SI" => "Siena",
		"SR" => "Siracusa",
		"SO" => "Sondrio",
		"TA" => "Taranto",
		"TE" => "Teramo",
		"TR" => "Terni",
		"TO" => "Torino",
		"TP" => "Trapani",
		"TN" => "Trento",
		"TV" => "Treviso",
		"TS" => "Trieste",
		"UD" => "Udine",
		"AO" => "Valle d'Aosta/Vallée d'Aoste",
		"VA" => "Varese",
		"VE" => "Venezia",
		"VB" => "Verbano-Cusio-Ossola",
		"VC" => "Vercelli",
		"VR" => "Verona",
		"VV" => "Vibo Valentia",
		"VI" => "Vicenza",
		"VT" => "Viterbo",
	);

	return $province;
}

/**
 * Ottieni le immagini delle ultime tre ricette inserite.
 *
 * @return array
 */
function thb_get_ricette_images() {
	$args = array(
		'post_type' => 'ricetta',
		'post_status' => 'publish',
		'posts_per_page' => 3,
	);

	$images = array();
	$ricette = get_posts( $args );

	foreach ( $ricette as $ricetta ) {
		$images[] = thb_get_featured_image( 'medium', $ricetta->ID );
	}

	return $images;
}

add_filter('upload_mimes','add_custom_mime_types');

function add_custom_mime_types($mimes){
        return array_merge($mimes,array (
                'gpx' => 'application/gpx+xml',
                'gpx' => 'application/octet-stream'
        ));
}

function get_corridoreName_fromID($id_corridore){
        $post = get_post($id_corridore);
        return $name = $post->post_title;

}