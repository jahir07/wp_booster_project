<?php
/**
 * Created by ra on 2/20/2015.
 */
class td_module_single_base extends td_module {

    var $td_post_theme_settings;

    var $is_single; //true if we are on a single page



    function __construct($post = '') {

        //run the parent constructor
        parent::__construct($post);

        //read post settings
        $this->td_post_theme_settings = get_post_meta($post->ID, 'td_post_theme_settings', true);

        $this->is_single = is_single();
    }



    function get_post_pagination() {
        if (!$this->is_single) {
            return;
        }


        return wp_link_pages(array(
            'before' => '<div class="page-nav page-nav-post td-pb-padding-side">',
            'after' => '</div>',
            'link_before' => '<div>',
            'link_after' => '</div>',
            'echo' => false,
            'nextpagelink'     => '<i class="td-icon-menu-right"></i>',
            'previouspagelink' => '<i class="td-icon-menu-left"></i>'
        ));
    }

    /**
     * @param string $cut_at - not used, it's added to maintain strict standards
     * @return string
     */
    function get_title($cut_at = '') {
        //just use h1 instead of h3
        $var_single = 0;
        if (is_single()) {
            $var_single = 1;
        }

        $buffy = '';
        $buffy .= '<h1 itemprop="name" class="entry-title">';

        if ($var_single == 0) {
            $buffy .='<a itemprop="url" href="' . $this->href . '" rel="bookmark" title="' . $this->title_attribute . '">';
        }

        $buffy .= $this->title;

        if ($var_single == 0) {
            $buffy .='</a>';
        }

        $buffy .= '</h1>';
        return $buffy;
    }

    //$show_stars_on_review - not used
    function get_author() {
        $buffy = '';
        if (td_util::get_option('tds_p_show_author_name') != 'hide') {
            $buffy .= '<div class="td-post-author-name">' . __td('By', TD_THEME_NAME) . ' ';
            $buffy .= '<a itemprop="author" href="' . get_author_posts_url($this->post->post_author) . '">' . get_the_author_meta('display_name', $this->post->post_author) . '</a>' ;

            if (td_util::get_option('tds_p_show_author_name') != 'hide' and td_util::get_option('tds_p_show_date') != 'hide') {
                $buffy .= ' - ';
            }
            $buffy .= '</div>';
        }
        return $buffy;
    }


    /**
     * v3 23 ian 2015
     * @param $thumbType
     * @return string
     */
    function get_image($thumbType) {
        global $page;


//        if ( //check to see if the current single template is configured to show the featured image on the second page
//            !isset( td_global::$single_templates_list[td_global::$cur_single_template]['show_featured_image_on_all_pages'] )
//            or td_global::$single_templates_list[td_global::$cur_single_template]['show_featured_image_on_all_pages'] === false
//        ){

        if (td_api_single_template::_check_show_featured_image_on_all_pages(td_global::$cur_single_template) === false) {
            //the post is configured to show the featured image only on the first page
            if (!empty($page) and $page > 1) {
                return '';
            }
        }


        //do not show the featured image if the global setting is set to hide - show the video preview regardless of the setting
        if (td_util::get_option('tds_show_featured_image') == 'hide' and get_post_format($this->post->ID) != 'video') {
            return '';
        }


        //handle video post format
        if (get_post_format($this->post->ID) == 'video') {
            //if it's a video post...
            $td_post_video = get_post_meta($this->post->ID, 'td_post_video', true);
            $td_video_support = new td_video_support();

            //render the video if the post has a video in the featured video section of the post
            if (!empty($td_post_video['td_video'])) {
                return $td_video_support->renderVideo($td_post_video['td_video']);
            }
        } else {
            //if it's a normal post, show the default thumb

            if ($this->post_has_thumb) {
                //get the featured image id + full info about the 640px wide one
                $featured_image_id = get_post_thumbnail_id($this->post->ID);
                $featured_image_info = td_util::attachment_get_full_info($featured_image_id, $thumbType);

                //get the full size for the popup
                $featured_image_full_size_src = td_util::attachment_get_src($featured_image_id, 'full');

                $buffy = '';

                $show_td_modal_image = td_util::get_option('tds_featured_image_view_setting') ;

                if (is_single()) {
                    if ($show_td_modal_image != 'no_modal') {
                        //wrap the image_html with a link + add td-modal-image class
                        $image_html = '<a href="' . $featured_image_full_size_src['src'] . '" data-caption="' . esc_attr($featured_image_info['caption'], ENT_QUOTES) . '">';
                        $image_html .= '<img width="' . $featured_image_info['width'] . '" height="' . $featured_image_info['height'] . '" itemprop="image" class="entry-thumb td-modal-image" src="' . $featured_image_info['src'] . '" alt="' . $featured_image_info['alt']  . '" title="' . $featured_image_info['title'] . '"/>';
                        $image_html .= '</a>';
                    } else { //no_modal
                        $image_html = '<img width="' . $featured_image_info['width'] . '" height="' . $featured_image_info['height'] . '" itemprop="image" class="entry-thumb" src="' . $featured_image_info['src'] . '" alt="' . $featured_image_info['alt']  . '" title="' . $featured_image_info['title'] . '"/>';
                    }
                } else {
                    //on blog index page
                    $image_html = '<a href="' . $this->href . '"><img width="' . $featured_image_info['width'] . '" height="' . $featured_image_info['height'] . '" itemprop="image" class="entry-thumb" src="' . $featured_image_info['src'] . '" alt="' . $featured_image_info['alt']  . '" title="' . $featured_image_info['title'] . '"/></a>';
                }


                $buffy .= '<div class="td-post-featured-image">';

                // caption - put html5 wrapper on when we have a caption
                if (!empty($featured_image_info['caption'])) {
                    $buffy .= '<figure>';
                    $buffy .= $image_html;

                    $buffy .= '<figcaption class="wp-caption-text">' . $featured_image_info['caption'] . '</figcaption>';
                    $buffy .= '</figure>';
                } else {
                    $buffy .= $image_html;
                }

                $buffy .= '</div>';


                return $buffy;
            } else {
                return ''; //the post has no thumb
            }
        }
    }


    function get_category() {

        $buffy = '';
        if (td_util::get_option('tds_p_categories_tags') != 'hide') {
            $buffy .= '<ul class="td-category">';
            $categories = get_the_category( $this->post->ID );
            $cat_array  = array();

            if ( $categories ) {
                foreach ( $categories as $category ) {
                    if ( $category->name != TD_FEATURED_CAT ) { //ignore the featured category name
                        //get the parent of this cat
                        $td_parent_cat_obj = get_category( $category->category_parent );

                        //if we have a parent, shot it first
                        if ( ! empty( $td_parent_cat_obj->name ) ) {
                            $tax_meta__color_parent                = td_util::get_category_option( $td_parent_cat_obj->cat_ID, 'tdc_color' );//swich by RADU A, get_tax_meta($td_parent_cat_obj->cat_ID,'tdc_color');
                            $tax_meta__hide_on_post_parent         = td_util::get_category_option( $td_parent_cat_obj->cat_ID, 'tdc_hide_on_post' );//swich by RADU A, get_tax_meta($td_parent_cat_obj->cat_ID,'tdc_hide_on_post');
                            $cat_array[ $td_parent_cat_obj->name ] = array(
                                'color'        => $tax_meta__color_parent,
                                'link'         => get_category_link( $td_parent_cat_obj->cat_ID ),
                                'hide_on_post' => $tax_meta__hide_on_post_parent
                            );
                        }

                        //show the category, only if we didn't already showed the parent
                        $tax_meta_color                = td_util::get_category_option( $category->cat_ID, 'tdc_color' );//swich by RADU A, get_tax_meta($category->cat_ID,'tdc_color');
                        $tax_meta__hide_on_post_parent = td_util::get_category_option( $category->cat_ID, 'tdc_hide_on_post' );//swich by RADU A, get_tax_meta($category->cat_ID,'tdc_hide_on_post');
                        $cat_array[ $category->name ]  = array(
                            'color'        => $tax_meta_color,
                            'link'         => get_category_link( $category->cat_ID ),
                            'hide_on_post' => $tax_meta__hide_on_post_parent
                        );
                    }
                }
            }

            foreach ( $cat_array as $td_cat_name => $td_cat_parms ) {
                if ( $td_cat_parms['hide_on_post'] == 'hide' ) {
                    continue;
                }

                if ( ! empty( $td_cat_parms['color'] ) ) {
                    $td_cat_color = ' style="background-color:' . $td_cat_parms['color'] . ';"';
                } else {
                    $td_cat_color = '';
                }


                $buffy .= '<li class="entry-category"><a ' . $td_cat_color . ' href="' . $td_cat_parms['link'] . '">' . $td_cat_name . '</a></li>';
            }
            $buffy .= '</ul>';
        }

        return $buffy;
    }









    function get_comments() {
        $buffy = '';
        if (td_util::get_option('tds_p_show_comments') != 'hide') {
            $buffy .= '<div class="td-post-comments">';
            $buffy .= '<a href="' . get_comments_link($this->post->ID) . '"><i class="td-icon-comments"></i>';
            $buffy .= get_comments_number($this->post->ID);
            $buffy .= '</a>';
            $buffy .= '</div>';
        }

        return $buffy;
    }

    function get_views() {
        $buffy = '';
        if (td_util::get_option('tds_p_show_views') != 'hide') {
            $buffy .= '<div class="td-post-views">';
            $buffy .= '<i class="td-icon-views"></i>';
            // WP-Post Views Counter
            if (function_exists('the_views')) {
                $post_views = the_views(false);
                $buffy .= $post_views;
            }
            // Default Theme Views Counter
            else {
                $buffy .= '<span class="td-nr-views-' . $this->post->ID . '">' . td_page_views::get_page_views($this->post->ID) .'</span>';
            }

            $buffy .= '</div>';
        }
        return $buffy;
    }



    function get_content() {

        /*  ----------------------------------------------------------------------------
            Prepare the content
        */
        $content = get_the_content(__td('Continue', TD_THEME_NAME));
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);



        /** ----------------------------------------------------------------------------
         * Smart list support. class_exists and new object WORK VIA AUTOLOAD
         * @see td_autoload_classes::loading_classes
         */
	    //$td_smart_list = get_post_meta($this->post->ID, 'td_smart_list', true);
        $td_smart_list = get_post_meta($this->post->ID, 'td_post_theme_settings', true);
        if (!empty($td_smart_list['smart_list_template'])) {

            $td_smart_list_class = $td_smart_list['smart_list_template'];
            if (class_exists($td_smart_list_class)) {
                $td_smart_list_obj = new $td_smart_list_class();  // make the class from string * magic :)
                return $td_smart_list_obj->render_from_post_content($content);
            } else {
                // there was an error?
                td_util::error(__FILE__, 'Missing smart list: ' . $td_smart_list_class . '. Did you disabled a tagDiv plugin?');
            }
        }
        /*  ----------------------------------------------------------------------------
            end smart list - if we have a list, the function returns above
         */




        /*  ----------------------------------------------------------------------------
            ad support on content
        */

        //read the current ad settings
        $tds_inline_ad_paragraph = td_util::get_option('tds_inline_ad_paragraph');
        $tds_inline_ad_align = td_util::get_option('tds_inline_ad_align');


        //add the inline ad
        if (td_util::is_ad_spot_enabled('content_inline') and is_single()) {

            if (empty($tds_inline_ad_paragraph)) {
                $tds_inline_ad_paragraph = 0;
            }

            $cnt = 0;
            $content_buffer = ''; // we replace the content with this buffer at the end

	        $content_parts = preg_split('/(<p.*>)/U', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            foreach ($content_parts as $content_part_index => $content_part_value) {
	            if (!empty($content_part_value)) {

		            if ($tds_inline_ad_paragraph == ($content_part_index / 2)) {
                        //it's time to show the ad
                        switch ($tds_inline_ad_align) {
                            case 'left':
                                $content_buffer .= td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'content_inline', 'align' => 'left'));
                                break;

                            case 'right':
                                $content_buffer .= td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'content_inline', 'align' => 'right'));
                                break;

                            default:

                                $content_buffer .= td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'content_inline'));
                                break;
                        }
                    }
                    $content_buffer .= $content_part_value;
                    $cnt++;
                }
            }
            $content = $content_buffer;
        }

        //add the top ad
        if (td_util::is_ad_spot_enabled('content_top') and is_single()) {
            $content = td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'content_top')) . $content;
        }


        //add bottom ad
        if (td_util::is_ad_spot_enabled('content_bottom') and is_single()) {
            $content = $content . td_global_blocks::get_instance('td_block_ad_box')->render(array('spot_id' => 'content_bottom'));
        }


        return $content;
    }


    function get_item_scope() {
        //show the review meta only on single posts that are reviews, the rest have to be article (in article lists)
        if (td_review::has_review($this->td_review) and is_single()) {
            return 'itemscope itemtype="' . td_global::$http_or_https . '://schema.org/Review"';
        } else {
            return 'itemscope itemtype="' . td_global::$http_or_https . '://schema.org/Article"';
        }
    }


    /**
     * returns the item scope meta. It returns an empty string if the module is used in a loop (not on a single page)
     * 16 march 2015
     * @return string
     */
    function get_item_scope_meta() {

        if (!is_single()) {
            return '';
        }
        $buffy = ''; //the vampire slayer

        $author_id = $this->post->post_author;
        $buffy .= '<meta itemprop="author" content = "' . get_the_author_meta('display_name', $author_id) . '">';

        $buffy .= '<meta itemprop="interactionCount" content="UserComments:' . get_comments_number($this->post->ID) . '"/>';

        if (td_review::has_review($this->td_review)) {
            $td_article_date_unix = get_the_time('U', $this->post->ID);

            $buffy .= '<meta itemprop="itemReviewed " content = "' . $this->title . '">';

            if (!empty($this->td_review['review'])) {
                $buffy .= '<meta itemprop="about" content = "' . esc_attr($this->td_review['review']) . '">';
            } else {
                //we have no review :|

                //get a damn excerpt for the metatag
                if ($this->post->post_excerpt != '') {
                    $td_post_excerpt = $this->post->post_excerpt;
                } else {
                    $td_post_excerpt = td_util::excerpt($this->post->post_content, 25);
                }
                $buffy .= '<meta itemprop="about" content = "' . esc_attr($td_post_excerpt) . '">';
            }


            $buffy .= '<meta itemprop="datePublished" content="' . date(DATE_W3C, $td_article_date_unix) . '">';
            $buffy .= '<span class="td-page-meta" itemprop="reviewRating" itemscope itemtype="' . td_global::$http_or_https . '://schema.org/Rating">';
            $buffy .= '<meta itemprop="worstRating" content = "1">';
            $buffy .= '<meta itemprop="bestRating" content = "5">';
            $buffy .= '<meta itemprop="ratingValue" content = "' . td_review::calculate_total_stars($this->td_review) . '">';
            $buffy .= ' </span>';
        }
        return $buffy;
    }



    /*  ----------------------------------------------------------------------------
        Single page
     */
    function get_review() {
        if (!$this->is_single) {
            return;
        }

        if (td_review::has_review($this->td_review)) {
            //print_r($this->td_review);
            $buffy = '';
            $buffy .= td_review::render_table($this->td_review);



            return $buffy;
        }

    }

    function get_source_and_via() {
        if (!$this->is_single) {
            return;
        }


        $buffy ='';

        //via and source
        if (!empty($this->td_post_theme_settings['td_source']) or !empty($this->td_post_theme_settings['td_via'])) {
            $via_url = '#';
            $source_url = '#';

	        // used to check is post have tags to align the source and via container
	        $td_no_tags = '';
	        if (!has_tag()) {
		        $td_no_tags = 'td-no-tags';
	        }

            if (!empty($this->td_post_theme_settings['td_via_url'])) {
                $via_url = $this->td_post_theme_settings['td_via_url'];
            }

            if (!empty($this->td_post_theme_settings['td_source_url'])) {
                $source_url = $this->td_post_theme_settings['td_source_url'];
            }

            $buffy .= '<div class="td-post-source-via ' . $td_no_tags . '">';
            if (!empty($this->td_post_theme_settings['td_via'])) {
                $buffy .= '<div class="td-post-small-box"><span>' . __td('VIA', TD_THEME_NAME) . '</span><a rel="nofollow" href="' . esc_url($via_url) . '">' . $this->td_post_theme_settings['td_via'] . '</a></div>';
            }

            if (!empty($this->td_post_theme_settings['td_source'])) {
                $buffy .= '<div class="td-post-small-box"><span>' . __td('SOURCE', TD_THEME_NAME) . '</span><a rel="nofollow" href="' . esc_url($source_url) . '">' . $this->td_post_theme_settings['td_source'] . '</a></div>';
            }
            $buffy .= '</div>';
        }


        return $buffy;
    }


    function get_the_tags() {
        if (!$this->is_single) {
            return;
        }

        if (td_util::get_option('tds_show_tags') == 'hide') {
            return;
        }


        $buffy = '';

        $td_post_tags = get_the_tags();
        if ($td_post_tags) {
            $buffy .= '<ul class="td-tags td-post-small-box clearfix">';
            $buffy .= '<li><span>' . __td('TAGS', TD_THEME_NAME) . '</span></li>';
            foreach ($td_post_tags as $tag) {
                $buffy .=  '<li><a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a></li>';
            }
            $buffy .= '</ul>';
        }

        return $buffy;
    }

    function get_next_prev_posts() {
        if (!$this->is_single) {
            return;
        }

        if (td_util::get_option('tds_show_next_prev') == 'hide') {
            return;
        }

        $buffy = '';

        $next_post = get_next_post();
        $prev_post = get_previous_post();

        if (!empty($next_post) or !empty($prev_post)) {
            $buffy .= '<div class="td-block-row td-post-next-prev">';
            if (!empty($prev_post)) {
                $buffy .= '<div class="td-block-span6 td-post-prev-post">';
                $buffy .= '<div class="td-post-next-prev-content"><span>' .__td('Previous article', TD_THEME_NAME) . '</span>';
                $buffy .= '<a href="' . esc_url(get_permalink($prev_post->ID)) . '">' . $prev_post->post_title . '</a>';
                $buffy .= '</div>';
                $buffy .= '</div>';
            } else {
                $buffy .= '<div class="td-block-span6 td-post-prev-post">';
                $buffy .= '</div>';
            }
            $buffy .= '<div class="td-next-prev-separator"></div>';
            if (!empty($next_post)) {
                $buffy .= '<div class="td-block-span6 td-post-next-post">';
                $buffy .= '<div class="td-post-next-prev-content"><span>' .__td('Next article', TD_THEME_NAME) . '</span>';
                $buffy .= '<a href="' . esc_url(get_permalink($next_post->ID)) . '">' . $next_post->post_title . '</a>';
                $buffy .= '</div>';
                $buffy .= '</div>';
            }
            $buffy .= '</div>'; //end fluid row
        }

        return $buffy;
    }

    function get_author_box($author_id = '') {

        if (!$this->is_single) {
            return;
        }



        if (td_util::get_option('tds_show_author_box') == 'hide') {
            $buffy = '<div class="td-author-name vcard author" style="display: none"><span class="fn">';
            $buffy .= '<a href="' . get_author_posts_url($author_id) . '">' . get_the_author_meta('display_name', $author_id) . '</a>' ;
            $buffy .= '</span></div>';
            return $buffy;
        }



        if (empty($author_id)) {
            $author_id = $this->post->post_author;
        }


        $buffy = '';

        $authorDescription = get_the_author_meta('description');
        $hideAuthor = td_util::get_option('hide_author');

        if (empty($hideAuthor)) {

            $buffy .= '<div class="author-box-wrap">';
            $buffy .= '<a itemprop="author" href="' . get_author_posts_url($author_id) . '">' ;
            $buffy .= get_avatar(get_the_author_meta('email', $author_id), '96');
            $buffy .= '</a>';


            $buffy .= '<div class="desc">';
            $buffy .= '<div class="td-author-name vcard author"><span class="fn">';
            $buffy .= '<a itemprop="author" href="' . get_author_posts_url($author_id) . '">' . get_the_author_meta('display_name', $author_id) . '</a>' ;
            $buffy .= '</span></div>';

            if (get_the_author_meta('user_url', $author_id) != '') {
                $buffy .= '<div class="td-author-url"><a href="' . get_the_author_meta('user_url', $author_id) . '">' . get_the_author_meta('user_url', $author_id) . '</a></div>';
            }

            $buffy .= '<div class="td-author-description">';
            $buffy .=  get_the_author_meta('description', $author_id);
            $buffy .= '</div>';


            $buffy .= '<div class="td-author-social">';
            foreach (td_social_icons::$td_social_icons_array as $td_social_id => $td_social_name) {
                //echo get_the_author_meta($td_social_id) . '<br>';
                $authorMeta = get_the_author_meta($td_social_id);
                if (!empty($authorMeta)) {

                    //the theme can use the twitter id instead of the full url. This avoids problems with yoast plugin
                    if ($td_social_id == 'twitter') {
                        if(filter_var($authorMeta, FILTER_VALIDATE_URL)){

                        } else {
                            $authorMeta = str_replace('@', '', $authorMeta);
                            $authorMeta = 'http://twitter.com/' . $authorMeta;
                        }
                    }
                    $buffy .= td_social_icons::get_icon($authorMeta, $td_social_id, 4, 16);
                }
            }
            $buffy .= '</div>';



            $buffy .= '<div class="clearfix"></div>';

            $buffy .= '</div>'; ////desc
            $buffy .= '</div>'; //author-box-wrap
        }


        return $buffy;
    }


    function related_posts($force_sidebar_position = '') {

        if (!$this->is_single) {
            return;
        }


        if (td_util::get_option('tds_similar_articles') == 'hide') {
            return;
        }




        //td_global::$cur_single_template_sidebar_pos;

        //cur_post_same_tags
        //cur_post_same_author
        //cur_post_same_categories

        if (td_util::get_option('tds_similar_articles_type') == 'by_tag') {
            $td_related_ajax_filter_type = 'cur_post_same_tags';
        } else {
            $td_related_ajax_filter_type = 'cur_post_same_categories';
        }


        // the number of rows to show. this number will be multiplied with the hardcoded limit
        $tds_similar_articles_rows = td_util::get_option('tds_similar_articles_rows');
        if (empty($tds_similar_articles_rows)) {
            $tds_similar_articles_rows = 1;
        }

        if (td_global::$cur_single_template_sidebar_pos == 'no_sidebar' or $force_sidebar_position === 'no_sidebar') {
            $td_related_limit = 5 * $tds_similar_articles_rows;
            $td_related_class = 'td-related-full-width';
            $td_column_number = 5;
        } else {
            $td_related_limit = 3 * $tds_similar_articles_rows;
            $td_related_class = '';
            $td_column_number = 3;
        }



        /**
         * 'td_ajax_filter_type' => 'td_custom_related' - this ajax filter type overwrites the live filter via ajax @see td_ajax_block
         */
        $td_block_args = array (
            'limit' => $td_related_limit,
            'ajax_pagination' => 'next_prev',
            'live_filter' => $td_related_ajax_filter_type,  //live atts - this is the default setting for this block
            'td_ajax_filter_type' => 'td_custom_related', //this filter type can overwrite the live filter @see
            'class' => $td_related_class,
            'td_column_number' => $td_column_number
        );


        /**
         * @see td_block_related_posts
         */
        return td_global_blocks::get_instance('td_block_related_posts')->render($td_block_args);

    }


}