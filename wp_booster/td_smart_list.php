<?php
abstract class td_smart_list {

    private $counting_order_asc = false; //how to count the items in the list
    private $counting_start = 1; //start from 1 or 0 ? - As of 31 July 2015 IT'S NOT USED :(

    protected $use_pagination = false;


    abstract protected function render_list_item($item_array, $current_item_id, $current_item_number, $total_items_number); //child classes must implement this :)

    /**
     * renders a smart list form content. This should be the ONLY public thing for now
     * @param $smart_list_settings array of settings for the smart list
     * @return string
     */
    function render_from_post_content($smart_list_settings) {

        $this->counting_order_asc = $smart_list_settings['counting_order_asc'];


        // make a new tokenizer
        $td_tokenizer = new td_tokenizer();
        $td_tokenizer->token_title_start = $smart_list_settings['td_smart_list_h'];
        $td_tokenizer->token_title_end = $smart_list_settings['td_smart_list_h'];


        // get the list items
        $list_items = $td_tokenizer->split_to_list_items($smart_list_settings['post_content']);

        // we need to number all the items before pagination because item 2 can have number 4 if the counting method is desc
        $list_items = $this->add_numbers_to_list_items($list_items);




        // no items found, we return the content as is
        if (empty($list_items['list_items'])) {
            return $smart_list_settings['post_content'];
        }


        if ($this->use_pagination === true) {
            $td_paged = $this->get_current_page();
            return $this->render($list_items, $td_paged);
        } else {
            return $this->render($list_items);
        }

    }


    /**
     * Calculate the total item number and the current item number
     *  current item number can be asc, desc and start from 0 or 1 etc.
     * @param $list_items
     * @return array - $list_items with added 'current_item_number' and 'total_items_number' keys
     */
    private function add_numbers_to_list_items($list_items) {

        $total_items_number = count($list_items['list_items']) - 1 + $this->counting_start; // fix for 0 base counting (0 of 3 - to -  3 of 3)

        //render each item using the render_list_item method from the child class
        foreach ($list_items['list_items'] as $list_item_key => &$list_item) {

            //how to count (asc or desc)
            if ($this->counting_order_asc === true) {
                $current_item_index = $list_item_key + $this->counting_start;
            } else {
                $current_item_index = $total_items_number - ($list_item_key);
            }

            $list_item['current_item_number'] = $current_item_index;
            $list_item['total_items_number'] = $total_items_number;
        }

        return $list_items;
    }

    /**
     * This is the rendering function. It gets a list of items and it outputs HTML
     * @param $list_items - the smart list list of items
     * @return string - the smart list's HTML
     */
    private function render($list_items, $td_paged = false) {

        /*  ----------------------------------------------------------------------------
            build the item id to item name for the table of contents
        */
        $item_id_2_item_array = array();
        foreach ($list_items['list_items'] as $list_item_key => $list_item) {
            $item_id_2_item_array[$list_item_key + 1] = $list_item;
        }



        $buffy = '';

        /*  ----------------------------------------------------------------------------
            add the before_list content
         */
        if (!empty($list_items['before_list'])) {
            $buffy .= implode('', $list_items['before_list']);
        }


        /*  ----------------------------------------------------------------------------
            add the table of contents before
         */
        $buffy .= $this->render_table_of_contents_before($item_id_2_item_array);


        /*  ----------------------------------------------------------------------------
            add the list
         */
        $buffy .= $this->render_before_list_wrap();  //from child class

        if ($td_paged === false) {
            //render each item using the render_list_item method from the child class
            foreach ($list_items['list_items'] as $list_item_key => $list_item) {
                $buffy .= $this->render_list_item($list_item, $list_item_key + 1, $list_item['current_item_number'], $list_item['total_items_number']);
            }
        } else {
            $array_id_from_paged = $td_paged-1;
            $buffy .= $this->render_list_item(
                $list_items['list_items'][$array_id_from_paged],
                $array_id_from_paged,
                $list_items['list_items'][$array_id_from_paged]['current_item_number'],
                $list_items['list_items'][$array_id_from_paged]['total_items_number']
            );
        }


        $buffy .= $this->render_after_list_wrap(); //from child class - render the list wrap end


        /*  ----------------------------------------------------------------------------
            add the table of contents after
         */
        $buffy .= $this->render_table_of_contents_after($item_id_2_item_array);


        // render the bottom pagination
        $buffy .= $this->render_pagination_bottom($list_items);



        /*  ----------------------------------------------------------------------------
            add the after_list content
         */
        if (!empty($list_items['after_list'])) {
            $buffy .= implode('', $list_items['after_list']);
        }

        return $buffy;
    }




    private function render_pagination_bottom($list_items) {
        $paged = $this->get_current_page();
        $buffy = '';

        $total_pages = count($list_items['list_items']);

        // no pagination if we have one page!
        if ($total_pages == 1) {
            return '';
        }

//        echo $paged;
//        echo $total_pages;

        if ($paged == 1) {
            // first page
            $buffy .= $this->pagination_back_disabled();
            $buffy .= $this->pagination_next($this->_wp_link_page($paged + 1));
        }
        elseif ($paged == $total_pages) {
            // last page
            $buffy .= $this->pagination_back($this->_wp_link_page($paged - 1));
            $buffy .= $this->pagination_next_disabled();
        }
        else {
            // middle page
            $buffy .= $this->pagination_back($this->_wp_link_page($paged - 1));
            $buffy .= $this->pagination_next($this->_wp_link_page($paged + 1));

        }


        return $buffy;
    }

    protected function pagination_next($page_link) { return ''; }
    protected function pagination_next_disabled() { return ''; }
    protected function pagination_back($page_link) { return ''; }
    protected function pagination_back_disabled() { return ''; }


    private function get_current_page() {
        $td_page = (get_query_var('page')) ? get_query_var('page') : 1; //rewrite the global var
        $td_paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //rewrite the global var
        //paged works on single pages, page - works on homepage
        if ($td_paged > $td_page) {
            $paged = $td_paged;
        } else {
            $paged = $td_page;
        }
        // if no pages, we are on the first page
        if (empty($paged)) {
            $paged = 1;
        }
        return $paged;
    }


    /**
     * This function returns the pagination link for the current post
     * TAGDIV: - taken from wordpress wp-includes/post-template.php
     *         - we removed the wrapping <a>
     *
     * Helper function for wp_link_pages().
     *
     * @since 3.1.0
     * @access private
     *
     * @param int $i Page number.
     * @return string Link.
     */
    private function _wp_link_page( $i ) {
        global $wp_rewrite;
        $post = get_post();

        if ( 1 == $i ) {
            $url = get_permalink();
        } else {
            if ( '' == get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending')) )
                $url = add_query_arg( 'page', $i, get_permalink() );
            elseif ( 'page' == get_option('show_on_front') && get_option('page_on_front') == $post->ID )
                $url = trailingslashit(get_permalink()) . user_trailingslashit("$wp_rewrite->pagination_base/" . $i, 'single_paged');
            else
                $url = trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged');
        }

        if ( is_preview() ) {
            $url = add_query_arg( array(
                'preview' => 'true'
            ), $url );

            if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
                $url = add_query_arg( array(
                    'preview_id'    => wp_unslash( $_GET['preview_id'] ),
                    'preview_nonce' => wp_unslash( $_GET['preview_nonce'] )
                ), $url );
            }
        }

        return esc_url( $url );
    }

    /**
     * what to render at the start of the smart list (usually it's overwritten by child classes)
     */
    protected function render_before_list_wrap() {
        return '';
    }

    /**
     * what to render at the end of the list (usually it's overwritten by child classes)
     */
    protected function render_after_list_wrap() {
        return '';
    }


    /**
     * @deprecated - not used yet
     * @param $item_id_2_item_array
     * @return string
     */
    protected function render_table_of_contents_before($item_id_2_item_array) {
        return '';
    }


    /**
     * @deprecated - not used yet
     * @param $item_id_2_item_array
     * @return string
     */
    protected function render_table_of_contents_after($item_id_2_item_array) {
        return '';
    }

}


/**
 * Class td_tokenizer - the magic tokenizer
 */
class td_tokenizer {


    private $log = false; //enable or disable the log
    private $last_log_function = '';
    private $last_log_token = '';
    private $last_token_id = 0;


    var $token_title_start = 'h3';
    var $token_title_end = 'h3';
    private $token_title_is_open = false; //are we in the title tag?
    private $token_td_smart_list_end = false; //did we reach the end of the list




    private $current_list_item = array(); //here we keep the current list item

    private $buffy = array();



    function __construct() {
        $this->current_list_item = $this->get_empty_list_item();



    }




    function split_to_list_items ($content) {



        //(<figure.*<\/figure>) - html5 image + caption
        //(<p>.*<a.*<img.*<\/a>.*<\/p>) - p a img
        //(<a.*<img.*\/a>) - a img
        //(<p>.*<img.*\/>.*<\/p>) - p img
        //(<img.*\/>) - img
        //(<p>.*[.*td_smart_list_end.*].*<\/p>) - <p> [td_smartlist_end] </p>
        //([.*td_smart_list_end.*]) - [td_smartlist_end] without p




        $td_magic_regex = $this->fix_regex(
            "(<$this->token_title_start.*?>)|" .
            "(</$this->token_title_end>)|" .
            "(<figure.*</figure>)|" .
            "(<p>.*<a.*<img.*</a>.*</p>)|" .  //two step - checks for image + description
            "(<a.*<img.*</a>)|" .
            "(<p>.*<img.*/>.*</p>)|" .
            "(<img.*/>)|" .
            "(<p>.*[.*td_smart_list_end.*].*</p>)|" .
            "([.*td_smart_list_end.*])");
        //echo $td_magic_regex;

        $tokens_list = preg_split('/' . $td_magic_regex . '/', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $tokens_list = array_map('trim', $tokens_list); //trim elements
        $tokens_list = array_filter( $tokens_list, 'strlen'); //filter empty, null etc except 0 (may be a bug 0)


        //print_r($tokens_list);


        foreach($tokens_list as $token) {

            if ($this->is_title_open($token)) {
            }

            elseif($this->is_content_after_smart_list($token)) {
            }

            elseif ($this->is_content_before_smart_list($token)) {

            }

            elseif ($this->is_title_close($token)) {
            }

            elseif ($this->is_title_text($token)) {
            }

            elseif ($this->is_first_image($token)) {
            }

            elseif($this->is_smart_list_end($token)) {
            }

            elseif ($this->is_description($token)) {
            }

            else {
                //normal content?
                $this->log_step('no match', $token);
            }

            $this->log_loop_complete();
        }


        //add the remaining element (last one)
        if (!empty($this->current_list_item['title'])) {
            $this->buffy['list_items'][] = $this->current_list_item;
        }

        return $this->buffy;
    }




    private function get_empty_list_item () {
        return array(
            'title' => '',
            'first_img_id' => '',
            'description' => '',
            'read_more_link' => ''
        );
    }




    private function is_title_open($token) {
        $this->log_step(__FUNCTION__, $token);
        $matches = array();
        preg_match('/<' . $this->token_title_start . '.*?>/', $token, $matches); //match <h3 class="with_optional_class">


        if (!empty($matches)) {
            $this->token_title_is_open = true;
            return true;
        } else {
            return false;
        }


    }

    private function is_title_close($token) {
        $this->log_step(__FUNCTION__, $token);
        if ($token == '</' . $this->token_title_end . '>') {
            $this->token_title_is_open = false; //make sure we change the h3 state
            return true;
        } else {
            return false;
        }
    }

    /**
     * this function also pushes the working buffer ($this->current_list_item) to $this->buffy
     * @param $token
     * @return bool
     */
    private function is_title_text($token) {
        $this->log_step(__FUNCTION__, $token);
        if ($this->token_title_is_open === true) {


            //if the last list item is not empty, we add it to the list_items buffer
            if (!empty($this->current_list_item['title'])) {
                $this->buffy['list_items'][] = $this->current_list_item;
            }

            //empty the list - RESET

            $this->current_list_item = $this->get_empty_list_item();
            $this->current_list_item['title'] = $token; //put the new title

            $this->token_title_is_open = false; //make sure we change the h3 state - this is a fix for cases when we don't have h3

            return true;
        } else {
            return false;
        }
    }

    private function is_smart_list_end($token) {
        $this->log_step(__FUNCTION__, $token);

        $matches = array();
        preg_match('/\[.*td_smart_list_end.*\]/', $token, $matches);

        if (!empty($matches[0])) {
            $this->token_td_smart_list_end = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * returns true if the content is before the smart list
     */
    private function is_content_before_smart_list($token) {
        $this->log_step(__FUNCTION__, $token);
        if (($this->token_title_is_open === true or !empty($this->current_list_item['title']) ) and $this->token_td_smart_list_end === false) {
            return false;

        } else {
            $this->buffy['before_list'][] = $token;
            return true;

        }
    }

    /**
     * returns true if the content is after the smart list
     */
    private function is_content_after_smart_list($token) {
        $this->log_step(__FUNCTION__, $token);
        if ($this->token_td_smart_list_end === true) {
            $this->buffy['after_list'][] = $token;
            return true;

        } else {
            return false;
        }
    }


    /**
     * returns true only if it's the first image
     * @param $token
     * @return bool
     */
    private function is_first_image($token) {
        $this->log_step(__FUNCTION__, $token);
        if (!empty($this->current_list_item['first_img_id'])) { //we already have the first image for this item
            return false;
        }




        $matches = array();
        preg_match('/wp-image-([0-9]+)/', $token, $matches);

        //do we have an image?
        if (!empty($matches[1])) {

            //do we have also some description in the same paragraph with the image?
            $tmp_description = $this->extract_description_from_first_image($token);

            /*
            echo '


            -----x-----------

            ';

            echo $token;

            echo '
            ---->
            ';

            echo $tmp_description;

            echo '
            --
            ';

            */
            if ($tmp_description != '') {
                $this->current_list_item['description'] .= $tmp_description;
            }


            $this->current_list_item['first_img_id'] = $this->get_image_id_from_token($token);
            return true;
        } else {
            return false;
        }
    }

    /**
     * It takes a paragraph <p> and:
     * 1. it extracts all the links and searches each one for images. If an image is found, it is removed from the text because it's already used as a first_image
     * 2. if no links with images are found, it searches for raw images without any link. It also removes the first one.
     * @param $token
     * @return mixed
     */
    private function extract_description_from_first_image($token) {
        $matches = '';
        $buffy = '';


        //0. check if we have a figure in the token. Figures are USUALLY alone (not in paragraph)
        if (strpos($token,'<figure') !== false) {
            return '';
        }


        //1. search for all the links in this toke / block of text - if this steps retuns something, the second step doesn't run
        preg_match_all('/<a.*\/a>/U', $token, $matches); //extract all links
        if (!empty($matches[0]) and is_array($matches[0])) {
            foreach ($matches[0] as $match) {
                if (strpos($match, '<img') !== false) { //check each link if we have an image in it
                    // we need the extra str_replace because the $match is user entered in tinymce
                    $match = str_replace('(', '\(', $match);
                    $match = str_replace(')', '\)', $match);
                    $buffy = preg_replace('/' . $this->fix_regex($match) . '/', '', $token, 1); //remove the first image because that will be used as first_image
                    break;
                }
            }
        }

        //2. no match found
        if ($buffy == '') {
            //search for the FIRST img if we didn't find any links in the block of text
            $matches = '';
            preg_match('/<img.*\/>/U', $token, $matches); //extract first image
            if (!empty($matches[0])) {
                // we need the extra str_replace because the $matches[0] is user entered in tinymce
                $input_regex = str_replace('(', '\(', $matches[0]);
                $input_regex = str_replace(')', '\)', $input_regex);
                $buffy = preg_replace('/' . $this->fix_regex($input_regex) . '/', '', $token, 1); //remove the first image because that will be used as first_image
            }
        }

        $buffy = trim($buffy);

        return $buffy;
    }


    /**
     * returns true only if the current item has a title
     * @param $token
     * @return bool
     */
    private function is_description($token) {
        $this->log_step(__FUNCTION__, $token);
        if (!empty($this->current_list_item['title']) and $this->token_td_smart_list_end === false) {  //if we have a item with title and the list did not ended, it's a description if not it's random text
            $this->current_list_item['description'] .= $token;
            return true;
        } else {
            return false;
        }
    }




    private function get_image_id_from_token($token) {
        $matches = array();
        preg_match('/wp-image-([0-9]+)/', $token, $matches);
        if (!empty($matches[1])) {
            return $matches[1];
        } else {
            return '';
        }
    }



    private function log_step($function_name, $token = '') {
        if ($this->log === true) {
            $this->last_log_function = $function_name;
            $this->last_log_token = $token;

        }
    }

    private function log_loop_complete() {
        if ($this->log === true) {
            //echo "\n -- Step complete -- \n\n";
            echo $this->last_token_id . ' ' . $this->last_log_function . ' -- token: ' . $this->last_log_token . "\n";

            $this->last_log_token = '';
            $this->last_log_function = '';

            $this->last_token_id++;
        }
    }


    /**
     * fix the regex string
     * @param $input_regex
     * @return mixed
     */
    private function fix_regex($input_regex) {
        $input_regex = str_replace('/', '\/', $input_regex);
        $input_regex = str_replace(']', '\]', $input_regex);
        $input_regex = str_replace('[', '\[', $input_regex);
        return $input_regex;
    }


}