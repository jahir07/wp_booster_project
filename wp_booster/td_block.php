<?php

/**
 * Class td_block - base class for blocks
 * v 4.0 - wp_010
 */
class td_block {
    var $block_id; // the block type
    var $block_uid; // the block unique id, it changes on every render

    var $atts; //the atts used for rendering the current block
    var $td_query; //the query used to rendering the current block

    private $td_block_template_instance; // the current block template instance that this block is using

    function __construct() {
        $this->block_id = get_class($this); // set the current block type id It is the class name of the parent block (ex: td_block_4)
    }



    /**
     * the base render function. This is called by all the child classes of this class
     * this function also ECHOES the block specific css to the buffer (for hover and stuff)
     * WARNING! THIS FUNCTIONS ECHOs THE CSS - it was made to work this way as a hack because the blocks do not get the returned value of render in a buffer
     * @param $atts
     * @return string ''
     */
    function render($atts, $content = null) {

        $this->atts = $this->add_live_filter_atts($atts); //add live filter atts
        $this->block_uid = td_global::td_generate_unique_id(); //update unique id on each render
        $this->td_query = &td_data_source::get_wp_query($this->atts); //by ref do the query



        extract(shortcode_atts(
            array(
                'td_ajax_filter_type' => '',
                'td_ajax_filter_ids' => '',
                'td_filter_default_txt' => 'All'
            ),$this->atts));



        // prepare the array for the td_pull_down_items, we send this array to the block_template
        $td_pull_down_items = array();
        if (!empty($td_ajax_filter_type)) {

            // make the default current pull down item (the first one is the default)
            $td_pull_down_items[0] = array (
                'name' => $td_filter_default_txt,
                'id' => ''
            );

            switch($td_ajax_filter_type) {
                case 'td_category_ids_filter': // by category
                    $td_categories = get_categories(array(
                        'include' => $td_ajax_filter_ids
                    ));
                    foreach ($td_categories as $td_category) {
                        $td_pull_down_items []= array (
                            'name' => $td_category->name,
                            'id' => $td_category->cat_ID,
                        );
                    }
                    break;

                case 'td_author_ids_filter': // by author
                    $td_authors = get_users(array('who' => 'authors', 'include' => $td_ajax_filter_ids));
                    foreach ($td_authors as $td_author) {
                        $td_pull_down_items []= array (
                            'name' => $td_author->display_name,
                            'id' => $td_author->ID,
                        );
                    }
                    break;

                case 'td_tag_slug_filter': // by tag slug
                    $td_tags = get_tags(array(
                        'include' => $td_ajax_filter_ids
                    ));
                    foreach ($td_tags as $td_tag) {
                        $td_pull_down_items []= array (
                            'name' => $td_tag->name,
                            'id' => $td_tag->term_id,
                        );
                    }
                    break;

                case 'td_popularity_filter_fa': // by popularity
                    $td_pull_down_items []= array (
                        'name' => __td('Featured'),
                        'id' => 'featured',
                    );
                    $td_pull_down_items []= array (
                        'name' => __td('All time popular'),
                        'id' => 'popular',
                    );
                    break;
            }
        }


        // add a persistent atts based block class (crc32 of atts + block_id)
        if (is_array($this->atts)) {  // double check to prevent warnings if no atts
            $this->add_class('td_block_id_' .
                sanitize_html_class(
                    str_replace('-', '',
                        crc32(
                            implode($this->atts) . $this->block_id
                        )
                    )
                )
            );
        }


        // add a unique class to the block
        $unique_block_class = $this->block_uid . '_rand';
        $this->add_class($unique_block_class);


        /**
         * Make a new block template instance (NOTE: ON EACH RENDER WE GENERATE A NEW BLOCK TEMPLATE)
         * td_block_template_x - Loaded via autoload
         * @see td_autoload_classes::loading_classes
         */
        $td_block_template_id = 'td_block_template_1';
        $this->td_block_template_instance = new $td_block_template_id(array(
            'atts' => $this->atts,
            'block_uid' => $this->block_uid,
            'unique_block_class' => $unique_block_class,
            'td_pull_down_items' => $td_pull_down_items,
        ));




        // echo the default style of the block
        echo $this->block_template()->get_css();

        return '';
    }


    /**
     * this function adds the live filters atts (for example the current category or the current post)
     * @param $atts
     * @return mixed
     */
    function add_live_filter_atts($atts) {
        if (!empty($atts['live_filter'])) {
            $atts['live_filter_cur_post_id'] = get_queried_object_id(); //add the current post id
            $atts['live_filter_cur_post_author'] =  get_post_field( 'post_author', $atts['live_filter_cur_post_id']); //get the current author
        }
        return $atts;
    }



    /**
     * Used by blocks that need auto generated titles
     * @return string
     */
    function get_block_title() {
        return $this->block_template()->get_block_title();
    }


    /**
     * shows a pull down filter based on the $this->atts
     * @return string
     */
    function get_pull_down_filter() {
        return $this->block_template()->get_pull_down_filter();
    }




    function get_block_pagination() {
        extract(shortcode_atts(
            array(
                'limit' => 5,
                'sort' => '',
                'category_id' => '',
                'category_ids' => '',
                'custom_title' => '',
                'custom_url' => '',
                'show_child_cat' => '',
                'sub_cat_ajax' => '',
                'ajax_pagination' => ''
            ),$this->atts));

        $buffy = '';

        switch ($ajax_pagination) {

            case 'next_prev':
                    $buffy .= '<div class="td-next-prev-wrap">';
                    $buffy .= '<a href="#" class="td-ajax-prev-page ajax-page-disabled" id="prev-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '"><i class="td-icon-font td-icon-menu-left"></i></a>';

                    if ($this->td_query->found_posts <= $limit) {
                        //hide next page because we don't have enough results
                        $buffy .= '<a href="#"  class="td-ajax-next-page ajax-page-disabled" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '"><i class="td-icon-font td-icon-menu-right"></i></a>';
                    } else {
                        $buffy .= '<a href="#"  class="td-ajax-next-page" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '"><i class="td-icon-font td-icon-menu-right"></i></a>';
                    }

                    $buffy .= '</div>';
                break;

            case 'load_more':
                $buffy .= '<div class="td-load-more-wrap">';
                $buffy .= '<a href="#" class="td_ajax_load_more" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '">' . __td('Load more');
                $buffy .= '<i class="td-icon-font td-icon-menu-down"></i>';
                $buffy .= '</a>';
                $buffy .= '</div>';
                break;

            case 'infinite':
                $buffy .= '<div class="td_ajax_infinite" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '">';
                $buffy .= ' ';
                $buffy .= '</div>';



                $buffy .= '<div class="td-load-more-wrap td-load-more-infinite-wrap" id="infinite-lm-' . $this->block_uid . '">';
                $buffy .= '<a href="#" class="td_ajax_load_more" id="next-page-' . $this->block_uid . '" data-td_block_id="' . $this->block_uid . '">' . __td('Load more');
                $buffy .= '<i class="td-icon-font td-icon-menu-down"></i>';
                $buffy .= '</a>';
                $buffy .= '</div>';
                break;

        }

        return $buffy;
    }




    function get_block_js() {

        //get the js for this block - do not load it in inline mode in visual composer
        if (td_util::vc_is_inline()) {
            return '';
        }

        extract(shortcode_atts(
            array(
                'limit' => 5,
                'sort' => '',
                'category_id' => '',
                'category_ids' => '',
                'custom_title' => '',
                'custom_url' => '',
                'show_child_cat' => '',
                'sub_cat_ajax' => '',
                'ajax_pagination' => '',
                'header_color' => '',
                'ajax_pagination_infinite_stop' => '',
                'td_column_number' => '' //pass a user defined column number to the block
            ), $this->atts));


        if (!empty($this->atts['custom_title'])) {
            $this->atts['custom_title'] = htmlspecialchars($this->atts['custom_title'], ENT_QUOTES );
        }

        if (!empty($this->atts['custom_url'])) {
            $this->atts['custom_url'] = htmlspecialchars($this->atts['custom_url'], ENT_QUOTES );
        }

        if (empty($td_column_number)) {
            $td_column_number = td_util::vc_get_column_number(); // get the column width of the block so we can sent it to the server. If the shortcode already has a user defined column number, we use that
        }
        $block_item = 'block_' . $this->block_uid;

        $buffy = '';

        $buffy .= '<script>';
        $buffy .= 'var ' . $block_item . ' = new td_block();' . "\n";
        $buffy .= $block_item . '.id = "' . $this->block_uid . '";' . "\n";
        $buffy .= $block_item . ".atts = '" . json_encode($this->atts) . "';" . "\n";
        $buffy .= $block_item . '.td_column_number = "' . $td_column_number . '";' . "\n";
        $buffy .= $block_item . '.block_type = "' . $this->block_id . '";' . "\n";

        //wordpress wp query parms
        $buffy .= $block_item . '.post_count = "' . $this->td_query->post_count . '";' . "\n";
        $buffy .= $block_item . '.found_posts = "' . $this->td_query->found_posts . '";' . "\n";
        $buffy .= $block_item . '.max_num_pages = "' . $this->td_query->max_num_pages . '";' . "\n";
        $buffy .= $block_item . '.header_color = "' . $header_color . '";' . "\n";
        $buffy .= $block_item . '.ajax_pagination_infinite_stop = "' . $ajax_pagination_infinite_stop . '";' . "\n";


        $buffy .= 'td_blocks.push(' . $block_item . ');' . "\n";
        $buffy .= '</script>';

        return $buffy;
    }

    /**
     * @param $additional_classes_array - array of classes to add to the block
     * @return string
     */
    function get_block_classes($additional_classes_array = '') {
        $color_preset = '';

        extract(shortcode_atts(
            array(
                'color_preset' => '',
                'border_top' => '',
                'class' => '', //add additional classes via short code - used by the widget builder to add the td_block_widget class
            ),$this->atts));


        //add the block wrap and block id class
        $block_classes = array(
            'td_block_wrap',
            $this->block_id
        );

        //add the classes that we receive via shortcode
        if (!empty($class)) {
            $class_array = explode(' ', $class);
            $block_classes = array_merge(
                $block_classes,
                $class_array
            );
        }

        //marge the additional classes received from blocks code
        if ($additional_classes_array != '') {
            $block_classes = array_merge(
                $block_classes,
                $additional_classes_array
            );
        }


        //add the full cell class + the color preset class
        if (!empty($color_preset)) {
            $block_classes[]= 'td-pb-full-cell';
            $block_classes[]= $color_preset;
        }


        /**
         * add the border top class - this one comes from the atts
         */
        if (empty($border_top)) {
            $block_classes[]= 'td-pb-border-top';
        }


        //remove duplicates
        $block_classes = array_unique($block_classes);


        return implode(' ', $block_classes);
    }


    /**
     * adds a class to the current block's ats
     * @param $raw_class_name string the class name is not sanitized, so make sure you send a sanitized one
     */
    private function add_class($raw_class_name) {
        if (!empty($this->atts['class'])) {
            $this->atts['class'] = $this->atts['class'] . ' ' . $raw_class_name;
        } else {
            $this->atts['class'] = $raw_class_name;
        }
    }


    /**
     * gets the current template instance, if no instance it's found throws error
     * @return mixed the template instance
     * @throws ErrorException - no template instance found
     */
    private function block_template() {
        if (isset($this->td_block_template_instance)) {
            return $this->td_block_template_instance;
        } else {
            throw new ErrorException("td_block: " . get_class($this) . " did not call render, no td_block_template_instance in td_block");
        }
    }


}

