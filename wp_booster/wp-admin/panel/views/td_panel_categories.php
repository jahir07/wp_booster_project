<?php


/**
 * custom walker
 * Class td_category_walker_panel
 */
class td_category_walker_panel extends Walker {
    var $tree_type = 'category';
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');


    var $td_category_hierarchy = array();  // we store them like so [0] Category 1 - [1] Category 2 - [2] Category 3


    var $td_category_buffer = array();

    function start_lvl( &$output, $depth = 0, $args = array() ) {

    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {

    }


    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

        if (!isset($td_last_category_objects[$depth])) {
            $this->td_category_hierarchy[$depth] = $category;
        }


        if ($depth == 0) {
            //reset the parrents
            $this->td_category_hierarchy = array();
            //put the
            $this->td_category_hierarchy[0] = $category;

            //add first parent
            $this->td_category_buffer['<a href="' . get_category_link($category->term_id) . '" target="_blank" data-is-category-link="yes">' . $category->name . '</a>'] = $category->term_id;
        } else {

            $td_tmp_buffer = '';
            $last_cat_id = 0;
            $contor_array = 0;
            //print_r($this->td_category_hierarchy);
            foreach ($this->td_category_hierarchy as $parent_cat_obj) {

                if ($td_tmp_buffer === '') {
                    $td_tmp_buffer = '<a href="' . get_category_link($parent_cat_obj->term_id) . '" target="_blank" data-is-category-link="yes">' . $parent_cat_obj->name . '</a>';
                    $last_cat_id = $parent_cat_obj->term_id;
                } else {
                    if($this->td_category_hierarchy[$contor_array-1]->term_id == $parent_cat_obj->parent) {
                        $td_tmp_buffer .=  '<img src="' . get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/panel-breadcrumb.png" class="td-panel-breadcrumb"/>' . '<a href="' . get_category_link($parent_cat_obj->term_id) . '" target="_blank" data-is-category-link="yes">' . $parent_cat_obj->name . '</a>';
                        $last_cat_id = $parent_cat_obj->term_id;
                    }
                }

                $contor_array++;

            }


            //add child
            $this->td_category_buffer[$td_tmp_buffer] = $last_cat_id;

        }


    }


    function end_el( &$output, $page, $depth = 0, $args = array() ) {

    }

}






class td_display_categories_sttings {

    /**
     * render the categories forms
     */
    static function render_categories_form() {
        //get all categories from database


        $categories = get_categories(array(
            'hide_empty' => 0
        ));



        $td_category_walker_panel = new td_category_walker_panel;
        $td_category_walker_panel->walk($categories, 4);

        //print_r($rawalker->td_category_buffer);
//die;

        //get_categories(array('hide_empty' => 0));//wordpress way
        //$categories = td_util::get_category2id_array(false);//our function


        foreach ($td_category_walker_panel->td_category_buffer as $display_category_name => $category_id) {

            ?>
            <!-- LAYOUT SETTINGS -->
            <?php
            echo td_panel_generator::ajax_box($display_category_name, array(
                    'td_ajax_calling_file' => basename(__FILE__),
                    'td_ajax_box_id' => 'td_get_category_section_by_id',
                    'category_id' => $category_id
                )
            );



        }//end foreach

    }//end function

}//end class



?>



<!-- CATEGORY page -->
<?php echo td_panel_generator::box_start('Category global settings', true); ?>


    <div class="td-box-description td-box-full">
        <p>Set the default layout for all the categories. Note that you can change the layout and settings on each category from Theme panel ⇢ Categories</p>
        <ul>
            <li>You can view each category page by going to Posts ⇢ Categories ⇢ hover on a category ⇢ select view</li>
            <li>This WordPress template is located in <strong>category.php</strong> file.</li>
        </ul>
    </div>


    <!-- Category template -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">Category template</span>
            <p>This is the header of the category</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_option',
                'option_id' => 'tds_category_template',
                'values' => td_api_category_template::_helper_to_panel_values()
            ));
            ?>
        </div>
    </div>



<div class="td-box-section-separator"></div>

    <!-- Category top posts style -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">Category top posts style</span>
            <p>Set top post style</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_option',
                'option_id' => 'tds_category_top_posts_style',
                'values' => td_api_category_top_posts_style::_helper_to_panel_values()
            ));
            ?>
        </div>
    </div>

    <?php
    // show the $big_grid_styles_list only if we have big grids
    // Newsmag as of 10 march is not using $big_grid_styles_list
    if (!empty(td_global::$big_grid_styles_list)) {
        ?>
        <div class="td-box-row">
            <div class="td-box-description">
                <span class="td-box-title">Category top posts GRID STYLE</span>
                <p>Each category grid supports multiple styles</p>
            </div>
            <div class="td-box-control-full">
                <?php

                $td_grid_style_values = array();
                foreach (td_global::$big_grid_styles_list as $big_grid_id => $params) {
                    $td_grid_style_values []= array(
                        'text' => $params['text'],
                        'val' => $big_grid_id
                    );
                }
                echo td_panel_generator::dropdown(array(
                    'ds' => 'td_option',
                    'option_id' => 'tds_category_td_grid_style',
                    'values' => $td_grid_style_values
                ));
                ?>
            </div>
        </div>
    <?php } ?>

<div class="td-box-section-separator"></div>


    <!-- DISPLAY VIEW -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">ARTICLE DISPLAY VIEW</span>
            <p>Select a module type, this is how your article list will be displayed</p>
        </div>
        <div class="td-box-control-full td-panel-module">
            <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_option',
                'option_id' => 'tds_category_page_layout',
                'values' => td_panel_generator::helper_display_modules('enabled_on_loops')
            ));
            ?>
        </div>
    </div>



    <!-- Custom Sidebar + position -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">CUSTOM SIDEBAR + POSITION</span>
            <p>Sidebar position and custom sidebars</p>
        </div>
        <div class="td-box-control-full td-panel-sidebar-pos">
            <div class="td-display-inline-block">
                <?php
                echo td_panel_generator::visual_select_o(array(
                    'ds' => 'td_option',
                    'option_id' => 'tds_category_sidebar_pos',
                    'values' => array(
                        array('text' => '', 'title' => '', 'val' => 'sidebar_left', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-left.png'),
                        array('text' => '', 'title' => '', 'val' => 'no_sidebar', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-full.png'),
                        array('text' => '', 'title' => '', 'val' => '', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-right.png')
                    )
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Select sidebar position</div>
            </div>
            <div class="td-display-inline-block td_sidebars_pulldown_align">
                <?php
                echo td_panel_generator::sidebar_pulldown(array(
                    'ds' => 'td_option',
                    'option_id' => 'tds_category_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>






<hr>
<div class="td-section-separator">Per category settings</div>




<?php


//start building the categories form
td_display_categories_sttings::render_categories_form();