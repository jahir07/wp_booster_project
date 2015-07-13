<?php
/**
 * Created by ra on 7/9/2015.
 */


$custom_post_type = td_util::get_http_post_val('custom_post_type');



// get the registered taxonomies for this specific post type and prepare them for use in the panels dropdowns
// add empty
$td_registered_taxonomies[] = array(
    'val' => '',
    'text' => '-- None --'
);

// read the taxonomies and build the array
$registered_taxonomies_obj = get_object_taxonomies($custom_post_type, 'objects');
foreach ($registered_taxonomies_obj as $registered_taxonomy_obj) {
    $td_registered_taxonomies[] = array(
        'val' => $registered_taxonomy_obj->name,
        'text' => $registered_taxonomy_obj->labels->name . '  ' . '(' . $registered_taxonomy_obj->name . ')'
    );
}



?>

<!-- breadcrumbs: enable disable -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">Enable breadcrumbs</span>
        <p>Enable or disable breadcrumbs</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_cpt',
            'item_id' => $custom_post_type,
            'option_id' => 'tds_breadcrumbs_show',
            'true_value' => '',
            'false_value' => 'hide'
        ));
        ?>
    </div>
</div>





<!-- breadcrumbs: select taxonomy -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">Breadcrumbs taxonomy</span>
        <p>What taxonomy should show up in the breadcrumbs</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::dropdown(array(
            'ds' => 'td_cpt',
            'item_id' => $custom_post_type,
            'option_id' => 'tds_breadcrumbs_taxonomy',
            'values' => $td_registered_taxonomies
        ));
        ?>
    </div>
</div>

<!-- breadcrumbs: parent category -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">SHOW PARENT TAXONOMY</span>
        <p>Show or hide the parent category link ex: Home > parent category > category </p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_cpt',
            'item_id' => $custom_post_type,
            'option_id' => 'tds_breadcrumbs_show_parent',
            'true_value' => '',
            'false_value' => 'hide'
        ));
        ?>
    </div>
</div>


<!-- breadcrumbs: article title -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">SHOW ARTICLE TITLE</span>
        <p>Show or hide the article title on post pages</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_cpt',
            'item_id' => $custom_post_type,
            'option_id' => 'tds_breadcrumbs_show_article',
            'true_value' => '',
            'false_value' => 'hide'
        ));
        ?>
    </div>
</div>


<!-- category spot: select taxonomy -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">Category spot taxonomy</span>
        <p>What taxonomy should show up in the breadcrumbs</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::dropdown(array(
            'ds' => 'td_cpt',
            'item_id' => $custom_post_type,
            'option_id' => 'tds_category_spot_taxonomy',
            'values' => $td_registered_taxonomies
        ));
        ?>
    </div>
</div>


<!-- tag spot: select taxonomy -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">tag spot taxonomy</span>
        <p>What taxonomy should show up in the breadcrumbs</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::dropdown(array(
            'ds' => 'td_cpt',
            'item_id' => $custom_post_type,
            'option_id' => 'tds_tag_spot_taxonomy',
            'values' => $td_registered_taxonomies
        ));
        ?>
    </div>
</div>

<!-- tag spot: text -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">tag spot text</span>
        <p>If you are using custom taxonomies, you can replace the default TAG label</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::input(array(
            'ds' => 'td_cpt',
            'item_id' => $custom_post_type,
            'option_id' => 'tds_tag_spot_text',
            'placeholder' => __td('TAGS')
        ));
        ?>
    </div>
</div>

