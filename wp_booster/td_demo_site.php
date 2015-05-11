<?php
class td_demo_site {

    var $last_menu_id;
    var $last_menu_position = 1;
    var $last_main_menu_id;

	//static $category_ids = array('cat_name', 'cat_slug', 'cat_id');

    var $last_page_id; //the last page or post id
    var $last_page_slug_id = 1;

    var $last_sidebar_widget_position = 0; //the position of the widget on the sidebar
    var $last_widget_instance = 70; //the id used by the widget instances in wp db


    var $last_attachment_id = 1; //the id of the file to load


    //used for progress calculation
    var $total_progress_steps = 113;
    var $current_progress_step = 1;


    var $show_log = true;

    /**
     * creates the menu
     * @param $menu_name
     */
    function create_menu($menu_name) {
        $current_menu = wp_get_nav_menu_object($menu_name);
        if(!$current_menu){
            $this->last_menu_id = wp_create_nav_menu($menu_name);
            $this->log("create_menu($menu_name)", 'ID:' . $this->last_menu_id);
        } else {
            wp_delete_nav_menu($menu_name);
            $this->last_menu_id = wp_create_nav_menu($menu_name);
            $this->log("create_menu($menu_name)", 'Menu recreated ID:' . $this->last_menu_id);

            /*
            if (!empty($current_menu->term_id)) {
                $this->last_menu_id = $current_menu->term_id;
                $this->log("create_menu($menu_name)", 'menu already exists ID: ' . $this->last_menu_id);
            }
            */
        }
    }



    /**
     * activates the last created menu for a specific spot
     * @param $spot
     */
    function activate_menu($spot) {
        $menu_spots_array = get_theme_mod('nav_menu_locations');
        if (isset($menu_spots_array[$spot]) and $menu_spots_array[$spot] == $this->last_menu_id) {
            //the menu is already there
            $this->log("activate_menu($spot)", 'menu already active for this spot ID: ' . $this->last_menu_id);
        } else {
            //activate the menu
            $menu_spots_array[$spot] = $this->last_menu_id;
            set_theme_mod('nav_menu_locations', $menu_spots_array);
            $this->log("activate_menu($spot)", 'ID: ' . $this->last_menu_id);
        }
    }


    /**
     * creates a top menu in wp
     * @param $title
     */
    function add_top_menu($title) {
        $this->separator();

        $itemData =  array(
            'menu-item-position'  => $this->last_menu_position,
            'menu-item-object' => '',
            'menu-item-type'      => 'custom',
            'menu-item-title'    => $title,
            'menu-item-url' => '#',
            'menu-item-status'    => 'publish'
        );

        $this->last_main_menu_id = wp_update_nav_menu_item($this->last_menu_id, 0, $itemData);
        $this->log("add_main_menu($title)", 'ID: ' . $this->last_main_menu_id);

        $this->last_menu_position++;
    }



	function add_top_mega_menu($title = '', $category_mega = '') {

		$itemData =  array(
			'menu-item-position'  => $this->last_menu_position,
			'menu-item-object' => '',
			'menu-item-type'      => 'custom',
			'menu-item-title'    => $title,
			'menu-item-url' => '#',
			'menu-item-status'    => 'publish'
		);


		$category_mega_menu = $this->category_array[$category_mega];

		$item_id = wp_update_nav_menu_item($this->last_menu_id, 0, $itemData);
		update_post_meta($item_id, 'td_mega_menu_cat', $category_mega_menu);

		$this->log("add_top_mega_menu()", 'page ID ' . $this->last_page_id . ' | added to top menu');

		$this->last_menu_position++;
	}

    function add_top_page($title = '') {
        $itemData =  array(
            'menu-item-object-id' => $this->last_page_id,
            'menu-item-parent-id' => 0,
            'menu-item-position'  => $this->last_menu_position,
            'menu-item-object' => 'page',
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish'
        );

        if (!empty($title)) {
            $itemData['menu-item-title'] = $title;
        }

        wp_update_nav_menu_item($this->last_menu_id, 0, $itemData);
        $this->log("add_top_page()", 'page ID ' . $this->last_page_id . ' | added to top menu');

        $this->last_menu_position++;
    }

    /**
     * adds a page to the main menu
     */
    function add_sub_page() {
        $itemData =  array(
            'menu-item-object-id' => $this->last_page_id,
            'menu-item-parent-id' => $this->last_main_menu_id,
            'menu-item-position'  => $this->last_menu_position,
            'menu-item-object' => 'page',
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish'
        );

        wp_update_nav_menu_item($this->last_menu_id, 0, $itemData);
        $this->log("add_main_menu_page()", 'page ID ' . $this->last_page_id . ' | added to menu ID: ' . $this->last_main_menu_id);

        $this->last_menu_position++;
    }


    /**
     * adds a custom page to the main menu
     */
    function add_sub_page_custom($param_sub_page_custom) {

        $itemData =  array(
            'menu-item-title' => $param_sub_page_custom['menu-item-title'],
            'menu-item-object-id' => $param_sub_page_custom['id'],
            'menu-item-db-id' => 0,
            'menu-item-url' => get_category_link($param_sub_page_custom['id']),
            'menu-item-type' => $param_sub_page_custom['menu-item-type'], //taxonomy
            'menu-item-status' => 'publish',
            'menu-item-object' => 'category',
            'menu-item-parent-id' => $this->last_main_menu_id
        );

        wp_update_nav_menu_item($this->last_menu_id, 0, $itemData);
        $this->log("add_main_menu_page()", 'page ID ' . $this->last_page_id . ' | added to menu ID: ' . $this->last_main_menu_id);

        $this->last_menu_position++;
    }



    /**
     * Creates a new page (blog or page post type)
     */
    function create_page($post_type = 'page', $title = '', $content = '', $page_template = '', $featured = '') {
        $post_main_category = 0;
        $post_array_categories = array();

        $this->separator();

        $slug = 'td_d_slug_' . $this->last_page_slug_id;

        if (empty($title)) {
            $title = $this->generate_title();
        }


        $args = array(
            'name' => $slug,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => 1
        );
        $my_posts = get_posts($args);


        //get a random number; used for adding category id's to posts
        $number_category = count($this->category_array);
        $rand_category = rand (0, ($number_category-1));

        $post_main_category = $this->category_array[$rand_category];

        $post_array_categories = array($post_main_category);

        //add post to featured category
        if(!empty($featured)) {
            $post_array_categories[] = get_cat_ID(TD_FEATURED_CAT);
        }

        $new_post = array(
            'post_title' => $title,
            'post_status' => 'publish',
            'post_type' => $post_type,
            'post_name' => $slug,
            'post_content' => $content,
            'comment_status' => 'open',
            'post_category' => $post_array_categories, //adding category to this post
            'guid' => td_global::td_generate_unique_id()
        );



        if (empty($my_posts[0])) {
            //new post / page
            $this->last_page_id = wp_insert_post ($new_post);

            $this->log("create_page($post_type, $slug, $title, $page_template)", 'ID: ' .  $this->last_page_id );
        } else {
            //update the existing one
            $new_post['ID'] = $my_posts[0]->ID;
            wp_insert_post ($new_post);

            $this->last_page_id = $my_posts[0]->ID;
            $this->log("create_page($post_type, $slug, $title, $page_template)", 'already exists, updated ID: ' .  $this->last_page_id);
        }

        //set the page template if we have one
        if (!empty($page_template)) {
            update_post_meta($this->last_page_id, '_wp_page_template', $page_template);
        }

        $this->last_page_slug_id++;
    }


    /**
     * used to set per page or per post wp_cake settings ! to the last page
     * @param $meta_key - the key used in wp-alchemy
     * @param $meta_sub_key - the setting key
     * @param $new_value - the new value
     */
    function update_post_meta($meta_key, $meta_sub_key, $new_value) {
        $td_post_meta = get_post_meta($this->last_page_id, $meta_key, true);
        $td_post_meta[$meta_sub_key] = $new_value;
        update_post_meta($this->last_page_id, $meta_key, $td_post_meta);
    }



    //ads a featured image to the last page
    function add_featured_image() {
        global $wpdb;
        $featured_image_url = get_template_directory_uri() . '/images/demo/' . $this->last_attachment_id . '.jpg';

        //check to see if the attachement is already uploaded
        //$td_attachment_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . 'td_demo_atachement_' . $this->last_attachment_id . "'" );
        $td_attachment_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = '%s'", 'td_demo_atachement_' . $this->last_attachment_id ));


        if (empty($td_attachment_id)) {
            //the attachement is not already present, upload it and add it as featured image
            add_action('add_attachment', array($this, 'new_attachment'));
            $lastAttachement = media_sideload_image($featured_image_url, $this->last_page_id, 'td_demo_atachement_' . $this->last_attachment_id);
            remove_action('add_attachment', array($this, 'new_attachment'));

            $this->log("add_featured_image", 'new attachment added to ID: ' .  $this->last_page_id);
        } else {
            // the attachement is already present, just update the last post to use it as a featured image
            set_post_thumbnail($this->last_page_id, $td_attachment_id);
            $this->log("add_featured_image", 'existing attachment ' . $td_attachment_id . ' added to ID: ' .  $this->last_page_id);
        }

        //reset the total atachements
        if ($this->last_attachment_id >= 7) {
            $this->last_attachment_id = 1;
        } else {
            $this->last_attachment_id++;
        }

    }

    //do not delete this, is used by add_featured_image as a callback
    function new_attachment($att_id){
        // the post this was sideloaded into is the attachments parent!
        $p = get_post($att_id);
        update_post_meta($p->post_parent,'_thumbnail_id',$att_id);
    }


    //sets the last page as homepage
    function set_homepage() {
        update_option( 'page_on_front', $this->last_page_id);
        update_option( 'show_on_front', 'page' );
        $this->log("set_homepage", 'to ID: ' .  $this->last_page_id);
    }



    function remove_widgets_from_sidebar($sidebar_name) {
        $sidebar_id = 'td-' . td_util::sidebar_name_to_id($sidebar_name);
        $sidebars_widgets = get_option( 'sidebars_widgets' );

        if (isset($sidebars_widgets[$sidebar_id])) {
            //empty the default sidebar
            unset($sidebars_widgets[$sidebar_id]);
            update_option('sidebars_widgets', $sidebars_widgets);
            $this->log("remove_widgets_from_sidebar", "removed widgets from sidebar - $sidebar_id");
        } else {
            $this->log("remove_widgets_from_sidebar", "no widgets in - $sidebar_id");
        }
    }

    //adds a widget to the default sidebar
    function add_widget_to_sidebar($sidebar_name, $widget_name, $atts) {
        $widget_instances = get_option('widget_' . $widget_name);
        //in the demo mode, all the widgets will have an istance id of 70+
        $widget_instances[$this->last_widget_instance] = $atts;

        //add the widget instance to the database
        update_option('widget_' . $widget_name, $widget_instances);

        $sidebars_widgets = get_option( 'sidebars_widgets' );


        $sidebars_widgets['td-' . td_util::sidebar_name_to_id($sidebar_name)][$this->last_sidebar_widget_position] = $widget_name . '-' . $this->last_widget_instance;
        update_option('sidebars_widgets', $sidebars_widgets);

        $this->log("add_widget_to_sidebar", "Added widget $widget_name to sidebar $sidebar_name");

        $this->last_sidebar_widget_position++;
        $this->last_widget_instance++;

    }

    //adds a custom user sidebar if it doesn't already exists
    function add_user_sidebar($new_sidebar) {
        //get our custom dinamic sidebars
        $currentSidebars = td_util::get_option('sidebars');
        //print_r($currentSidebars);
        foreach ($currentSidebars as $sidebar) {
            if ($new_sidebar == $sidebar) {
                $this->log("add_user_sidebar", "sidebar $new_sidebar already exists");
                return;
            }
        } //end foreach

        $currentSidebars[]= $new_sidebar;
        td_util::update_option('sidebars', $currentSidebars);
        $this->log("add_user_sidebar", "new sidebar $new_sidebar saved");
    }


    function update_logo($path_normal, $path_retina, $path_logo_mobile) {
        td_util::update_option('tds_logo_upload', $path_normal);
        td_util::update_option('tds_logo_upload_r', $path_retina);
        td_util::update_option('tds_logo_menu_upload', $path_logo_mobile);

        $this->log("add_logo($path_normal, $path_retina)", "new logo added");
    }


	function add_socials () {
		td_util::update_option('td_social_networks_show', 'show');

		$td_socials['facebook'] = '#';
		$td_socials['twitter'] = '#';
		$td_socials['vimeo'] = '#';
		$td_socials['vk'] = '#';
		$td_socials['youtube'] = '#';

		td_util::update_option('td_social_networks', $td_socials);
	}




    //ads a new ad spot
    function add_ad_spot($name, $ad_code = '') {
        $td_ad_spots = td_util::get_option('td_ads'); //read the sidebars

	    $new_ad_spot['ad_code']= $ad_code;
	    $new_ad_spot['current_ad_type']= 'other';

        $td_ad_spots[strtolower($name)] = $new_ad_spot;

        td_util::update_option('td_ads', $td_ad_spots);

        $this->log("add_ad_spot($name ...)", "new ad spot added");
    }


    //footer setup
	function set_footer($footer_logo, $footer_retina_logo, $footer_text, $footer_email) {
		td_util::update_option('tds_footer_logo_upload', $footer_logo);
		td_util::update_option('tds_footer_retina_logo_upload', $footer_retina_logo);
		td_util::update_option('tds_footer_text', $footer_text);
		td_util::update_option('tds_footer_email', $footer_email);
		$this->log("setup_footer", "footer updated");
	}



    /*  ----------------------------------------------------------------------------
        util functions
     */

    //returns an image url from the /images/demo folder
    function get_demo_image($file) {
        return  get_template_directory_uri() . '/images/demo/' . $file;
    }


    //create a new category
    function add_category($param_new_category) {
	    $new_name = trim($param_new_category[0]);



        if(!empty($param_new_category[1])) {
	        $new_categ_slug = str_replace(' ','-',strtolower($param_new_category[1]));
	        $new_categ_slug_id = get_category_by_slug($new_categ_slug);
	        $new_cat_id = $new_categ_slug_id->term_id;

            wp_create_category($new_name, $new_cat_id);
        } else {
	        wp_create_category($new_name, 0);
        }

		//create slag from category name
	    $category_slug = str_replace(' ','-', strtolower($new_name));

		//get category id //alse can be used get_cat_ID( $cat_name )
	    $this->category_array[] = get_category_by_slug($category_slug)->cat_ID;

        $this->log("create_category($param_new_category[0])", '');
    }



    private function separator() {
        if ($this->show_log) {
            echo '<div class="td-demo-msg">----------------------------------------------------------------------------------------------------</div>';
        }
    }

    /**
     * Logging function
     * @param $function
     * @param $msg
     * @param bool $important
     */
    private function log($function, $msg = '', $important = false) {
        if ($this->show_log) {
            echo '<div class="td-demo-msg"><strong>' . $this->current_progress_step . ' - ' . $function . '</strong> - ' . $msg . '</div>';
        }


        $current_progress_percent = round(($this->current_progress_step * 100) / $this->total_progress_steps, 0);
        echo "<script>td_progressbar_step('" . $current_progress_percent . "');</script>";

        $this->current_progress_step++;



//        ob_flush();
//        flush();
    }




    private function generate_title() {
        $demo_titles = array(
            '10 Most Beautifull Infinity Pools With Blue Water You Have To See',
            'The oriental experience: Chew – Pan Asia Caffe in the world 2014',
            'The NEW iPhone6 was arrived just take a look, is awesome',
            'The Big College Debate: What Makes A Good Investment',
            'Kendall Jenner Strips For LOVE Newsmag WordPress Theme',
            'Travelling with kids on Queensland’s Capricorn Coast',
	        'They Are Wearing the Best From Paris Week',
	        'The 20 free things in Sydney with your girlfriend'
        );
        return $demo_titles[array_rand($demo_titles, 1)];
    }
}

