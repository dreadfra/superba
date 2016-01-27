<?php

/**
 * Crea il custom post type "corridore".
 *
 * @see http://codex.wordpress.org/Function_Reference/register_post_type
 */
function create_corridore_post_type() {
	register_post_type( 'corridore', array(
		'labels'             => array(
			'name'               => 'Corridore',
			'singular_name'      => 'Corridore',
			'menu_name'          => 'Corridori',
			'name_admin_bar'     => 'Corridore',
			'add_new'            => 'Aggiungi nuovo',
			'add_new_item'       => 'Aggiungi nuovo corridore',
			'new_item'           => 'Nuovo corridore',
			'edit_item'          => 'Modifica corridore',
			'view_item'          => 'Visualizza corridore',
			'all_items'          => 'Tutti i corridori',
			'search_items'       => 'Cerca corridore',
			'parent_item_colon'  => 'Corridore di appartenenza',
			'not_found'          => 'Nessun corridore trovato',
			'not_found_in_trash' => 'Nessun corridore trovato nel cestino'
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
                'menu_icon'           => 'dashicons-admin-users',
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	) );
}

/* Categorie del tipo di post Corridore*/

function build_taxonomies_corridore() {  
    register_taxonomy( 'corridore_categories', 'corridore', array( 'hierarchical' => true, 'label' => 'Categoria Corridore', 'query_var' => true, 'rewrite' => true ) );
    register_taxonomy( 'corridore_carica', 'corridore', array( 'hierarchical' => true, 'label' => 'Carica Corridore', 'query_var' => true, 'rewrite' => true ) );    
}


/**
 * Aggiunge un meta box contenente le opzioni inerenti alla pagina di modifica/inserimento di una Ricetta.
 *
 * @see http://codex.wordpress.org/Function_Reference/add_meta_box
 */
function superba_create_corridore_meta_box() {
	$admin_template = thb_get_admin_template();

	if ( $admin_template !== 'single-corridore.php' ) {
		return;
	}

	add_meta_box(
		'superba_corridore_meta_box',
		'Informazioni Aggiuntive',
		'superba_corridore_meta_box',
		'corridore'
	);
}


/**
 * Funzione che restituisce l'output del meta box per la pagina di modifica/inserimento di una Ricetta.
 */
function superba_corridore_meta_box() {
	global $post;
	$post_id = 0;

	if ( $post ) {
		$post_id = $post->ID;
	}

	wp_nonce_field( 'superba_corridore_meta_box', 'superba_corridore_meta_box_nonce' );

	echo '<div class="thb">';

		
	$names = array(
		'anno'   => "anno",
                'link_strava'   => "link_strava"
	);

	$values = array();

	foreach ( $names as $key => $name ) {
		$values[$key] = get_post_meta( $post_id, $name, true );
	}
	?>

	<div class="thb-field thb-page-field">
		<div class="thb-corridore-link">
			<div>
				<label for="<?php echo esc_attr( $names['anno'] ); ?>">Anno di Nascita</label>
		                <input type="text" placeholder="anno di nascita" value="<?php echo esc_attr( $values['anno'] ); ?>" id="<?php echo esc_attr( $names['anno'] ); ?>" name="<?php echo esc_attr( $names['anno'] ); ?>">
			</div>
		        <div>
				<label for="<?php echo esc_attr( $names['link_strava'] ); ?>">URL Profilo di Strava</label>
		                <input type="text" placeholder="URL Profilo di Strava" value="<?php echo esc_attr( $values['link_strava'] ); ?>" id="<?php echo esc_attr( $names['link_strava'] ); ?>" name="<?php echo esc_attr( $names['link_strava'] ); ?>">
			</div>
		</div>

	
	</div>
	<?
	echo '</div>';
}

/**
 * Funzione che salva il contenuto del meta box per la pagina di modifica/inserimento di una Ricetta.
 *
 * @param int $post_id L'ID della pagina che deve essere salvata.
 */
function superba_save_corridore_meta_box( $post_id ) {

    
    if ( ! isset( $_POST['superba_corridore_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['superba_corridore_meta_box_nonce'], 'superba_corridore_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
	}
        
        if ( isset( $_POST['post_type'] ) ) {
            if ( 'corridore' == $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                        return;
                }
            }
            else {
                    return;
            }
	}	
        
        $admin_template = thb_get_admin_template();

	if ( $admin_template !== 'single-corridore.php' ) {
		return;
	}
        
        $names = array(
		'anno'   => "anno",
                'link_strava'   => "link_strava"
	);
        
        foreach ( $names as $key => $name ) {
            if ( ! isset( $_POST[$name] ) ) {
                    continue;
            }

            $value = sanitize_text_field( $_POST[$name] );
            update_post_meta( $post_id, $name, $value );
                
	}
}

/* Aggancia la funzione di creazione del meta box per la pagina di modifica/inserimento di una Ricetta. */
add_action( 'add_meta_boxes', 'superba_create_corridore_meta_box' );

/* Aggancia la funzione di creazione del custom post type "corridore". */
add_action( 'init', 'create_corridore_post_type' );

/* Aggiunge in sidebar le categorie di questo custom post */
add_action( 'init', 'build_taxonomies_corridore', 0 );   

/* Aggancia la funzione di salvataggio del meta box per la pagina di modifica/inserimento di una Ricetta. */
add_action( 'save_post', 'superba_save_corridore_meta_box' );
