<?php
/*
Plugin Name: Event Manager
Description: Custom Plugin for events.
Version: 1.0
Author: CR
*/

// Register CPT 'Event'
function em_register_event_cpt() {
    $labels = array(
        'name'               => _x( 'Events', 'post type general name', 'event-manager' ),
        'singular_name'      => _x( 'Event', 'post type singular name', 'event-manager' ),
        'menu_name'          => _x( 'Events', 'admin menu', 'event-manager' ),
        'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'event-manager' ),
        'add_new'            => _x( 'Add New', 'event', 'event-manager' ),
        'add_new_item'       => __( 'Add New Event', 'event-manager' ),
        'new_item'           => __( 'New Event', 'event-manager' ),
        'edit_item'          => __( 'Edit Event', 'event-manager' ),
        'view_item'          => __( 'View Event', 'event-manager' ),
        'all_items'          => __( 'All Events', 'event-manager' ),
        'search_items'       => __( 'Search Events', 'event-manager' ),
        'parent_item_colon'  => __( 'Parent Events:', 'event-manager' ),
        'not_found'          => __( 'No events found.', 'event-manager' ),
        'not_found_in_trash' => __( 'No events found in Trash.', 'event-manager' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'event' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor' ),
        'show_in_rest'       => true, // Important for WPGraphQL
        'show_in_graphql'    => true, //  To show in GraphQl
        'graphql_single_name'=> 'Event', // Single name in GraphQL
        'graphql_plural_name'=> 'Events', //Plural name in GraphQl
    );

    register_post_type( 'event', $args );
}
add_action( 'init', 'em_register_event_cpt' );

// Add custom metaboxes
function em_add_event_metaboxes() {
    add_meta_box(
        'em_event_date',
        'Event Date',
        'em_event_date_callback',
        'event',
        'normal',
        'default'
    );

    add_meta_box(
        'em_event_description',
        'Event Description',
        'em_event_description_callback',
        'event',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'em_add_event_metaboxes' );

// Callback 'Date'
function em_event_date_callback( $post ) {
    wp_nonce_field( 'em_save_event_date', 'em_event_date_nonce' );
    $value = get_post_meta( $post->ID, '_em_event_date', true );
    ?>
    <label for="em_event_date">Date:</label>
    <input type="date" id="em_event_date" name="em_event_date" value="<?php echo esc_attr( $value ); ?>" />
    <?php
}

// Callback 'Description'
function em_event_description_callback( $post ) {
    wp_nonce_field( 'em_save_event_description', 'em_event_description_nonce' );
    $value = get_post_meta( $post->ID, '_em_event_description', true );
    ?>
    <label for="em_event_description">Description:</label>
    <textarea id="em_event_description" name="em_event_description" rows="5" style="width:100%;"><?php echo esc_textarea( $value ); ?></textarea>
    <?php
}

// Save metaboxes data
function em_save_event_meta( $post_id ) {
    // Save 'Date'
    if ( isset( $_POST['em_event_date_nonce'] ) && wp_verify_nonce( $_POST['em_event_date_nonce'], 'em_save_event_date' ) ) {
        if ( isset( $_POST['em_event_date'] ) ) {
            update_post_meta( $post_id, '_em_event_date', sanitize_text_field( $_POST['em_event_date'] ) );
        }
    }

    // Save 'Description'
    if ( isset( $_POST['em_event_description_nonce'] ) && wp_verify_nonce( $_POST['em_event_description_nonce'], 'em_save_event_description' ) ) {
        if ( isset( $_POST['em_event_description'] ) ) {
            update_post_meta( $post_id, '_em_event_description', sanitize_textarea_field( $_POST['em_event_description'] ) );
        }
    }
}
add_action( 'save_post_event', 'em_save_event_meta' );

// Register custom fields in WPGraphQL
function em_register_graphql_fields() {
    // 'Date'
    register_graphql_field( 'Event', 'eventDate', [
        'type' => 'String',
        'description' => 'The date of the event',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, '_em_event_date', true );
        }
    ] );

    //  'Description'
    register_graphql_field( 'Event', 'eventDescription', [
        'type' => 'String',
        'description' => 'The description of the event',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, '_em_event_description', true );
        }
    ] );
}
add_action( 'graphql_register_types', 'em_register_graphql_fields' );
