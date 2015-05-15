<?php

/**
 * Class td_stacks_importer
 *
 * menu
 * widgets
 * homepage
 *
 */
class td_stacks_importer {

}

class td_demo_category {

    static function add_category($category_name, $parent_id=0) {
        $td_stacks_demo_categories_id = td_util::get_option('td_stacks_demo_categories_id');
        $new_cat_id = wp_create_category($category_name, $parent_id);

        // keep a list of installed category ids so we can delete them later if needed
        $td_stacks_demo_categories_id []= $new_cat_id;
        td_util::update_option('td_stacks_demo_categories_id', $td_stacks_demo_categories_id);

        return $new_cat_id;
    }

    static function remove() {
        $td_stacks_demo_categories_id = td_util::get_option('td_stacks_demo_categories_id');
        foreach ($td_stacks_demo_categories_id as $td_stacks_demo_category_id) {
            wp_delete_category($td_stacks_demo_category_id);
        }
    }
}

class td_demo_content {
    static function add_post($params) {
        $new_post = array(
            'post_title' => $params['title'],
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_content' => file_get_contents($params['file']),
            'comment_status' => 'open',
            'post_category' => '', //adding category to this post
            'guid' => td_global::td_generate_unique_id()
        );

        //new post / page
        $post_id = wp_insert_post($new_post);

        // add our demo custom meta field, using this field we will delete all the pages
        update_post_meta($post_id, 'td_demo_content', true);

        return $post_id;
    }


    static function add_page($params) {
        $new_post = array(
            'post_title' => $params['title'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => file_get_contents($params['file']),
            'comment_status' => 'open',
            'guid' => td_global::td_generate_unique_id()
        );

        //new post / page
        $page_id = wp_insert_post ($new_post);

        // add our demo custom meta field, using this field we will delete all the pages
        update_post_meta($page_id, 'td_demo_content', true);

        //set the page template if we have one
        if (!empty($params['page_template'])) {
            update_post_meta($page_id, '_wp_page_template', $params['page_template']);
        }
        return $page_id;
    }


    static function remove() {
        $args = array(
            'post_type' => array('page', 'post'),
            'meta_key'  => 'td_demo_content',
        );
        $query = new WP_Query( $args );
        if (!empty($query->posts)) {
            foreach ($query->posts as $post) {
                wp_delete_post($post->ID, true);
            }
       }
    }
}


class td_demo_widgets {

    private static $last_widget_instance = 70;
    private static $last_sidebar_widget_position = 0;


    /**
     * @param $sidebar_name string - must begin with td_demo_
     */
    static function add_sidebar($sidebar_name) {
        if (substr($sidebar_name, 0, 8) != 'td_demo_') {
            td_util::error(__FILE__, 'All sidebars used in the demo must begin with td_demo_');
            return;
        }
        $tmp_sidebars = td_util::get_option('sidebars');
        $tmp_sidebars[]= $sidebar_name;
        td_util::update_option('sidebars', $tmp_sidebars);
    }



    //adds a widget to the default sidebar
    static function add_widget_to_sidebar($sidebar_name, $widget_name, $atts) {
        $widget_instances = get_option('widget_' . $widget_name);
        //in the demo mode, all the widgets will have an istance id of 70+
        $widget_instances[self::$last_widget_instance] = $atts;

        //add the widget instance to the database
        update_option('widget_' . $widget_name, $widget_instances);

        $sidebars_widgets = get_option( 'sidebars_widgets' );


        $sidebars_widgets['td-' . td_util::sidebar_name_to_id($sidebar_name)][self::$last_sidebar_widget_position] = $widget_name . '-' . self::$last_widget_instance;
        update_option('sidebars_widgets', $sidebars_widgets);


        self::$last_sidebar_widget_position++;
        self::$last_widget_instance++;

    }


    /**
     * remove the sidebars that begin with td_demo_
     */
    static function remove() {
        $tmp_sidebars = td_util::get_option('sidebars');
        foreach ($tmp_sidebars as $index => $sidebar) {
            if (substr($sidebar, 0, 8) == 'td_demo_') {
                unset($tmp_sidebars[$index]);
            }
        }
        td_util::update_option('sidebars', $tmp_sidebars);
    }
}


class td_demo_menus {


    private static $allowed_menu_names = array(
        'td_stack_top',
        'td_stack_main',
        'td_stack_footer',
    );



    /**
     * creates a menu and adds it to a location of the theme
     * @param $menu_name
     * @param $location
     * @return bool
     */
    static function create_menu($menu_name, $location) {
        if (!in_array($menu_name, self::$allowed_menu_names)) {
            td_util::error(__FILE__, 'td_stacks_menu::create_menu - menu_name is not in allowed_menu_names');
            return false;
        }

        $menu_id = wp_create_nav_menu($menu_name);
        if (is_wp_error($menu_id)) {
            return false;
        }

        $menu_spots_array = get_theme_mod('nav_menu_locations');
        // activate the menu only if it's not already active
        if (!isset($menu_spots_array[$location]) or $menu_spots_array[$location] != $menu_id) {
            $menu_spots_array[$location] = $menu_id;
            set_theme_mod('nav_menu_locations', $menu_spots_array);
        }
        return $menu_id;
    }



    /**
     * @param $menu_id
     * @param $title
     * @param $url
     * @param string $parent_id
     * @return int|WP_Error
     */
    static function add_link($menu_id, $title, $url, $parent_id = '') {
        $itemData =  array(
            'menu-item-object' => '',
            'menu-item-type'      => 'custom',
            'menu-item-title'    => $title,
            'menu-item-url' => $url,
            'menu-item-status'    => 'publish'
        );

        if (!empty($parent_id)) {
            $itemData['menu-item-parent-id'] = $parent_id;
        }

        $menu_item_id = wp_update_nav_menu_item($menu_id, 0, $itemData);
        return $menu_item_id;
    }



    /**
     * @param $menu_id
     * @param string $title
     * @param $page_id
     * @param string $parent_id
     * @return int|WP_Error
     */
    static function add_page($menu_id, $title='', $page_id, $parent_id = '') {
        $itemData =  array(
            'menu-item-object-id' => $page_id,
            'menu-item-parent-id' => 0,
            'menu-item-object' => 'page',
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish'
        );

        if (!empty($parent_id)) {
            $itemData['menu-item-parent-id'] = $parent_id;
        }

        if (!empty($title)) {
            $itemData['menu-item-title'] = $title;
        }

        $menu_item_id = wp_update_nav_menu_item($menu_id, 0, $itemData);
        return $menu_item_id;
    }



    /**
     * @param $menu_id
     * @param $title
     * @param $category_id
     * @return int|WP_Error
     */
    static function add_mega_menu($menu_id, $title, $category_id) {
        $itemData =  array(
            'menu-item-object' => '',
            'menu-item-type'      => 'custom',
            'menu-item-title'    => $title,
            'menu-item-url' => '#',
            'menu-item-status'    => 'publish'
        );

        $menu_item_id =  wp_update_nav_menu_item($menu_id, 0, $itemData);
        update_post_meta($menu_item_id, 'td_mega_menu_cat', $category_id);
        return $menu_item_id;
    }


    /**
     * @param $menu_id
     * @param $title
     * @param $category_id
     * @param string $parent_id
     */
    static function add_category($menu_id, $title, $category_id, $parent_id = '') {
        $itemData =  array(
            'menu-item-title' => $title,
            'menu-item-object-id' => $category_id,
            'menu-item-db-id' => 0,
            'menu-item-url' => get_category_link($category_id),
            'menu-item-type' => 'taxonomy', //taxonomy
            'menu-item-status' => 'publish',
            'menu-item-object' => 'category',
        );

        if (!empty($parent_id)) {
            $itemData['menu-item-parent-id'] = $parent_id;
        }

        wp_update_nav_menu_item($menu_id, 0, $itemData);
    }


    /**
     * removes all the menus
     */
    static function remove() {
        foreach (self::$allowed_menu_names as $menu_name) {
            wp_delete_nav_menu($menu_name);
        }
    }
}


//$td_stacks_media->td_media_sideload_image('http://demo.tagdiv.com/newsmag/wp-content/uploads/2014/08/38.jpg', '');
class td_demo_media {
    /**
     * Download an image from the specified URL and attach it to a post.
     *
     * @since 2.6.0
     *
     * @param string $file The URL of the image to download
     * @param int $post_id The post ID the media is to be associated with
     * @param string $desc Optional. Description of the image
     * @return string|WP_Error Populated HTML img tag on success
     */
    static function add_image_to_media_gallery($file, $td_attachment_id, $post_id = '', $desc = null ) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');


        // Set variables for storage, fix file filename for query strings.
        preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
        $file_array = array();
        $file_array['name'] = basename( $matches[0] );

        // Download file to temp location.
        $file_array['tmp_name'] = download_url( $file );

        // If error storing temporarily, return the error.
        if ( is_wp_error( $file_array['tmp_name'] ) ) {
            @unlink($file_array['tmp_name']);
            return $file_array['tmp_name'];
        }

        // Do the validation and storage stuff.
        $id = media_handle_sideload( $file_array, $post_id, $desc ); //$id of attachement or wp_error

        // If error storing permanently, unlink.
        if ( is_wp_error( $id ) ) {
            @unlink( $file_array['tmp_name'] );
            return $id;
        }


        update_post_meta($id, 'td_demo_attachment', $td_attachment_id);

        return $id;
    }



    static function remove() {
        $args = array(
            'post_type' => array('attachment'),
            'post_status' => 'inherit',
            'meta_key'  => 'td_demo_attachment',
        );
        $query = new WP_Query( $args );


        if (!empty($query->posts)) {
            foreach ($query->posts as $post) {
                wp_delete_attachment($post->ID, true);
            }
        }
    }
}