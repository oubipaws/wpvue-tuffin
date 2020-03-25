<?php
show_admin_bar(false);
remove_action ('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_generator');

add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );

$tuffin_options = array(
	"general" => array(
		array("Label" => "Google Analytics UA", "Name" => "tuffin_general_ga_ua", "Type" => "Text"),
	),	
	"social" => array(
		array("Label" => "Twitter", "Name" => "tuffin_social_twitter", "Type" => "Text"),
		array("Label" => "Facebook", "Name" => "tuffin_social_facebook", "Type" => "Text"),
		array("Label" => "LinkedIn", "Name" => "tuffin_social_linkedin", "Type" => "Text"),
		array("Label" => "CodePen", "Name" => "tuffin_social_codepen", "Type" => "Text"),
		array("Label" => "Dribbble", "Name" => "tuffin_social_dribbble", "Type" => "Text"),
		array("Label" => "Instagram", "Name" => "tuffin_social_instagram", "Type" => "Text"),
	)
);

add_action( 'rest_api_init', 'tuffin_api_register' );
function tuffin_api_register() {
	register_rest_route( 'tuffin/v1', '/options', array(
		'methods'  => 'GET',
		'callback' => 'toffin_api_options_callback',
	) );
}

function toffin_api_options_callback() {
	global $tuffin_options;

	$site_options = array();
	foreach($tuffin_options as $key => $val){
		foreach($val as $to_single){
			$site_options[$key][$to_single['Name']] = get_option($to_single['Name']);
		}
	}

	return $site_options;
}
 
function enqueue_js_and_css() {
	$base_url  = esc_url_raw( home_url() );
	$base_path = rtrim( parse_url( $base_url, PHP_URL_PATH ), '/' );
	
	if(!is_admin()){
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
}
add_action( 'wp_enqueue_scripts', 'enqueue_js_and_css' );

function enqueue_js_and_css_admin(){
	wp_enqueue_style( 'tuffin_admin', get_template_directory_uri() . '/assets/css/tuffin-admin.css',false,'','all');
}
add_action( 'admin_enqueue_scripts', 'enqueue_js_and_css_admin' );

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

function tuffin_generate_settings() {
	global $tuffin_options;
	foreach($tuffin_options as $to){
		foreach($to as $to_single){
			add_option( $to_single['Name'], '');
		}
	}
}
add_action( 'admin_init', 'tuffin_generate_settings' );

function tuffin_generate_options_page() {
	add_options_page('Theme Settings', 'Theme Settings', 'manage_options', 'tuffin_options', 'tuffin_options_page');
}
add_action('admin_menu', 'tuffin_generate_options_page');

function tuffin_options_page(){
	global $tuffin_options;

	if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
	}else{
		$active_tab = "general";
	}

	if($_POST){
		foreach($_POST as $key => $val){
			if($key != "submit"){
				update_option( $key, $val);
			}
		}
	}
	?>
	<div class="wrap"> 
	 	<h2>Tuffin Theme Options</h2>
	 	
	 	<h2 class="nav-tab-wrapper">
			 <a href="?page=tuffin_options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General Options</a>
		 	<a href="?page=tuffin_options&tab=social" class="nav-tab <?php echo $active_tab == 'social' ? 'nav-tab-active' : ''; ?>">Social Options</a>
	 	</h2>
	  
	 	<form method="post">
		 	<div class="tuffin_options_container">
				<?php
				foreach($tuffin_options[$active_tab] as $to){
					if($to['Type'] == "Text"){
					?>
					<div class="tuffin_options_container-field-group">
						<label for="<?php echo $to['Name']; ?>"><?php echo $to['Label']; ?></label>
						<input type="text" id="<?php echo $to['Name']; ?>" name="<?php echo $to['Name']; ?>" value="<?php echo get_option($to['Name']); ?>" />
					</div>
					<?php
					}
				}					
				
				submit_button(); 
				?>  
			</div>
		</form>  
 	</div>
	<?php
} 