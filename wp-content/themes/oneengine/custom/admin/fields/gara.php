<?php
	$names = array(
		'link_url'   => "link_url"
	);

	$values = array();

	foreach ( $names as $key => $name ) {
		$values[$key] = get_post_meta( $post_id, $name, true );
	}
?>

<div class="thb-field thb-page-field">
    <div class="thb-gara-link">
        <div>
            <input type="text" placeholder="URL (http://)" value="<?php echo esc_attr( $values['link_url'] ); ?>" id="<?php echo esc_attr( $names['link_url'] ); ?>" name="<?php echo esc_attr( $names['link_url'] ); ?>">
        </div>
    </div>
</div>