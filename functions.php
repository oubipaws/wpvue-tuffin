<?php
show_admin_bar(false);
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );


function enqueue_js_and_css() {
	$base_url  = esc_url_raw( home_url() );
	$base_path = rtrim( parse_url( $base_url, PHP_URL_PATH ), '/' );

    
    wp_enqueue_style( 'tuffin_tailwind', 'https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css',false,'','all');
    wp_enqueue_style( 'tuffin_base', get_stylesheet_uri() );

	wp_enqueue_script( 'rest-theme-vue', get_template_directory_uri() . '/vue-theme/dist/build.js', array(), '1.0.0', true );
	wp_localize_script( 'rest-theme-vue', 'wp', array(
		'root'      => esc_url_raw( rest_url() ),
		'base_url'  => $base_url,
		'base_path' => $base_path ? $base_path . '/' : '/',
		'nonce'     => wp_create_nonce( 'wp_rest' ),
		'site_name' => get_bloginfo( 'name' ),
		'routes'    => rest_theme_routes(),
	) );
}

add_action( 'wp_enqueue_scripts', 'enqueue_js_and_css' );

function rest_theme_routes() {
	$routes = array();

	$query = new WP_Query( array(
		'post_type'      => 'any',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	) );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$routes[] = array(
				'id'   => get_the_ID(),
				'type' => get_post_type(),
				'slug' => basename( get_permalink() ),
			);
		}
	}
	wp_reset_postdata();

	return $routes;
}


/* PERFORMANCE TWEAKS */
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );
   

function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}
   

function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
   
        $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }
   
    return $urls;
}