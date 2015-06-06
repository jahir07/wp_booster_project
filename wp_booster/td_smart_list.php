<?php
abstract class td_smart_list {


    abstract function render_list_item($item_array, $current_item_id, $current_item_number, $total_items_number); //child classes must implement this :)



    var $counting_order_asc = false; //how to count the items in the list
    var $counting_start = 1; //start from 1 or 0 ?



    function render_from_post_content($content) {
        // make a new tokenizer
        $td_tokenizer = new td_tokenizer($content);


        //read the smart list settings
        global $post;
        $td_smart_list = get_post_meta($post->ID, 'td_post_theme_settings', true);




        // check the smart list numbering preferences
        // the default value is already set above - if the $td_smart_list['td_smart_list_order'] is empty
        if (!empty($td_smart_list['td_smart_list_order'])) {
            $this->counting_order_asc = true;
            $this->counting_start = 1;
        }




        // are we using custom h tags ?
        if (!empty($td_smart_list['td_smart_list_h'])) {
            $td_tokenizer->token_title_end = $td_smart_list['td_smart_list_h'];
            $td_tokenizer->token_title_start = $td_smart_list['td_smart_list_h'];
        } else {
            // default
            $td_tokenizer->token_title_end = 'h3';
            $td_tokenizer->token_title_start = 'h3';
        }



        $list_items = $td_tokenizer->split_to_list_items($content);


        if (empty($list_items['list_items'])) {
            return $content;
        }
        //print_r($list_items);

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

        //count the total number of items
        $total_items_number = count($list_items['list_items']) - 1 + $this->counting_start; // fix for 0 base counting (0 of 3 - to -  3 of 3)

        //render each item using the render_list_item method from the child class
        foreach ($list_items['list_items'] as $list_item_key => $list_item) {

            //how to count (asc or desc)
            if ($this->counting_order_asc === true) {
                $current_item_index = $list_item_key + $this->counting_start;
            } else {
                $current_item_index = $total_items_number - ($list_item_key);
            }

            $buffy .= $this->render_list_item($list_item, $list_item_key + 1, $current_item_index, $total_items_number);
        }


        $buffy .= $this->render_after_list_wrap(); //from child class - render the list wrap end


        /*  ----------------------------------------------------------------------------
            add the table of contents after
         */
        $buffy .= $this->render_table_of_contents_after($item_id_2_item_array);


        /*  ----------------------------------------------------------------------------
            add the after_list content
         */
        if (!empty($list_items['after_list'])) {
            $buffy .= implode('', $list_items['after_list']);
        }

        return $buffy;
    }


    function render_from_gallery() {

    }


    /**
     * @param $atts - the shortcode atts
     * @return string - this will return the rendered smart list
     */
    function render_from_short_code($atts) {
        return '';
    }





    /**
     * what to render at the start of the smart list (usually it's overwritten by child classes)
     */
    function render_before_list_wrap() {
        return '';
    }

    /**
     * what to render at the end of the list (usually it's overwritten by child classes)
     */
    function render_after_list_wrap() {
        return '';
    }



    function render_table_of_contents_before($item_id_2_item_array) {
        return '';
    }


    function render_table_of_contents_after($item_id_2_item_array) {
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



    function __construct($content) {
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