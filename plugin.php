<?php
/*
Plugin Name: Mattias plugin
Description: My own plugin
Author: Mattias
Version: 1.0.0
*/

function mt_custom_post_type() {
	register_post_type('recept',
		array(
			'labels'      => array(
				'name'          => __('Recept', 'mt'),
				'singular_name' => __('Recept', 'mt'),
			),
			'show_in_rest' => true,	
            'public'      => true,
            'supports' => array('title', 'editor', 'thumbnail'),
			'has_archive' => true,
		)
	);
}
add_action('init', 'mt_custom_post_type');

function mt_create_main_protein_taxonomy() {
    $labels = array(
        'name'          => 'Main Proteins',
        'singular_name' => 'Main Protein',
        'menu_name'     => 'Main Protein',
        'all_items'     => 'All Main Proteins',
        'edit_item'     => 'Edit Main Protein',
        'add_new_item'  => 'Add New Main Protein',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'main-protein' ),
        'show_in_rest'      => true, // Gör taxonomin tillgänglig i REST API
    );

    // Koppla taxonomin till post type "recept"
    register_taxonomy( 'main_protein', array( 'recept' ), $args );

    $labels = array(
        'name'          => 'Attribut',
        'singular_name' => 'Attribut',
        'menu_name'     => 'Attribut',
        'all_items'     => 'All Attribut',
        'edit_item'     => 'Edit Attribut',
        'add_new_item'  => 'Add New Attribut',
    );

    $args = array(
        'hierarchical'      => false, // Ställ in som icke-hierarkisk
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'attribut' ),
        'show_in_rest'      => true, // Gör taxonomin tillgänglig i REST API för Gutenberg
    );

    // Koppla taxonomin till post type "recept"
    register_taxonomy( 'attribut', array( 'recept' ), $args );

    $labels = array(
        'name'          => 'Svårighetsgrader',
        'singular_name' => 'Svårighetsgrad',
        'menu_name'     => 'Svårighetsgrad',
        'all_items'     => 'Alla Svårighetsgrader',
        'edit_item'     => 'Redigera Svårighetsgrad',
        'add_new_item'  => 'Lägg till ny Svårighetsgrad',
    );

    $args = array(
        'hierarchical'      => false, // Ställ in som icke-hierarkisk
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'svarighetsgrad' ),
        'show_in_rest'      => true, // Gör taxonomin tillgänglig i REST API för Gutenberg
    );

    // Koppla taxonomin till post type "recept"
    register_taxonomy( 'svarighetsgrad', array( 'recept' ), $args );
}

add_action( 'init', 'mt_create_main_protein_taxonomy', 0 );


add_action('before_create_form', function() {
    ?>
        <div>Kom ihåg att välja produkter</div>
    <?php
});

/*
add_action('create_form', function() {
    ?>
        <div>Just nu kan vi inte ta emot nya recept</div>
    <?php
});

add_action('init', function() {
    remove_action('create_form', 'mt_create_form');
});
*/

function mt_adjust_create_form_html($output) {

    return str_replace('multiple', '', $output);
}

add_filter('create_form_html', 'mt_adjust_create_form_html', 10, 1);

function mt_adjust_create_form_html_options($output, $products) {

    $new_output = '';

    foreach($products as $key => $name) {
        $new_output .= '<option value="'.$key.'">'.$name.' ('.$key.')</option>';
    }

    return $new_output;
}

add_filter('create_form_html_options', 'mt_adjust_create_form_html_options', 10, 2);
