<?php
/**
 * WordPress booster V 3.0 by tagDiv
 */

do_action('td_wp_booster_before');


if (TD_DEPLOY_MODE == 'dev') {
    require_once('external/kint/Kint.class.php');
}


// theme specific config values
require_once('td_global.php');
require_once('td_util.php');

// load the api

/* @td_get_inline */
require_once('td_api_base.php');
require_once('td_api_block.php');
require_once('td_api_block_template.php');
require_once('td_api_category_template.php');
require_once('td_api_category_top_posts_style.php');
require_once('td_api_footer_template.php');
require_once('td_api_header_style.php');
require_once('td_api_module.php');
require_once('td_api_single_template.php');
require_once('td_api_smart_list.php');
require_once('td_api_thumb.php');
require_once('td_api_top_bar_template.php');
require_once('td_api_tinymce_formats.php');
require_once('td_api_autoload.php');
/* @td_end_get_inline */

do_action('td_global_after');

require_once('td_block_template.php');          // block template base class
require_once('td_category_template.php');       // block template base class
require_once('td_category_top_posts_style.php');       // base class
require_once('td_global_blocks.php');
require_once('td_menu.php');            //theme menu support
require_once('td_social_icons.php');    // The social icons
require_once('td_review.php');          // Review js buffer class
require_once('td_page_generator.php');  //
require_once('td_js_buffer.php');       // page generator
require_once('td_block_layout.php');    // block layout
require_once('td_template_layout.php'); // template layout
require_once('td_unique_posts.php');    //unique posts (uses hooks + do_action('td_wp_boost_new_module'); )
require_once('td_data_source.php');      // data source
require_once('td_css_compiler.php');  // css compiler
require_once('td_css_inline.php');  // css compiler
require_once('td_page_views.php'); // page views counter
require_once('td_module.php');           // module builder
require_once('td_module_single_base.php');           // module single builder
require_once('td_block.php');            // block builder
require_once('td_cake.php');
require_once('td_widget_builder.php');  // widget builder
require_once('td_first_install.php');  //the code that runs on the first install of the theme
require_once("td_fonts.php"); //fonts support
require_once("td_ajax.php"); // ajax
require_once('td_video_support.php');  // video thumbnail support
require_once('td_video_playlist_support.php'); //video playlist support
require_once('td_css_buffer.php'); // css buffer class
require_once('td_js_generator.php');  // ~ app config ~ css generator

require_once('td_demo_site.php');  // the demo site
require_once('td_smart_list.php');  //the smart lists
require_once('td_generic_filter_builder.php');  // generic filter buider class
require_once('td_login.php');  // modal window for user login
require_once('td_more_article_box.php');  //handles more articles box
require_once('td_block_widget.php');  //used to make widgets from our blocks



// Every class after this (that has td_ in the name) is auto loaded
require_once('td_autoload_classes.php');  //used to autoload classes [modules, blocks]


// add auto loading classes
td_api_autoload::add('td_background_render', td_global::$get_template_directory . '/includes/wp_booster/td_background_render.php');

require_once('td_background.php');  //the background support



/*
 * if debug - the constants are used to load the live color customizer (demo) and to remove the tf bar on ios devices
 */
if (TD_DEBUG_LIVE_THEME_STYLE) {
    require_once('td_theme_style.php' );
}

if (TD_DEBUG_IOS_REDIRECT) {
    require_once('td_ios_redirect.php' );
}


do_action('td_wp_booster_loaded'); //used by our plugins



/* ----------------------------------------------------------------------------
 * Add theme support for features
 */
add_theme_support('post-thumbnails');
add_theme_support('post-formats', array('video'));
add_theme_support('automatic-feed-links');
add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
add_theme_support('woocommerce');




/* ----------------------------------------------------------------------------
 * front end js composer file @todo - check it why is this way
 */
add_action('wp_enqueue_scripts',  'load_js_composer_front', 1000);
function load_js_composer_front() {
    wp_enqueue_style('js_composer_front');
}




/* ----------------------------------------------------------------------------
 * front end css files
 */
add_action('wp_enqueue_scripts', 'load_front_css', 1001);   // 1001 priority because visual composer uses 1000
function load_front_css() {
    if (TD_DEBUG_USE_LESS) {
        wp_enqueue_style('td-theme', td_global::$get_template_directory_uri . '/td_less_style.css.php',  '', TD_THEME_VERSION, 'all' );
    } else {
        wp_enqueue_style('td-theme', get_stylesheet_uri(), '', TD_THEME_VERSION, 'all' );
    }
}

/** ---------------------------------------------------------------------------
 * front end user compiled css @see  td_css_generator.php
 */
function td_include_user_compiled_css() {
    if (!is_admin()) {
        td_css_buffer::add_to_header(td_util::get_option('tds_user_compile_css'));
    }
}
add_action('wp_head', 'td_include_user_compiled_css', 10);


/* ----------------------------------------------------------------------------
 * CSS fonts / google fonts in front end
 */
add_action('wp_enqueue_scripts', 'td_load_css_fonts');
function td_load_css_fonts() {
    if ((defined('TD_DEPLOY_MODE') and (TD_DEPLOY_MODE == 'demo')) or defined('TD_SPEED_BOOSTER')) {
        /*
         * on demo and dev we load only the latin fonts
         *
         * modify this collection if you want to optimize the fonts loaded when you have the speed booster enabled
         *
         * collection url -> : http://www.google.com/fonts#ReviewPlace:refine/Collection:PT+Sans:400,700,400italic|Ubuntu:400,400italic|Open+Sans:400italic,400|Oswald:400,700|Roboto+Condensed:400italic,700italic,400,700
         */
        wp_enqueue_style('google-font-rest', td_global::$http_or_https . '://fonts.googleapis.com/css?family=Roboto+Condensed:400italic,700italic,700,400|Open+Sans:400italic,600italic,700italic,400,700,600'); //used on menus/small text
    } else {
        $td_user_fonts_list = array();
        $td_user_fonts_db = td_util::get_option('td_fonts');

        if(!empty($td_user_fonts_db)) {
            foreach($td_user_fonts_db as $td_font_setting_key => $td_font_setting_val) {
                if(!empty($td_font_setting_val) and !empty($td_font_setting_val['font_family'])) {
                    $td_user_fonts_list[] = $td_font_setting_val['font_family'];
                }
            }
        }

        // enqueue the fonts
        if(!in_array('g_438', $td_user_fonts_list)) {//'g_438', //Open Sans
            wp_enqueue_style('google-font-opensans', td_global::$http_or_https . '://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic'); //used on menus/small text
        }

        if(!in_array('g_522', $td_user_fonts_list)) {//'g_522', //Roboto condensed
            wp_enqueue_style('google-roboto-cond', td_global::$http_or_https . '://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic'); //used on content
        }
    }

    /*
     * add the google link for fonts used by user
     *
     * td_fonts_css_files : holds the link to fonts.googleapis.com in the database
     *
     * this section will appear in the header of the source of the page
     */
    $td_fonts_css_files = td_util::get_option('td_fonts_css_files');
    if(!empty($td_fonts_css_files)) {
        wp_enqueue_style('google-fonts-style', td_global::$http_or_https . $td_fonts_css_files);
    }
}




/* ----------------------------------------------------------------------------
 * front end javascript files
 */
add_action('wp_enqueue_scripts', 'load_front_js');
function load_front_js() {
    $td_deploy_mode = TD_DEPLOY_MODE;

    //switch the deploy mode to demo if we have tagDiv speed booster
    if (defined('TD_SPEED_BOOSTER')) {
        $td_deploy_mode = 'demo';
    }

    switch ($td_deploy_mode) {
        default: //deploy
            wp_enqueue_script('td-site', td_global::$get_template_directory_uri . '/js/tagdiv_theme.js', array('jquery'), TD_THEME_VERSION, true);
            break;

        case 'demo':
            wp_enqueue_script('td-site-min', td_global::$get_template_directory_uri . '/js/tagdiv_theme.min.js', array('jquery'), TD_THEME_VERSION, true);
            break;

        case 'dev':
            // dev version - load each file separately
            $last_js_file_id = '';
            foreach (td_global::$js_files as $js_file_id => $js_file) {
                if ($last_js_file_id == '') {
                    wp_enqueue_script($js_file_id, td_global::$get_template_directory_uri . $js_file, array('jquery'), TD_THEME_VERSION, true); //first, load it with jQuery dependency
                } else {
                    wp_enqueue_script($js_file_id, td_global::$get_template_directory_uri . $js_file, array($last_js_file_id), TD_THEME_VERSION, true);  //not first - load with the last file dependency
                }
                $last_js_file_id = $js_file_id;
            }
            break;

    }

    //add the comments reply to script on single pages
    if (is_singular()) {
        wp_enqueue_script('comment-reply');
    }
}




/* ----------------------------------------------------------------------------
 * css for wp-admin / backend
 */
add_action('admin_enqueue_scripts', 'load_wp_admin_css');
function load_wp_admin_css() {
    //load the panel font in wp-admin
    $td_protocol = is_ssl() ? 'https' : 'http';
    wp_enqueue_style('google-font-ubuntu', $td_protocol . '://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic&amp;subset=latin,cyrillic-ext,greek-ext,greek,latin-ext,cyrillic'); //used on content
    wp_enqueue_style('td-wp-admin-td-panel-2', td_global::$get_template_directory_uri . '/includes/wp_booster/wp-admin/css/wp-admin.css', false, TD_THEME_VERSION, 'all' );

    //load the colorpicker
    wp_enqueue_style( 'wp-color-picker' );
}




/* ----------------------------------------------------------------------------
 * js for wp-admin / backend   admin js
 */
add_action('admin_enqueue_scripts', 'load_wp_admin_js');
function load_wp_admin_js() {

// dev version - load each file separately
    $last_js_file_id = '';
    foreach (td_global::$js_files_for_wp_admin as $js_file_id => $js_file) {
        if ($last_js_file_id == '') {
            wp_enqueue_script($js_file_id, td_global::$get_template_directory_uri . $js_file, array('jquery', 'wp-color-picker'), TD_THEME_VERSION, false); //first, load it with jQuery dependency
        } else {
            wp_enqueue_script($js_file_id, td_global::$get_template_directory_uri . $js_file, array($last_js_file_id), TD_THEME_VERSION, false);  //not first - load with the last file dependency
        }
        $last_js_file_id = $js_file_id;
    }

    wp_enqueue_script('thickbox');
    add_thickbox();
}




/* ----------------------------------------------------------------------------
 * Prepare head related links
 */
add_action('wp_head', 'hook_wp_head', 1);  //hook on wp_head -> 1 priority, we are first
function hook_wp_head() {
    if (is_single()) {
        global $post;

        // facebook sharing fix for videos, we add the custom meta data
        if (has_post_thumbnail($post->ID)) {
            $td_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
            if (!empty($td_image[0])) {
                echo '<meta property="og:image" content="' .  $td_image[0] . '" />';
            }
        }

        // show author meta tag on sigle pages
        $td_post_author = get_the_author_meta('display_name', $post->post_author);
        if ($td_post_author) {
            echo '<meta name="author" content="'.$td_post_author.'">'."\n";
        }
    }

    // fav icon support
    $tds_favicon_upload = td_util::get_option('tds_favicon_upload');
    if (!empty($tds_favicon_upload)) {
        echo '<link rel="icon" type="image/png" href="' . $tds_favicon_upload . '">';
    }


    // ios bookmark icon support
    $tds_ios_76 = td_util::get_option('tds_ios_icon_76');
    $tds_ios_120 = td_util::get_option('tds_ios_icon_120');
    $tds_ios_152 = td_util::get_option('tds_ios_icon_152');
    $tds_ios_114 = td_util::get_option('tds_ios_icon_114');
    $tds_ios_144 = td_util::get_option('tds_ios_icon_144');

    if(!empty($tds_ios_76)) {
        echo '<link rel="apple-touch-icon-precomposed" sizes="76x76" href="' . $tds_ios_76 . '"/>';
    }

    if(!empty($tds_ios_120)) {
        echo '<link rel="apple-touch-icon-precomposed" sizes="120x120" href="' . $tds_ios_120 . '"/>';
    }

    if(!empty($tds_ios_152)) {
        echo '<link rel="apple-touch-icon-precomposed" sizes="152x152" href="' . $tds_ios_152 . '"/>';
    }

    if(!empty($tds_ios_114)) {
        echo '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . $tds_ios_114 . '"/>';
    }

    if(!empty($tds_ios_144)) {
        echo '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . $tds_ios_144 . '"/>';
    }




	if (TD_DEBUG_USE_LESS) {
		$style_sheet_path = td_global::$get_template_directory_uri . '/td_less_style.css.php';
	} else {
		$style_sheet_path = get_stylesheet_uri();
	}

	ob_start();
	?>

	<script>

		(function(){
			var html_jquery_obj = jQuery('html');

			if (html_jquery_obj.length && (html_jquery_obj.is('.ie8') || html_jquery_obj.is('.ie9'))) {

				jQuery.get('<?php echo $style_sheet_path ?>', function(data) {

					var arr_splits = data.split('#td_css_split_separator');

					var arr_length = arr_splits.length;

					if (arr_length > 1) {
						for (var i = 0; i < arr_length; i++) {

							jQuery('head').append('<style>' + arr_splits[i] + '</style>');
						}
					}
				});
			}
		})();

	</script>



	<?php
	$script_buffer = ob_get_clean();
	$js_script = "\n". td_util::remove_script_tag($script_buffer);
	td_js_buffer::add_to_header($js_script);




	// lazy loading images - animation effect
	$tds_lazy_loading_image = td_util::get_option('tds_lazy_loading_image');

	if (empty($tds_lazy_loading_image)) {
        ob_start();
        ?>
        <script>
            jQuery(window).load(function() {

                // if the theme has td_animation_stack, initialize it
                if (typeof window.td_animation_stack !== 'undefined') {
                    window.td_animation_stack.init();
                }
            });
        </script>
        <?php
        $buffer = ob_get_clean();
        $js = "\n". td_util::remove_script_tag($buffer);
        td_js_buffer::add_to_footer($js);

		add_filter('body_class','td_hook_add_custom_body_class');
	}
}

function td_hook_add_custom_body_class($classes) {
	$classes[] = 'lazy-animation';
	return $classes;
}



/* ----------------------------------------------------------------------------
 * localization
 */
add_action('after_setup_theme', 'td_load_text_domains');
function td_load_text_domains() {
    load_theme_textdomain(TD_THEME_NAME, get_template_directory() . '/translation');

    // theme specific config values
    require_once('td_translate.php');

}




/* ----------------------------------------------------------------------------
    Custom <title> wp_title - seo
 */
add_filter( 'wp_title', 'td_wp_title', 10, 2 );
function td_wp_title( $title, $sep ) {
    global $paged, $page;

    if ( is_feed() )
        return $title;

    // Add the site name.
    $title .= get_bloginfo( 'name' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ( $paged >= 2 || $page >= 2 )
        $title = "$title $sep " . __td('Page') . ' ' .  max( $paged, $page );

    return $title;
}




/**  ----------------------------------------------------------------------------
    archive widget - adds .current class in the archive widget and maybe it's used in other places too!
 */
add_filter('get_archives_link', 'theme_get_archives_link');
function theme_get_archives_link ( $link_html ) {
    global $wp;
    static $current_url;
    if ( empty( $current_url ) ) {
        $current_url = esc_url(add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) ));
    }
    if ( stristr( $current_url, 'page' ) !== false ) {
        $current_url = substr($current_url, 0, strrpos($current_url, 'page'));
    }
    if ( stristr( $link_html, $current_url ) !== false ) {
        $link_html = preg_replace( '/(<[^\s>]+)/', '\1 class="current"', $link_html, 1 );
    }
    return $link_html;
}




/*  ----------------------------------------------------------------------------
    add span wrap for category number in widget
 */
add_filter('wp_list_categories', 'cat_count_span');
function cat_count_span($links) {
    $links = str_replace('</a> (', '<span class="td-widget-no">', $links);
    $links = str_replace(')', '</span></a>', $links);
    return $links;
}




/*  ----------------------------------------------------------------------------
    remove gallery style css
 */
add_filter( 'use_default_gallery_style', '__return_false' );




/*  ----------------------------------------------------------------------------
    more text
 */
add_filter('excerpt_more', 'new_excerpt_more');
function new_excerpt_more($text){
    return ' ';
}




/*  ----------------------------------------------------------------------------
    editor style
 */
add_action( 'after_setup_theme', 'my_theme_add_editor_styles' );
function my_theme_add_editor_styles() {
    add_editor_style('td_less_editor-style.php');
}




/*  ----------------------------------------------------------------------------
    the bottom code for css
 */
add_action('wp_footer', 'td_bottom_code');
function td_bottom_code() {
    global $post;

    // try to detect speed booster
    $speed_booster = '';
    if (defined('TD_SPEED_BOOSTER')) {
        $speed_booster = 'Speed booster: ' . TD_SPEED_BOOSTER . "\n";
    }

    echo '

    <!--

        Theme: ' . TD_THEME_NAME .' by tagDiv 2015
        Version: ' . TD_THEME_VERSION . ' (rara)
        Deploy mode: ' . TD_DEPLOY_MODE . '
        ' . $speed_booster . '
        uid: ' . uniqid() . '
    -->

    ';


    // get and paste user custom css
    $td_custom_css = stripslashes(td_util::get_option('tds_custom_css'));


    // get the custom css for the responsive values
    $responsive_css_values = array();
    foreach (td_global::$theme_panel_custom_css_fields_list as $option_id => $css_params) {
        $responsive_css = td_util::get_option($option_id);
        if ($responsive_css != '') {
            $responsive_css_values[$css_params['media_query']] = $responsive_css;
        }
    }



    // check if we have to show any css
    if (!empty($td_custom_css) or count($responsive_css_values) > 0) {
        $css_buffy = PHP_EOL . '<!-- Custom css form theme panel -->';
        $css_buffy .= PHP_EOL . '<style type="text/css" media="screen">';

        //paste custom css
        if(!empty($td_custom_css)) {
            $css_buffy .= PHP_EOL . '/* custom css theme panel */' . PHP_EOL;
            $css_buffy .= $td_custom_css . PHP_EOL;
        }

        foreach ($responsive_css_values as $media_query => $responsive_css) {
            $css_buffy .= PHP_EOL . PHP_EOL . '/* custom responsive css from theme panel (Advanced CSS) */';
            $css_buffy .= PHP_EOL . $media_query . ' {' . PHP_EOL;
            $css_buffy .= $responsive_css;
            $css_buffy .= PHP_EOL . '}' . PHP_EOL;
        }
        $css_buffy .= '</style>' . PHP_EOL . PHP_EOL;

        // echo the css buffer
        echo $css_buffy;
    }


    //get and paste user custom javascript
    $td_custom_javascript = stripslashes(td_util::get_option('tds_custom_javascript'));
    if(!empty($td_custom_javascript)) {
        echo '<script type="text/javascript">'
            .$td_custom_javascript.
            '</script>';
    }

    //AJAX POST VIEW COUNT
    if(td_util::get_option('tds_ajax_post_view_count') == 'enabled') {

        //Ajax get & update counter views
        if(is_single()) {
            //echo 'post page: '.  $post->ID;
            if($post->ID > 0) {
                td_js_buffer::add_to_footer('
                jQuery().ready(function jQuery_ready() {
                    td_ajax_count.td_get_views_counts_ajax("post",' . json_encode('[' . $post->ID . ']') . ');
                });
            ');
            }
        }
    }
}




/*  ----------------------------------------------------------------------------
    google analytics
 */
add_action('wp_head', 'td_header_analytics_code', 40);
function td_header_analytics_code() {
    $td_analytics = td_util::get_option('td_analytics');
    echo stripslashes($td_analytics);

}




/*  ----------------------------------------------------------------------------
    Append page slugs to the body class
 */
add_filter('body_class', 'add_slug_to_body_class');
function add_slug_to_body_class( $classes ) {
    global $post;
    if( is_home() ) {
        $key = array_search( 'blog', $classes );
        if($key > -1) {
            unset( $classes[$key] );
        };
    } elseif( is_page() ) {
        $classes[] = sanitize_html_class( $post->post_name );
    } elseif(is_singular()) {
        $classes[] = sanitize_html_class( $post->post_name );
    };

    $i = 0;
    foreach ($classes as $key => $value) {
        $pos = strripos($value, 'span');
        if ($pos !== false) {
            unset($classes[$i]);
        }

        $pos = strripos($value, 'row');
        if ($pos !== false) {
            unset($classes[$i]);
        }

        $pos = strripos($value, 'container');
        if ($pos !== false) {
            unset($classes[$i]);
        }
        $i++;
    }
    return $classes;
}




/*  ----------------------------------------------------------------------------
    remove span row container classes from post_class()
 */
add_filter('post_class', 'add_slug_to_post_class');
function add_slug_to_post_class($classes) {
    $i = 0;
    foreach ($classes as $key => $value) {
        $pos = strripos($value, 'span');
        if ($pos !== false) {
            unset($classes[$i]);
        }

        $pos = strripos($value, 'row');
        if ($pos !== false) {
            unset($classes[$i]);
        }

        $pos = strripos($value, 'container');
        if ($pos !== false) {
            unset($classes[$i]);
        }
        $i++;
    }
    return $classes;
}




/*  -----------------------------------------------------------------------------
    Our custom admin bar
 */
add_action('admin_bar_menu', 'td_custom_menu', 1000);
function td_custom_menu() {
    global $wp_admin_bar;
    if(!is_super_admin() || !is_admin_bar_showing()) return;

    $wp_admin_bar->add_menu(array(
        'parent' => 'site-name',
        'title' => '<span class="td-admin-bar-red">Theme panel</span>',
        'href' => admin_url('admin.php?page=td_theme_panel'),
        'id' => 'td-menu1'
    ));

    $wp_admin_bar->add_menu( array(
        'id'   => 'our_support_item',
        'meta' => array('title' => 'Theme support', 'target' => '_blank'),
        'title' => 'Theme support',
        'href' => 'http://forum.tagdiv.com' ));

}




/*  -----------------------------------------------------------------------------
 * Add prev and next links to a numbered link list - the pagination on single.
 */
add_filter('wp_link_pages_args', 'wp_link_pages_args_prevnext_add');
function wp_link_pages_args_prevnext_add($args)
{
    global $page, $numpages, $more, $pagenow;

    if (!$args['next_or_number'] == 'next_and_number')
        return $args; # exit early

    $args['next_or_number'] = 'number'; # keep numbering for the main part
    if (!$more)
        return $args; # exit early

    if($page-1) # there is a previous page
        $args['before'] .= _wp_link_page($page-1)
            . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
        ;

    if ($page<$numpages) # there is a next page
        $args['after'] = _wp_link_page($page+1)
            . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
            . $args['after']
        ;

    return $args;
}




/*  -----------------------------------------------------------------------------
 * Add, on theme body element, the custom classes from Theme Panel -> Custom Css -> Custom Body class(s)
 */
add_filter('body_class','td_my_custom_class_names_on_body');
function td_my_custom_class_names_on_body($classes) {
    //get the custom classes from theme options
    $custom_classes = td_util::get_option('td_body_classes');

    if(!empty($custom_classes)) {
        // add 'custom classes' to the $classes array
        $classes[] = $custom_classes;
    }

    // return the $classes array
    return $classes;
}




/*  ----------------------------------------------------------------------------
    used by ie8 - there is no other way to add js for ie8 only
 */
add_action('wp_head', 'add_ie_html5_shim');
function add_ie_html5_shim () {
    echo '<!--[if lt IE 9]>';
    echo '<script src="' . td_global::$http_or_https . '://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
    echo '<![endif]-->
    ';
}




/*  ----------------------------------------------------------------------------
    add extra contact information for author in wp-admin -> users -> your profile
 */
add_filter('user_contactmethods', 'td_extra_contact_info_for_author');
function td_extra_contact_info_for_author($contactmethods) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
    foreach (td_social_icons::$td_social_icons_array as $td_social_id => $td_social_name) {
        $contactmethods[$td_social_id] = $td_social_name;
    }
    return $contactmethods;
}








/* ----------------------------------------------------------------------------
 * shortcodes in widgets
 */
add_filter('widget_text', 'do_shortcode');




/* ----------------------------------------------------------------------------
 * FILTER - the_content_more_link - read more - ?
 */
add_filter('the_content_more_link', 'td_remove_more_link_scroll');
function td_remove_more_link_scroll($link) {
    $link = preg_replace('|#more-[0-9]+|', '', $link);
    $link = '<div class="more-link-wrap">' . $link . '</div>';
    return $link;
}




/* ----------------------------------------------------------------------------
 * Visual Composer init
 */

/**
 * visual composer rewrite classes
 * Filter to Replace default css class for vc_row shortcode and vc_column
 */
add_filter('vc_shortcodes_css_class', 'custom_css_classes_for_vc_row_and_vc_column', 10, 2);
function custom_css_classes_for_vc_row_and_vc_column($class_string, $tag)
{
    //vc_span4
    if ($tag == 'vc_row' || $tag == 'vc_row_inner') {
        $class_string = str_replace('vc_row-fluid', 'td-pb-row', $class_string);
    }
    if ($tag == 'vc_column' || $tag == 'vc_column_inner') {
        $class_string = preg_replace('/vc_col-sm-(\d{1,2})/', 'td-pb-span$1', $class_string);
        //$class_string = preg_replace('/vc_span(\d{1,2})/', 'td-pb-span$1', $class_string);
    }
    return $class_string;
}


// visual composer rewrite templates
add_filter('vc_load_default_templates', 'my_custom_template_modify_array');
function my_custom_template_modify_array($data) {
    return array(); // This will remove all default templates
}


add_action('vc_load_default_templates_action','my_custom_template_for_vc');
function my_custom_template_for_vc() {
    require_once(get_template_directory() . '/includes/td_templates_builder.php');
}


td_vc_init();
function td_vc_init() {

    // Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
    if (function_exists('vc_set_as_theme')) {
        vc_set_as_theme(true);
    }

    if (function_exists('wpb_map')) {
        //map all of our blocks in page builder
        td_global_blocks::wpb_map_all();
    }

    if (function_exists('vc_disable_frontend')) {
        vc_disable_frontend();
    }

    if (class_exists('WPBakeryVisualComposer')) { //disable visual composer updater
        $td_composer = WPBakeryVisualComposer::getInstance();
        $td_composer->disableUpdater();
    }
}




/* ----------------------------------------------------------------------------
 * TagDiv gallery - tinyMCE hooks
 */
add_action('print_media_templates', 'td_custom_gallery_settings_hook');
add_action('print_media_templates', 'td_change_backbone_js_hook');
/**
 * custom gallery setting
 */
function td_custom_gallery_settings_hook () {
    // define your backbone template;
    // the "tmpl-" prefix is required,
    // and your input field should have a data-setting attribute
    // matching the shortcode name
    ?>
    <script type="text/html" id="tmpl-td-custom-gallery-setting">
        <label class="setting">
            <span>Gallery Type</span>
            <select data-setting="td_select_gallery_slide">
                <option value="">Default </option>
                <option value="slide">TagDiv Slide Gallery</option>
            </select>
        </label>

        <label class="setting">
            <span>Gallery Title</span>
            <input type="text" value="" data-setting="td_gallery_title_input" />
        </label>
    </script>

    <script>

        jQuery(document).ready(function(){

            // add your shortcode attribute and its default value to the
            // gallery settings list; $.extend should work as well...
            _.extend(wp.media.gallery.defaults, {
                td_select_gallery_slide: '', td_gallery_title_input: ''
            });

            // merge default gallery settings template with yours
            wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
                template: function(view){
                    return wp.media.template('gallery-settings')(view)
                        + wp.media.template('td-custom-gallery-setting')(view);
                }
            });

            //console.log();
            // wp.media.model.Attachments.trigger('change')
        });

    </script>
<?php
}


/**
 * td-modal-image support in tinymce
 */
function td_change_backbone_js_hook() {
    //change the backbone js template


    // make the buffer for the dropdown
    $image_styles_buffer_for_select = '';
    $image_styles_buffer_for_switch = '';


    foreach (td_global::$tiny_mce_image_style_list as $tiny_mce_image_style_id => $tiny_mce_image_style_params) {
        $image_styles_buffer_for_select .= "'<option value=\"" . $tiny_mce_image_style_id . "\">" . $tiny_mce_image_style_params['text'] . "</option>' + ";
        $image_styles_buffer_for_switch .= "
        case '$tiny_mce_image_style_id':
            td_clear_all_classes(); //except the modal one
            td_add_image_css_class('" . $tiny_mce_image_style_params['class'] . "');
            break;
        ";
    }


    ?>
    <script type="text/javascript">

        (function (){

            var td_template_content = jQuery('#tmpl-image-details').text();

            var td_our_content = '' +
                '<div class="setting">' +
                '<span>Modal image</span>' +
                '<div class="button-large button-group" >' +
                '<button class="button active td-modal-image-off" value="left">Off</button>' +
                '<button class="button td-modal-image-on" value="left">On</button>' +
                '</div><!-- /setting -->' +
                '<div class="setting">' +
                '<span>tagDiv image style</span>' +
                '<select class="size td-wp-image-style">' +
                '<option value="">Default</option>' +
                <?php echo $image_styles_buffer_for_select ?>
                '</select>' +
                '</div>' +
                '</div>';

            //inject our settings in the template - before <div class="setting align">
            td_template_content = td_template_content.replace('<div class="setting align">', td_our_content + '<div class="setting align">');

            //save the template
            jQuery('#tmpl-image-details').html(td_template_content);

            //modal off - click event
            jQuery(".td-modal-image-on").live( "click", function() {
                if (jQuery(this).hasClass('active')) {
                    return;
                }
                td_add_image_css_class('td-modal-image');

                jQuery(".td-modal-image-off").removeClass('active');
                jQuery(".td-modal-image-on").addClass('active');
            });

            //modal on - click event
            jQuery(".td-modal-image-off").live( "click", function() {
                if (jQuery(this).hasClass('active')) {
                    return;
                }

                td_remove_image_css_class('td-modal-image');

                jQuery(".td-modal-image-off").addClass('active');
                jQuery(".td-modal-image-on").removeClass('active');
            });

            // select change event
            jQuery(".td-wp-image-style").live( "change", function() {
                switch (jQuery( ".td-wp-image-style").val()) {

                    <?php echo $image_styles_buffer_for_switch; ?>

                    default:
                        td_clear_all_classes(); //except the modal one
                        jQuery('*[data-setting="extraClasses"]').change(); //trigger the change event for backbonejs
                }
            });

            //util functions to edit the image details in wp-admin
            function td_add_image_css_class(new_class) {
                var td_extra_classes_value = jQuery('*[data-setting="extraClasses"]').val();
                jQuery('*[data-setting="extraClasses"]').val(td_extra_classes_value + ' ' + new_class);
                jQuery('*[data-setting="extraClasses"]').change(); //trigger the change event for backbonejs
            }

            function td_remove_image_css_class(new_class) {
                var td_extra_classes_value = jQuery('*[data-setting="extraClasses"]').val();

                //try first with a space before the class
                var td_regex = new RegExp(" " + new_class,"g");
                td_extra_classes_value = td_extra_classes_value.replace(td_regex, '');

                var td_regex = new RegExp(new_class,"g");
                td_extra_classes_value = td_extra_classes_value.replace(td_regex, '');

                jQuery('*[data-setting="extraClasses"]').val(td_extra_classes_value);
                jQuery('*[data-setting="extraClasses"]').change(); //trigger the change event for backbonejs
            }

            //clears all classes except the modal image one
            function td_clear_all_classes() {
                var td_extra_classes_value = jQuery('*[data-setting="extraClasses"]').val();
                if (td_extra_classes_value.indexOf('td-modal-image') > -1) {
                    //we have the modal image one - keep it, remove the others
                    jQuery('*[data-setting="extraClasses"]').val('td-modal-image');
                } else {
                    jQuery('*[data-setting="extraClasses"]').val('');
                }
            }

            //monitor the backbone template for the current status of the picture
            setInterval(function(){
                var td_extra_classes_value = jQuery('*[data-setting="extraClasses"]').val();
                if (typeof td_extra_classes_value !== 'undefined' && td_extra_classes_value != '') {
                    // if we have modal on, switch the toggle
                    if (td_extra_classes_value.indexOf('td-modal-image') > -1) {
                        jQuery(".td-modal-image-off").removeClass('active');
                        jQuery(".td-modal-image-on").addClass('active');
                    }

                    <?php

                    foreach (td_global::$tiny_mce_image_style_list as $tiny_mce_image_style_id => $tiny_mce_image_style_params) {
                        ?>
                        //change the select
                        if (td_extra_classes_value.indexOf('<?php echo $tiny_mce_image_style_params['class'] ?>') > -1) {
                            jQuery(".td-wp-image-style").val('<?php echo $tiny_mce_image_style_id ?>');
                        }
                        <?php
                    }

                    ?>

                }
            }, 1000);
        })(); //end anon function
    </script>
<?php
}




/* ----------------------------------------------------------------------------
 * TagDiv gallery - front end hooks
 */
add_filter('post_gallery', 'my_gallery_shortcode', 10, 4);
/**
 * @param string $output - is empty !!!
 * @param $atts
 * @param bool $content
 * @param bool $tag
 * @return mixed
 */
function my_gallery_shortcode($output = '', $atts, $content = false, $tag = false) {

    //print_r($atts);
    $buffy = '';

    //check for gallery  = slide
    if(!empty($atts) and !empty($atts['td_select_gallery_slide']) and $atts['td_select_gallery_slide'] == 'slide') {

        $td_double_slider2_no_js_limit = 7;
        $td_nr_columns_slide = 'td-slide-on-2-columns';
        $nr_title_chars = 95;

        //check to see if we have or not sidebar on the page, to set the small images when need to show them on center
        if(td_global::$cur_single_template_sidebar_pos == 'no_sidebar') {
            $td_double_slider2_no_js_limit = 11;
            $td_nr_columns_slide = 'td-slide-on-3-columns';
            $nr_title_chars = 170;
        }

        $title_slide = '';
        //check for the title
        if(!empty($atts['td_gallery_title_input'])) {
            $title_slide = $atts['td_gallery_title_input'];

            //check how many chars the tile have, if more then 84 then that cut it and add ... after
            if(mb_strlen ($title_slide, 'UTF-8') > $nr_title_chars) {
                $title_slide = mb_substr($title_slide, 0, $nr_title_chars, 'UTF-8') . '...';
            }
        }

        $slide_images_thumbs_css = '';
        $slide_display_html  = '';
        $slide_cursor_html = '';

        $image_ids = explode(',', $atts['ids']);

        //check to make sure we have images
        if (count($image_ids) == 1 and !is_numeric($image_ids[0])) {
            return;
        }

        $image_ids = array_map('trim', $image_ids);//trim elements of the $ids_gallery array

        //generate unique gallery slider id
        $gallery_slider_unique_id = td_global::td_generate_unique_id();

        $cur_item_nr = 1;
        foreach($image_ids as $image_id) {

            //get the info about attachment
            $image_attachment = td_util::attachment_get_full_info($image_id);

            //get images url
            $td_temp_image_url_80x60 = wp_get_attachment_image_src($image_id, 'td_80x60'); //for the slide - for small images slide popup
            $td_temp_image_url_full = $image_attachment['src'];                            //default big image - for magnific popup

            //if we are on full wight (3 columns use the default images not the resize ones)
            if(td_global::$cur_single_template_sidebar_pos == 'no_sidebar') {

                $td_temp_image_url = wp_get_attachment_image_src($image_id, 'td_1021x580');       //1021x580 images - for big slide
            } else {
                $td_temp_image_url = wp_get_attachment_image_src($image_id, 'td_0x420');       //0x420 image sizes - for big slide
            }


            //check if we have all the images
            if(!empty($td_temp_image_url[0]) and !empty($td_temp_image_url_80x60[0]) and !empty($td_temp_image_url_full)) {

                //css for display the small cursor image
                $slide_images_thumbs_css .= '
                    #' . $gallery_slider_unique_id . '  .td-doubleSlider-2 .td-item' . $cur_item_nr . ' {
                        background: url(' . $td_temp_image_url_80x60[0] . ') 0 0 no-repeat;
                    }';

                //html for display the big image
                $class_post_content = '';

                if(!empty($image_attachment['description']) or !empty($image_attachment['caption'])) {
                    $class_post_content = 'td-gallery-slide-content';
                }

                //if picture has caption & description
                $figcaption = '';

                if(!empty($image_attachment['caption']) or !empty($image_attachment['description'])) {
                    $figcaption = '<figcaption class = "td-slide-caption ' . $class_post_content . '">';

                    if(!empty($image_attachment['caption'])) {
                        $figcaption .= '<div class = "td-gallery-slide-copywrite">' . $image_attachment['caption'] . '</div>';
                    }

                    if(!empty($image_attachment['description'])) {
                        $figcaption .= '<span>' . $image_attachment['description'] . '</span>';
                    }

                    $figcaption .= '</figcaption>';
                }

                $slide_display_html .= '
                    <div class = "td-slide-item td-item' . $cur_item_nr . '">
                        <figure class="td-slide-galery-figure td-slide-popup-gallery">
                            <a class="slide-gallery-image-link" href="' . $td_temp_image_url_full . '" title="' . $image_attachment['title'] . '"  data-caption="' . esc_attr($image_attachment['caption'], ENT_QUOTES) . '"  data-description="' . htmlentities($image_attachment['description'], ENT_QUOTES) . '">
                                <img src="' . $td_temp_image_url[0] . '" alt="' . htmlentities($image_attachment['alt'], ENT_QUOTES) . '">
                            </a>
                            ' . $figcaption . '
                        </figure>
                    </div>';

                //html for display the small cursor image
                $slide_cursor_html .= '
                    <div class = "td-button td-item' . $cur_item_nr . '">
                        <div class = "td-border"></div>
                    </div>';

                $cur_item_nr++;
            }//end check for images
        }//end foreach

        //check if we have html code for the slider
        if(!empty($slide_display_html) and !empty($slide_cursor_html)) {

            //get the number of slides
            $nr_of_slides = count($image_ids);
            if($nr_of_slides < 0) {
                $nr_of_slides = 0;
            }

            $buffy = '
                <style type="text/css">
                    ' .
                $slide_images_thumbs_css . '
                </style>

                <div id="' . $gallery_slider_unique_id . '" class="' . $td_nr_columns_slide . '">
                    <div class="post_td_gallery">
                        <div class="td-gallery-slide-top">
                           <div class="td-gallery-title">' . $title_slide . '</div>

                            <div class="td-gallery-controls-wrapper">
                                <div class="td-gallery-slide-count"><span class="td-gallery-slide-item-focus">1</span> ' . __td('of') . ' ' . $nr_of_slides . '</div>
                                <div class="td-gallery-slide-prev-next-but">
                                    <i class = "td-icon-left doubleSliderPrevButton"></i>
                                    <i class = "td-icon-right doubleSliderNextButton"></i>
                                </div>
                            </div>
                        </div>

                        <div class = "td-doubleSlider-1 ">
                            <div class = "td-slider">
                                ' . $slide_display_html . '
                            </div>
                        </div>

                        <div class = "td-doubleSlider-2">
                            <div class = "td-slider">
                                ' . $slide_cursor_html . '
                            </div>
                        </div>

                    </div>

                </div>
                ';

            $slide_javascript = '
                    //total number of slides
                    var ' . $gallery_slider_unique_id . '_nr_of_slides = ' . $nr_of_slides . ';

                    jQuery(document).ready(function() {
                        //magnific popup
                        jQuery("#' . $gallery_slider_unique_id . ' .td-slide-popup-gallery").magnificPopup({
                            delegate: "a",
                            type: "image",
                            tLoading: "Loading image #%curr%...",
                            mainClass: "mfp-img-mobile",
                            gallery: {
                                enabled: true,
                                navigateByImgClick: true,
                                preload: [0,1]
                            },
                            image: {
                                tError: "<a href=\'%url%\'>The image #%curr%</a> could not be loaded.",
                                    titleSrc: function(item) {//console.log(item.el);
                                    //alert(jQuery(item.el).data("caption"));
                                    return item.el.attr("data-caption") + "<div>" + item.el.attr("data-description") + "<div>";
                                }
                            },
                            zoom: {
                                    enabled: true,
                                    duration: 300,
                                    opener: function(element) {
                                        return element.find("img");
                                    }
                            },

                            callbacks: {
                                change: function() {
                                    // Will fire when popup is closed
                                    jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-1").iosSlider("goToSlide", this.currItem.index + 1 );
                                }
                            }

                        });

                        jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-1").iosSlider({
                            scrollbar: true,
                            snapToChildren: true,
                            desktopClickDrag: true,
                            infiniteSlider: true,
                            responsiveSlides: true,
                            navPrevSelector: jQuery("#' . $gallery_slider_unique_id . ' .doubleSliderPrevButton"),
                            navNextSelector: jQuery("#' . $gallery_slider_unique_id . ' .doubleSliderNextButton"),
                            scrollbarHeight: "2",
                            scrollbarBorderRadius: "0",
                            scrollbarOpacity: "0.5",
                            onSliderResize: td_gallery_resize_update_vars_' . $gallery_slider_unique_id . ',
                            onSliderLoaded: doubleSlider2Load_' . $gallery_slider_unique_id . ',
                            onSlideChange: doubleSlider2Load_' . $gallery_slider_unique_id . ',
                            keyboardControls:true
                        });

                        //small image slide
                        jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2 .td-button").each(function(i) {
                            jQuery(this).bind("click", function() {
                                jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-1").iosSlider("goToSlide", i+1);
                            });
                        });

                        //check the number of slides
                        if(' . $gallery_slider_unique_id . '_nr_of_slides > ' . $td_double_slider2_no_js_limit . ') {
                            jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2").iosSlider({
                                desktopClickDrag: true,
                                snapToChildren: true,
                                snapSlideCenter: true,
                                infiniteSlider: true
                            });
                        } else {
                            jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2").addClass("td_center_slide2");
                        }

                        function doubleSlider2Load_' . $gallery_slider_unique_id . '(args) {
                            //var currentSlide = args.currentSlideNumber;
                            jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2").iosSlider("goToSlide", args.currentSlideNumber);


                            //put a transparent border around all small sliders
                            jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2 .td-button .td-border").css("border", "3px solid #ffffff").css("opacity", "0.5");
                            jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2 .td-button").css("border", "0");

                            //put a white border around the focused small slide
                            jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2 .td-button:eq(" + (args.currentSlideNumber-1) + ") .td-border").css("border", "3px solid #ffffff").css("opacity", "1");
                            //jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-2 .td-button:eq(" + (args.currentSlideNumber-1) + ")").css("border", "3px solid #ffffff");

                            //write the current slide number
                            td_gallery_write_current_slide_' . $gallery_slider_unique_id . '(args.currentSlideNumber);
                        }

                        //writes the current slider beside to prev and next buttons
                        function td_gallery_write_current_slide_' . $gallery_slider_unique_id . '(slide_nr) {
                            jQuery("#' . $gallery_slider_unique_id . ' .td-gallery-slide-item-focus").html(slide_nr);
                        }


                        /*
                        * Resize the iosSlider when the page is resided (fixes bug on Android devices)
                        */
                        function td_gallery_resize_update_vars_' . $gallery_slider_unique_id . '(args) {
                            if(td_detect.is_android) {
                                setTimeout(function(){
                                    jQuery("#' . $gallery_slider_unique_id . ' .td-doubleSlider-1").iosSlider("update");
                                }, 1500);
                            }
                        }
                    });';

            td_js_buffer::add_to_footer($slide_javascript);
        }//end check if we have html code for the slider
    }//end if slide

    //!!!!!! WARNING
    //$return has to be != empty to overwride the default output
    return $buffy;
}




/* ----------------------------------------------------------------------------
 * filter the gallery shortcode
 */
add_filter('shortcode_atts_gallery', 'my_gallery_atts_modifier', 1); //run with 1 priority, allow anyone to overwrite our hook.
/**
 * @todo trebuie fixuite toate tipurile de imagini din gallerie in functie de setarile template-ului
 */
function my_gallery_atts_modifier($out) {

    // td_global::$cur_single_template_sidebar_pos; //is set in single.php @todo set it also on the page template
    // link to files instead of no link or attachement. The file is used by magnific pupup
    $out['link'] = 'file';

    if (!isset($out['columns'])) {
        $out['columns'] = '';
    }

    if (td_global::$cur_single_template_sidebar_pos == 'no_sidebar') {
        if ($out['columns'] == 1) {
            $out['size'] = 'td_1021x580';
        }
    } else {
        if ($out['columns'] == 1) {
            $out['size'] = 'td_640x350';
        }
    }
    return $out;
}




/* ----------------------------------------------------------------------------
 * add custom classes to the single templates, also mix fixes for white menu and white grid
 */
add_filter('body_class', 'td_add_single_template_class');
function td_add_single_template_class($classes) {

    if (is_single()) {
        global $post;

        $active_single_template = '';
        $td_post_theme_settings = get_post_meta($post->ID, 'td_post_theme_settings', true);

        if (!empty($td_post_theme_settings['td_post_template'])) {
            // we have a post template set in the post
            $active_single_template = $td_post_theme_settings['td_post_template'];
        } else {
            // we may have a global post template form td panel
            $td_default_site_post_template = td_util::get_option('td_default_site_post_template');
            if(!empty($td_default_site_post_template)) {
                $active_single_template = $td_default_site_post_template;
            }
        }


        // add the class if we have a post template
        if (!empty($active_single_template)) {
            td_global::$cur_single_template = $active_single_template;
            $classes []= sanitize_html_class($active_single_template);
        }

    }

    // if main menu background color is white to fix the menu appearance on all headers
    if (td_util::get_option('tds_menu_color') == '#ffffff' or td_util::get_option('tds_menu_color') == 'ffffff') {
        $classes[] = 'white-menu';
    }

    // if grid color is white to fix the menu appearance on all headers
    if (td_util::get_option('tds_grid_line_color') == '#ffffff' or td_util::get_option('tds_grid_line_color') == 'ffffff') {
        $classes[] = 'white-grid';
    }
    return $classes;
}




/* ----------------------------------------------------------------------------
 * add custom classes to the single templates, also mix fixes for white menu and white grid
 */
add_filter('body_class', 'td_add_category_template_class');
function td_add_category_template_class($classes) {
    if(!is_admin() and is_category()) {
        $classes [] = sanitize_html_class(td_api_category_template::_helper_get_active_id());
        $classes [] = sanitize_html_class(td_api_category_top_posts_style::_helper_get_active_id());
    }
    return $classes;
}




/* ----------------------------------------------------------------------------
 * add `filter_by` URL variable so I can retrieve it with  `get_query_var` function
 */
add_filter('query_vars', 'td_category_big_grid_add_query_vars_filter');
function td_category_big_grid_add_query_vars_filter($vars) {
    $vars[] = "filter_by";
    return $vars;
}




/* ----------------------------------------------------------------------------
 * modify the main query for category pages
 */
add_action('pre_get_posts', 'td_modify_main_query_for_category_page');
function td_modify_main_query_for_category_page($query) {

    //checking for category page and main query
    if(!is_admin() and is_category() and $query->is_main_query()) {

        //get the number of page where on
        $paged = get_query_var('paged');

        //get the `filter_by` URL($_GET) variable
        $filter_by = get_query_var('filter_by');

        //get the limit of posts on the category page
        $limit = get_option('posts_per_page');

        // get the category object - with or without permalinks
        if (empty($query->query_vars['cat'])) {
            td_global::$current_category_obj = get_category_by_path(get_query_var('category_name'), false);  // when we have permalinks, we have to get the category object like this.
        } else {
            td_global::$current_category_obj = get_category($query->query_vars['cat']);
        }

        //offset is hardcoded because of big grid
        $offset = td_api_category_top_posts_style::_helper_get_posts_shown_in_the_loop();


        //echo $filter_by;
        switch ($filter_by) {
            case 'featured':
                //get the category object
                $query->set('category_name',  td_global::$current_category_obj->slug);
                $query->set('cat', get_cat_ID(TD_FEATURED_CAT)); //add the fetured cat
                break;

            case 'popular':
                $query->set('meta_key', td_page_views::$post_view_counter_key);
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;

            case 'popular7':
                $query->set('meta_key', td_page_views::$post_view_counter_7_day_total);
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;

            case 'review_high':
                $query->set('meta_key', td_review::$td_review_key);
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;

            case 'random_posts':
                $query->set('orderby', 'rand');
                break;
        }//end switch

	    // offset + custom pagination - if we have offset, wordpress overwrites the pagination and works with offset + limit
	    if(empty($query->is_feed)) {
		    if ( ! empty( $offset ) and $paged > 1 ) {
			    $query->set( 'offset', $offset + ( ( $paged - 1 ) * $limit ) );
		    } else {
			    $query->set( 'offset', $offset );
		    }
	    }
        //print_r($query);
    }//end if main query
}




/** ----------------------------------------------------------------------------
 *  update category shared terms
 *  @since WordPress 4.2
 *  @link https://make.wordpress.org/core/2015/02/16/taxonomy-term-splitting-in-4-2-a-developer-guide/
 */
add_action('split_shared_term', 'td_category_split_shared_term', 10, 4);
function td_category_split_shared_term($term_id, $new_term_id, $term_taxonomy_id, $taxonomy) {
	if (($taxonomy === 'category') and (isset(td_global::$td_options['category_options'][$term_id]))) {

		$current_settings = td_global::$td_options['category_options'][$term_id];
		td_global::$td_options['category_options'][$new_term_id] = $current_settings;
		unset(td_global::$td_options['category_options'][$term_id]);

		update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);
	}
}




/* ----------------------------------------------------------------------------
 *   TagDiv WordPress booster init
 */

td_init_booster();
function td_init_booster() {
    global $content_width;

    // content width - this is overwritten in post
    if (!isset($content_width)) {
        $content_width = 640;
    }

    /* ----------------------------------------------------------------------------
     * add_image_size for WordPress - register all the thumbs from the thumblist
     */
    foreach (td_api_thumb::get_all() as $thumb_array) {
        if (td_util::get_option('tds_thumb_' . $thumb_array['name']) != '') {
            add_image_size($thumb_array['name'], $thumb_array['width'], $thumb_array['height'], $thumb_array['crop']);
        }
    }


    /* ----------------------------------------------------------------------------
     * Add lazy shortcodes of the registered blocks
     */
    foreach (td_api_block::get_all() as $block_settings_key => $block_settings_value) {
        td_global_blocks::add_lazy_shortcode($block_settings_key);
    }


    /* ----------------------------------------------------------------------------
    * register the default sidebars + dynamic ones
    */
    register_sidebar(array(
        'name'=> TD_THEME_NAME . ' default',
        'id' => 'td-default', //the id is used by the importer
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="block-title"><span>',
        'after_title' => '</span></div>'
    ));

    register_sidebar(array(
        'name'=>'Footer 1',
        'id' => 'td-footer-1',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="block-title"><span>',
        'after_title' => '</span></div>'
    ));

    register_sidebar(array(
        'name'=>'Footer 2',
        'id' => 'td-footer-2',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="block-title"><span>',
        'after_title' => '</span></div>'
    ));

    register_sidebar(array(
        'name'=>'Footer 3',
        'id' => 'td-footer-3',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="block-title"><span>',
        'after_title' => '</span></div>'
    ));

    //get our custom dynamic sidebars
    $currentSidebars = td_util::get_option('sidebars');

    //if we have user made sidebars, register them in wp
    if (!empty($currentSidebars)) {
        foreach ($currentSidebars as $sidebar) {
            register_sidebar(array(
                'name' => $sidebar,
                'id' => 'td-' . td_util::sidebar_name_to_id($sidebar),
                'before_widget' => '<aside class="widget %2$s">',
                'after_widget' => '</aside>',
                'before_title' => '<div class="block-title"><span>',
                'after_title' => '</span></div>',
            ));
        } //end foreach
    }

	$smooth_scroll = td_util::get_option('tds_smooth_scroll');

	if (!empty($smooth_scroll)) {

		add_action('wp_enqueue_scripts',  'load_js_smooth_scroll', 1000);
		function load_js_smooth_scroll() {
			wp_enqueue_script('td_smooth_scroll', get_template_directory_uri() . '/includes/js_files/td_smooth_scroll.js', array('jquery'), TD_THEME_VERSION, true);
		}
	}
}




/*  ----------------------------------------------------------------------------
    check to see if we are on the backend
 */
if (is_admin()) {
    // the two files are required by wp_admin -> to make the page / posts metaboxes
    require_once('wp-admin/panel/td_panel_generator.php');
    require_once('wp-admin/panel/td_panel_data_source.php');

    if (current_user_can('switch_themes')) {
        // the panel
        require_once('wp-admin/panel/td_panel.php');
    }

    /**
     * the wp-admin TinyMCE editor buttons
     */
    require_once('wp-admin/tinymce/tinymce.php');

    /**
     * Custom content metaboxes (the select sidebar dropdown/post etc)
     */
    require_once('wp-admin/content-metaboxes/td_templates_settings.php');

    /**
     * Helper pointers
     */
    require_once('td_help_pointers.php');

    add_action('admin_enqueue_scripts', 'td_help_pointers');
    function td_help_pointers()
    {
        //First we define our pointers
        $pointers = array(
            array(
                'id' => 'vc_columns_pointer',   // unique id for this pointer
                'screen' => 'page', // this is the page hook we want our pointer to show on
                'target' => '.composer-switch', // the css selector for the pointer to be tied to, best to use ID's
                'title' => TD_THEME_NAME . ' (tagDiv) tip',
                'content' => '<img src="' . td_global::$get_template_directory_uri . '/includes/wp_booster/wp-admin/images/td_helper_pointers/vc-columns.png' . '">',
                'position' => array(
                    'edge' => 'top', //top, bottom, left, right
                    'align' => 'left' //top, bottom, left, right, middle
                )
            )
            // more as needed
        );
        //Now we instantiate the class and pass our pointer array to the constructor
        $myPointers = new td_help_pointers($pointers);
    }

    /*  -----------------------------------------------------------------------------
        TGM_Plugin_Activation
     */
    require_once 'external/class-tgm-plugin-activation.php';

    add_action('tgmpa_register', 'td_required_plugins');
    function td_required_plugins()
    {
        $plugins = array(
            array(
                'name' => 'Visual Composer ( required )', // The plugin name
                'slug' => 'js_composer', // The plugin slug (typically the folder name)
                'source' => td_global::$get_template_directory_uri . '/includes/plugins/js_composer.zip', // The plugin source
                'required' => true, // If false, the plugin is only 'recommended' instead of required
                'version' => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url' => '', // If set, overrides default API URL and points to an external URL
            ),
            array(
                'name' => 'tagDiv social counter ( This plugin is optional )', // The plugin name
                'slug' => 'td-social-counter', // The plugin slug (typically the folder name)
                'source' => td_global::$get_template_directory_uri . '/includes/plugins/td-social-counter.zip', // The plugin source
                'required' => false, // If false, the plugin is only 'recommended' instead of required
                'version' => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url' => '', // If set, overrides default API URL and points to an external URL
            )
        );

        $config = array(
            'domain' => TD_THEME_NAME,            // Text domain - likely want to be the same as your theme.
            'default_path' => '',                            // Default absolute path to pre-packaged plugins
            'parent_menu_slug' => 'themes.php',                // Default parent menu slug
            'parent_url_slug' => 'themes.php',                // Default parent URL slug
            'menu' => 'td_plugins',    // Menu slug
            'has_notices' => true,                        // Show admin notices or not
            'is_automatic' => true,                        // Automatically activate plugins after installation or not
            'message' => '',                            // Message to output right before the plugins table
            'strings' => array(
                'page_title' => __('Install Required Plugins', TD_THEME_NAME),
                'menu_title' => __('Install Plugins', TD_THEME_NAME),
                'installing' => __('Installing Plugin: %s', TD_THEME_NAME), // %1$s = plugin name
                'oops' => __('Something went wrong with the plugin API.', TD_THEME_NAME),
                'notice_can_install_required' => _n_noop('<span class="td-tgma-tip">' . TD_THEME_NAME . ' theme:</span> Hi, sorry to bother you but please install %1$s plugin. It\'s included with the theme and no aditional purchase is requiered!', 'This theme requires the following plugins: %1$s.'), // %1$s = plugin name(s)
                'notice_can_install_recommended' => _n_noop('If you need social icons, you can install %1$s.', 'This theme recommends the following plugins: %1$s.'), // %1$s = plugin name(s)
                'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.'), // %1$s = plugin name(s)
                'notice_can_activate_required' => _n_noop('<span class="td-tgma-tip">' . TD_THEME_NAME . ' theme:</span> Hi, please activate %1$s. Our theme works best with it.', 'The following required plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
                'notice_can_activate_recommended' => _n_noop('<span class="td-tgma-tip">' . TD_THEME_NAME . ' theme:</span> The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
                'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'), // %1$s = plugin name(s)
                'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'), // %1$s = plugin name(s)
                'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'), // %1$s = plugin name(s)
                'install_link' => _n_noop('Go to plugin instalation', ' Begin installing plugins'),
                'activate_link' => _n_noop('Go to activation panel', 'Activate installed plugins'),
                'return' => __('Return to Required Plugins Installer', TD_THEME_NAME),
                'plugin_activated' => __('Plugin activated successfully.', TD_THEME_NAME),
                'complete' => __('All plugins installed and activated successfully. %s', TD_THEME_NAME), // %1$s = dashboard link
                'nag_type' => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
            )
        );
        tgmpa($plugins, $config);
    }
}