<?php
/**
 * Plugin Name:       Race Guide Plugin
 * Description:       A fantastic plugin for tracking your races this year!
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            David Brousseau
 */

function post_type_races() {

	$supports = array(
		'title',
		'page-attributes',
		'editor',
		'thumbnail',
		'excerpt',
		'revisions',
	);

	$labels = array(
		'name' 				=> _x('Races', 'plural'),
		'singular_name' 	=> _x('Race', 'singular'),
		'menu_name' 		=> _x('Races', 'admin menu'),
		'name_admin_bar' 	=> _x('Races', 'admin bar'),
		'add_new' 			=> _x('Add New', 'add new'),
		'add_new_item' 		=> __('Add New Race'),
		'new_item' 			=> __('New Race'),
		'edit_item' 		=> __('Edit Race'),
		'view_item' 		=> __('View Race'),
		'all_items' 		=> __('All Races'),
		'search_items' 		=> __('Search Races'),
		'not_found' 		=> __('No races found.'),
	);

	$args = array(
		'supports' 		=> $supports,
		'labels' 		=> $labels,
		'public' 		=> true,
		'query_var' 	=> true,
		'rewrite' 		=> array('slug' => 'races'),
		'has_archive' 	=> true,
		'hierarchical' 	=> false,
		'register_meta_box_cb' 	=> 'add_race_metaboxes',
        'taxonomies' 	=> array( 'category' ),
	);
	
	register_post_type('races', $args);

}

add_action('init', 'post_type_races');

// Register Race Taxonomy //

function register_races() {
	register_taxonomy(
        'races-category',
		'races',
        array(
            'label' => __( 'Race Category' ),
            'rewrite' => array( 'slug' => 'races-category' ),
            'hierarchical' => true,
        )
    );
}

add_action('init', 'register_races');

// Add metaboxes //

function add_race_metaboxes() {
	add_meta_box(
		'race_start_time',
		'Race Start Time',
		'race_start_time',
		'races',
		'normal',
		'high'
	);
	add_meta_box(
		'race_distance',
		'Race Distance',
		'race_distance',
		'races',
		'normal',
		'high'
	);
	add_meta_box(
		'race_organizer',
		'Race Organizer',
		'race_organizer',
		'races',
		'normal',
		'high'
	);
	add_meta_box(
		'race_location',
		'Race Location',
		'race_location',
		'races',
		'normal',
		'high'
	);
}

// Distance metabox //
function race_distance() {
	global $post;

	wp_nonce_field( basename( __FILE__ ), 'event_fields' );

	$start_time = get_post_meta( $post->ID, 'distance', true );

	echo '<input type="text" name="distance" value="' . esc_textarea( $start_time )  . '" class="widefat">';
}
// Save Distance metabox //
function save_distances_meta( $post_id, $post ) {

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	if ( ! isset( $_POST['distance'] ) || ! wp_verify_nonce( $_POST['event_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}

	$events_meta['distance'] = esc_textarea( $_POST['distance'] );

	foreach ( $events_meta as $key => $value ) :

		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'save_distances_meta', 1, 2 );

// Start Time metabox //
function race_start_time() {
	global $post;

	wp_nonce_field( basename( __FILE__ ), 'event_fields' );

	$start_time = get_post_meta( $post->ID, 'start_time', true );

	echo '<input type="time" name="start_time" value="' . esc_textarea( $start_time )  . '" class="widefat">';
}
// Save Start Time metabox //
function save_start_times_meta( $post_id, $post ) {

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	if ( ! isset( $_POST['start_time'] ) || ! wp_verify_nonce( $_POST['event_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}

	$events_meta['start_time'] = esc_textarea( $_POST['start_time'] );

	foreach ( $events_meta as $key => $value ) :

		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'save_start_times_meta', 1, 2 );

// Location metabox //
function race_location() {
	global $post;

	wp_nonce_field( basename( __FILE__ ), 'event_fields' );

	$location = get_post_meta( $post->ID, 'location', true );

	echo '<input type="text" name="location" value="' . esc_textarea( $location )  . '" class="widefat">';
}
// Save Location metabox //
function save_locations_meta( $post_id, $post ) {

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	if ( ! isset( $_POST['location'] ) || ! wp_verify_nonce( $_POST['event_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}

	$events_meta['location'] = esc_textarea( $_POST['location'] );

	foreach ( $events_meta as $key => $value ) :

		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'save_locations_meta', 1, 2 );

// Organizer metabox //
function race_organizer() {
	global $post;

	wp_nonce_field( basename( __FILE__ ), 'event_fields' );

	$organizer = get_post_meta( $post->ID, 'organizer', true );

	echo '<input type="text" name="organizer" value="' . esc_textarea( $organizer )  . '" class="widefat">';
}
// Save Organizer metabox //
function save_organizer_meta( $post_id, $post ) {

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	if ( ! isset( $_POST['organizer'] ) || ! wp_verify_nonce( $_POST['event_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}

	$events_meta['organizer'] = esc_textarea( $_POST['organizer'] );

	foreach ( $events_meta as $key => $value ) :

		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'save_organizer_meta', 1, 2 );

// END OF METABOXES


// Refresh wp permalinks action hook on activation //

function plugin_prefix_activation() {
	
    post_type_races();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'plugin_prefix_activation' );


// Refresh wp permalinks action hook on deactivation //

function plugin_prefix_deactivation() {
	
    post_type_races();
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'plugin_prefix_deactivation' );


// Shortcode List Races //

function list_races(){

	$args = array( 
		'numberposts'	=> -1,
		'post_type'		=> 'races',
		'orderby' 		=> 'date',
		'order' 		=> 'DESC'
	);

	$myposts = get_posts($args);

	if( $myposts ):
	
		foreach ($myposts as $mypost): ?>

			<article>

				<h3><a href="<?php echo get_permalink($mypost->ID); ?>"><?php echo get_the_title($mypost->ID); ?></a></h3>
				<strong>Event Name: </strong><?php echo get_the_title($mypost->ID); ?><br>
				<strong>Organizer: </strong><?php echo get_post_meta($mypost->ID, 'organizer', true); ?><br>
				<strong>Location: </strong><?php echo get_post_meta($mypost->ID, 'location', true); ?><br>
				<strong>Distance: </strong><?php echo get_post_meta($mypost->ID, 'distance', true); ?><br>
				<strong>Start Time: </strong><?php echo get_post_meta($mypost->ID, 'start_time', true); ?><br><br>
				<p><?php echo get_the_excerpt($mypost->ID); ?></p>
				<em>Posted by: <?php echo get_the_author(); ?> - <?php echo get_the_date('F j, Y', $mypost->ID); ?></em><br><br>
				
				<a class="button" href="<?php echo get_permalink($mypost->ID); ?>">Read More</a>
			
			</article>

		<?php endforeach;
		
		wp_reset_postdata();
		
	endif;

}

add_shortcode('list-races', 'list_races');

?>