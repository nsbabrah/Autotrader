<?php
/**
 * Plugin Name: Select Authors
 * Plugin URI: http://localhost
 * Description: A simple plugin for selecting authors with post.
 * Version: 1.0
 * Author: Meta Box
 * Author URI: http://localhost
 */

 /**
 * Register meta boxes.
 */

// add_action( 'save_post', 'save_author' );
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */


function add_custom_box() {
	$screens = [ 'post'];
	foreach ( $screens as $screen ) {
		add_meta_box(
			'wporg_box_id',                 // Unique ID
			'Developers',      // Box title
			'custom_box_html',  // Content callback, must be of type callable
			$screen,
            'side',                            // Post type
		);
	}
}
add_action( 'add_meta_boxes', 'add_custom_box' );


// 


function custom_box_html( $post ) {

    $user_query = new WP_User_Query([
        'role' => 'author',
        'number' => '20',
        'fields' => [
            'display_name',
            'ID',
        ],
    ]);

    $editors = $user_query->get_results();
    $value = get_post_meta( $post->ID, 'my_meta_box_check', true );
	?>
    	
			<!-- <label for="my_meta_box_check[]">Editor:</label>
			<select name="my_meta_box_check[]" id="post_editor"> -->
				<!-- <option> - Select One -</option> -->

                
				<?php
				
					foreach ($editors as $editor) {
						// echo '<option name="wporg_field" value="'.$editor->ID.'" '.selected(get_post_meta(get_the_ID(), '', true), $editor->ID, false).'>'.$editor->display_name.'</option>';
					
                    echo'<input type="checkbox" id="my_meta_box_check" 
                    name="my_meta_box_check" value="'.$editor->ID.'" '.selected(get_post_meta(get_the_ID(), 'my_meta_box_check', true), $editor->ID, false).'>'.$editor->display_name.'';
                    }

				?>
			</select>

	<?php
}
function save_postdata( $post_id ) {
	if ( isset( $_POST['my_meta_box_check'] ) && $_POST['my_meta_box_check'] != '' ) {
		update_post_meta(
			$post_id,
            'my_meta_box_check',
			$_POST[$editor->ID]
		);
	}
}
add_action( 'save_post', 'save_postdata' );


