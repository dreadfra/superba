<?php

/**
 * Crea il custom post type "gallery".
 *
 * @see http://codex.wordpress.org/Function_Reference/register_post_type
 */
function create_gallery_post_type() {
	register_post_type( 'galleria', array(
		'labels'             => array(
			'name'               => 'Gallerie Fotografiche',
			'singular_name'      => 'Galleria',
			'menu_name'          => 'Gallerie',
			'name_admin_bar'     => 'Galleria',
			'add_new'            => 'Aggiungi nuovo',
			'add_new_item'       => 'Aggiungi nuovo galleria',
			'new_item'           => 'Nuova galleria',
			'edit_item'          => 'Modifica galleria',
			'view_item'          => 'Visualizza galleria',
			'all_items'          => 'Tutte le gallerie',
			'search_items'       => 'Cerca galleria',
			'parent_item_colon'  => 'Galleria di appartenenza',
			'not_found'          => 'Nessuna galleria trovato',
			'not_found_in_trash' => 'Nessuna galleria trovato nel cestino'
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
                'menu_icon'           => 'dashicons-format-gallery',
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	) );
}

/* Categorie del tipo di post Corridore*/

function build_taxonomies_gallery() {  
    register_taxonomy( 'gallery_categories', 'galleria', array( 'hierarchical' => true, 'label' => 'Evento', 'query_var' => true, 'rewrite' => true ) );
}


/**
 * Funzione che salva il contenuto del meta box per la pagina di modifica/inserimento di una Ricetta.
 *
 * @param int $post_id L'ID della pagina che deve essere salvata.
 */
function gallery_save_corridore_meta_box( $post_id ) {

    
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

 

/* Aggancia la funzione di creazione del custom post type "corridore". */
add_action( 'init', 'create_gallery_post_type' );

/* Aggiunge in sidebar le categorie di questo custom post */
add_action( 'init', 'build_taxonomies_gallery', 0 );   

/* Aggancia la funzione di salvataggio del meta box per la pagina di modifica/inserimento di una Ricetta. */
add_action( 'save_post', 'gallery_save_corridore_meta_box' );