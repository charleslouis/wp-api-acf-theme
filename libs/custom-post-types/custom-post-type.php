<?php

function CTP_init() {
	register_post_type( 'CTP', array(
		'labels'            => array(
			'name'                => __( 'CTPs', 'YOUR-TEXTDOMAIN' ),
			'singular_name'       => __( 'CTP', 'YOUR-TEXTDOMAIN' ),
			'all_items'           => __( 'CTPs', 'YOUR-TEXTDOMAIN' ),
			'new_item'            => __( 'New CTP', 'YOUR-TEXTDOMAIN' ),
			'add_new'             => __( 'Add New', 'YOUR-TEXTDOMAIN' ),
			'add_new_item'        => __( 'Add New CTP', 'YOUR-TEXTDOMAIN' ),
			'edit_item'           => __( 'Edit CTP', 'YOUR-TEXTDOMAIN' ),
			'view_item'           => __( 'View CTP', 'YOUR-TEXTDOMAIN' ),
			'search_items'        => __( 'Search CTPs', 'YOUR-TEXTDOMAIN' ),
			'not_found'           => __( 'No CTPs found', 'YOUR-TEXTDOMAIN' ),
			'not_found_in_trash'  => __( 'No CTPs found in trash', 'YOUR-TEXTDOMAIN' ),
			'parent_item_colon'   => __( 'Parent CTP', 'YOUR-TEXTDOMAIN' ),
			'menu_name'           => __( 'CTPs', 'YOUR-TEXTDOMAIN' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_position'		=> 2	
	) );

}
add_action( 'init', 'CTP_init' );

function CTP_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['CTP'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('CTP updated. <a target="_blank" href="%s">View CTP</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'YOUR-TEXTDOMAIN'),
		3 => __('Custom field deleted.', 'YOUR-TEXTDOMAIN'),
		4 => __('CTP updated.', 'YOUR-TEXTDOMAIN'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('CTP restored to revision from %s', 'YOUR-TEXTDOMAIN'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('CTP published. <a href="%s">View CTP</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		7 => __('CTP saved.', 'YOUR-TEXTDOMAIN'),
		8 => sprintf( __('CTP submitted. <a target="_blank" href="%s">Preview CTP</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('CTP scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview CTP</a>', 'YOUR-TEXTDOMAIN'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('CTP draft updated. <a target="_blank" href="%s">Preview CTP</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'CTP_updated_messages' );
