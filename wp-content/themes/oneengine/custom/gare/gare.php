<?php

/**
 * Crea il custom post type "gara".
 *
 * @see http://codex.wordpress.org/Function_Reference/register_post_type
 */
function create_gare_post_type() {
	register_post_type( 'gara', array(
		'labels'             => array(
			'name'               => 'Gare',
			'singular_name'      => 'Gara',
			'menu_name'          => 'Gare',
			'name_admin_bar'     => 'Gara',
			'add_new'            => 'Aggiungi nuova',
			'add_new_item'       => 'Aggiungi nuova gara',
			'new_item'           => 'Nuova gara',
			'edit_item'          => 'Modifica gara',
			'view_item'          => 'Visualizza gara',
			'all_items'          => 'Tutte le gare',
			'search_items'       => 'Cerca gara',
			'parent_item_colon'  => 'Gara di appartenenza',
			'not_found'          => 'Nessuna gara trovata',
			'not_found_in_trash' => 'Nessuna gara trovata nel cestino'
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
                'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
                'menu_icon'           => 'dashicons-flag',
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	) );
}


/* Categorie del tipo di post Gara*/

function build_taxonomies() {  
    register_taxonomy( 'gara_categories', 'gara', array( 'hierarchical' => true, 'label' => 'Tipo di Gare', 'query_var' => true, 'rewrite' => true ) );
    register_taxonomy( 'gara_year', 'gara', array( 'hierarchical' => true, 'label' => 'Stagioni', 'query_var' => true, 'rewrite' => true ) );
}

function corridori_field() {
    global $post;
    
    
    wp_nonce_field( 'superba_gara_meta_box', 'superba_gara_meta_box_nonce' );
    
    $selected_corridori = get_post_meta( $post->ID );
    
    if(isset($selected_corridori['corridori'])){
        if($selected_corridori['corridori'][0] != ""){
            $partecipanti = unserialize($selected_corridori['corridori'][0]);
            $array = array();

            if(count($partecipanti) > 0 ){
                foreach($partecipanti as $key => $value){

                    $array[$value['title']] = $value;
                }    
            }
            $partecipanti = $array;
        }
    }
    if(!isset($partecipanti) ){
        $partecipanti = array();
    }
    $all_corridori = get_posts(array("post_type" => "corridore",'post_status'=> 'publish'));
    
     ?>
    <div id="meta_inner">
    <?php
    
    //get the saved meta as an array
    $corridori = get_post_meta($post->ID,'corridori');
    $c = 0;
        if(isset($corridori[0])){
            if($corridori[0] != ""){
            
            if ( count( $corridori[0] ) > 0 ) {
                foreach( $corridori[0] as $track ) {
                    if ( isset( $track['title'] ) || isset( $track['track'] ) ) {
                        ?> <p> Seleziona il Corridore <select name="corridori[<?php echo $c ?>][title]" >
                            <?php foreach ( $all_corridori as $corridore ){ ?> 
                                <option value="<?php echo $corridore->ID ?>" <?php if( $corridore->ID == $track['title'] ){ ?> selected="selected" <?php } ?> > <?php echo $corridore->post_title; ?> </option> 
                            <?php } ?> 
                            </select> 
                            -- Strava Race Activity : <input  type="text" name="corridori[<?php echo $c ?>][track]" value="<?php echo $track['track']?>" />
                            -- Classificato Lungo: <input size="3" type="text" name="corridori[<?php echo $c ?>][mysdamlungo]" value="<?php echo $track['mysdamlungo']?>" />
                            -- Classificato Medio: <input size="3" type="text" name="corridori[<?php echo $c ?>][mysdamcorto]" value="<?php echo $track['mysdamcorto']?>" />
                            -- Posizione Categoria: <input size="3" type="text" name="corridori[<?php echo $c ?>][categoria]" value="<?php echo $track['categoria']?>" />
                            <span class="remove">Elimina Partecipante</span></p>
                        <?php   $c = $c +1;
                    }
                }
            }
        }
    }

    ?>
    <span id="here"></span>
    <span class="add"><?php _e('Aggiungi Partecipante'); ?></span>
    <script>
        var $ =jQuery.noConflict();
        $(document).ready(function() {
            var count = <?php echo $c; ?>;
            $(".add").click(function() {
                count = count + 1;
                $('#here').append('<p> Seleziona il Corridore <select name="corridori['+count+'][title]" ><?php foreach ( $all_corridori as $corridore ){ ?> <option value="<?php echo $corridore->ID ?>" <?php if(in_array( $corridore->ID, $partecipanti )){ ?> selected="selected" <?php } ?> > <?php echo $corridore->post_title; ?> </option> <?php } ?> </select> -- Strava Race Activity : <input type="text" name="corridori['+count+'][track]" value="" />-- Classificato Lungo: <input size="3" type="text" name="corridori['+count+'][mysdamlungo]" value="" />-- Classificato Medio: <input size="3" type="text" name="corridori['+count+'][mysdamcorto]" value="" />-- Posizione Categoria: <input size="3" type="text" name="corridori['+count+'][categoria]" value="" /><span class="remove">Elimina Partecipante</span></p>' );
                return false;
            });
            $(".remove").live('click', function() {
                $(this).parent().remove();
            });
        });
        </script>
    </div><?php
}

function add_meta_boxes_corridori() {
    add_meta_box(   'corridori_field', 
                    'Partecipanti & Strava', 
                    'corridori_field', 
                    'gara' );
}

function add_custom_meta_boxes() {
 
    // Define the custom attachment for posts
    add_meta_box(
        'wp_custom_attachment',
        'GPX File',
        'wp_custom_attachment',
        'gara'
    );
 
} // end add_custom_meta_boxes



function add_gallery_meta_boxes() {
 
    // Define the custom attachment for posts
    add_meta_box(
        'gallery',
        'Scegli Galleria da associare',
        'link_gallery',
        'gara'
    );
 
} // end add_custom_meta_boxes

function link_gallery(){
    
    global $post;
    
    $selected_corridori = get_post_meta( $post->ID );

      $categories = get_categories( array('taxonomy' => 'gallery_categories' ));

     ?>
     <div id="meta_inner">
    
    <p> Seleziona la galleria 
        <select name="galleria" >
            <option value="0">Seleziona la Galleria</option>
        <?php foreach ( $categories as $key => $value){ ?>
            <option value="<?php echo $value->term_id ?>" <?php if( $value->term_id == $selected_corridori['galleria'][0] ){ ?> selected="selected" <?php } ?> > <?php if($value->parent != "0") echo "&raquo; "?><?php echo $value->name ?> </option> 
        <?php } ?> 
        </select>
    </p>
    
     </div>
   <?php
}

function wp_custom_attachment() {
 
    wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');
     
    $img = get_post_meta(get_the_ID(), 'wp_custom_attachment', true);  
    $img2 = get_post_meta(get_the_ID(), 'wp_custom_attachment_2', true);  
    
    $html = '<p class="description">'; 
    if(isset($img['url']) != ""){
        $html .= "<a href='".$img['url']."' target='_blank' >".$img['url']."</a> <br>";
    }
    $html .= 'Percorso Corto (o percorso Unico)';
    $html .= '<input type="file" id="wp_custom_attachment" name="wp_custom_attachment" value="" size="25" /><br>';
    if(isset($img2['url']) != ""){
        $html .= "<a href='".$img2['url']."' target='_blank' >".$img2['url']."</a> <br>";
    }
   
    $html .= 'Percorso Lungo';
    $html .= '<input type="file" id="wp_custom_attachment_2" name="wp_custom_attachment_2" value="" size="25" /> <br>';
    $html .= '</p>';
         
    echo $html;
    
}

/**
 * Funzione che salva il contenuto del meta box per la pagina di modifica/inserimento di una Ricetta.
 *
 * @param int $post_id L'ID della pagina che deve essere salvata.
 */
function superba_save_gara_meta_box( $post_id ) {
    
    
	if ( ! isset( $_POST['superba_gara_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['superba_gara_meta_box_nonce'], 'superba_gara_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		
                
                return $post_id;
                
	}
       // wp_die(print_r($_POST));
	if ( isset( $_POST['post_type'] ) ) {
		if ( 'gara' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		else {
			return;
		}
	}

	$admin_template = thb_get_admin_template();

	if ( $admin_template !== 'single-gara.php' ) {
		return;
	}

	$names = array(
		'link_url'      => "link_url",
                'partecipanti'  => "partecipanti",
                'galleria'      => "galleria",
                'activities_id' => "activities_id"
	);
        
        
        // OK, we're authenticated: we need to find and save the data

        $corridori = $_POST['corridori'];

        update_post_meta($post_id,'corridori',$corridori);
        
        // Make sure the file array isn't empty
        if(!empty($_FILES['wp_custom_attachment']['name'])) {

            // Setup the array of supported file types. In this case, it's just PDF.
            $supported_types = array('application/gpx+xml','application/octet-stream');

            // Get the file type of the upload
            $uploaded_type = $_FILES['wp_custom_attachment']['type'];

            // Check if the type is supported. If not, throw an error.
            if(in_array($uploaded_type, $supported_types)) {

                // Use the WordPress API to upload the file
                $upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));

                if(isset($upload['error']) && $upload['error'] != 0) {
                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                } else {

                    $post_meta = add_post_meta($post_id, 'wp_custom_attachment', $upload);
                    $update_meta = update_post_meta($post_id, 'wp_custom_attachment', $upload);
                } // end if/else

            } else {
                wp_die("The file type that you've uploaded is not a GPX.".print_r($supported_types)."=".$uploaded_type);
            } // end if/else

        } // end if
        
        // Make sure the file array isn't empty
        if(!empty($_FILES['wp_custom_attachment_2']['name'])) {

            // Setup the array of supported file types. In this case, it's just PDF.
            $supported_types = array('application/gpx+xml','application/octet-stream');

            // Get the file type of the upload
            $uploaded_type = $_FILES['wp_custom_attachment_2']['type'];

            // Check if the type is supported. If not, throw an error.
            if(in_array($uploaded_type, $supported_types)) {

                // Use the WordPress API to upload the file
                $upload = wp_upload_bits($_FILES['wp_custom_attachment_2']['name'], null, file_get_contents($_FILES['wp_custom_attachment_2']['tmp_name']));

                if(isset($upload['error']) && $upload['error'] != 0) {
                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                } else {

                    $post_meta = add_post_meta($post_id, 'wp_custom_attachment_2', $upload);
                    $update_meta = update_post_meta($post_id, 'wp_custom_attachment_2', $upload);
                } // end if/else

            } else {
                wp_die("The file type that you've uploaded is not a GPX.".print_r($supported_types)."=".$uploaded_type);
            } // end if/else

        } // end if
        
        
	foreach ( $names as $key => $name ) {
		if ( ! isset( $_POST[$name] ) ) {
			continue;
		}
                if(!is_array($_POST[$name])){    
                    $value = sanitize_text_field( $_POST[$name] );
                    update_post_meta( $post_id, $name, $value );
                }else{
                    unset($_POST[$name][0]);
                    //wp_die(print_r($_POST[$name]));
                    $value = sanitize_text_field(serialize($_POST[$name]));
                    update_post_meta($post_id, $name, $value);
                }
	}
}

function update_edit_form() {
    echo ' enctype="multipart/form-data"';
} // end update_edit_form

/* Aggancia la funzione di creazione del custom post type "gara". */
add_action( 'init', 'create_gare_post_type' );

/* Aggiunge in sidebar le categorie di questo custom post */
add_action( 'init', 'build_taxonomies', 0 );   

/* Aggiunge Upload GPX File */
add_action('add_meta_boxes', 'add_meta_boxes_corridori');

/* Aggiunge Upload GPX File */
add_action('add_meta_boxes', 'add_custom_meta_boxes');

/* Aggiunge Upload GPX File */
add_action('add_meta_boxes', 'add_gallery_meta_boxes');

/* Aggiunge l'enctype alla form. */
add_action('post_edit_form_tag', 'update_edit_form');

/* Aggancia la funzione di salvataggio del meta box per la pagina di modifica/inserimento di una Ricetta. */
add_action( 'save_post', 'superba_save_gara_meta_box' );