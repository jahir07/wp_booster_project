<!-- smart sidebar support -->
<?php echo td_panel_generator::box_start('Smart sidebar', false); ?>
    <div class="td-box-row">
        <div class="td-box-description td-box-full">
            <p>From here you can enable and disable the smart sidebar on all the templates. The smart sidebar is an affix (sticky) sidebar that has auto resize and it scrolls with the content. The smart sidebar reverts back to a normal sidebar on iOS (iPad) and on mobile devices. The following widgets are not supported in the smart sidebar:</p>
            <ul>
                <li>[tagDiv] Block 14</li>
                <li>[tagDiv] Block 15</li>
                <li>[tagDiv] Slide</li>
            </ul>
        </div>



        <div class="td-box-row">
            <div class="td-box-description">
                <span class="td-box-title">Smart sidebar</span>
                <p>Enable / Disable the smart sidebar on all templates</p>
            </div>
            <div class="td-box-control-full">
                <?php
                echo td_panel_generator::checkbox(array(
                    'ds' => 'td_option',
                    'option_id' => 'tds_smart_sidebar',
                    'true_value' => 'enabled',
                    'false_value' => ''
                ));
                ?>
            </div>
        </div>

        <div class="td-box-row-margin-bottom"></div>
    </div>
<?php echo td_panel_generator::box_end();?>



<!-- breadcrumbs -->
<?php echo td_panel_generator::box_start('Breadcrumbs', false); ?>

<!-- Show breadcrumbs on post -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">SHOW BREADCRUMBS</span>
        <p>Enable or disable the breadcrumbs</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_option',
            'option_id' => 'tds_breadcrumbs_show',
            'true_value' => '',
            'false_value' => 'hide'
        ));
        ?>
    </div>
</div>


<div class="td-box-section-separator"></div>


<!-- Show breadcrumbs home link -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">SHOW BREADCRUMBS HOME LINK</span>
        <p>Show or hide the home link in breadcrumbs</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_option',
            'option_id' => 'tds_breadcrumbs_show_home',
            'true_value' => '',
            'false_value' => 'hide'
        ));
        ?>
    </div>
</div>



<!-- Show breadcrumbs parent category -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">SHOW PARENT CATEGORY</span>
        <p>Show or hide the parent category link ex: Home > parent category > category </p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_option',
            'option_id' => 'tds_breadcrumbs_show_parent',
            'true_value' => '',
            'false_value' => 'hide'
        ));
        ?>
    </div>
</div>


<!-- show Breadcrumbs article title -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">SHOW ARTICLE TITLE</span>
        <p>Show or hide the article title on post pages</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_option',
            'option_id' => 'tds_breadcrumbs_show_article',
            'true_value' => '',
            'false_value' => 'hide'
        ));
        ?>
    </div>
</div>

<?php echo td_panel_generator::box_end();?>




<hr>
<div class="td-section-separator">WordPress templates</div>


<!-- Theme information -->
<?php echo td_panel_generator::box_start('More information'); ?>
<!-- text -->
<div class="td-box-row">
    <div class="td-box-description td-box-full">
        <p>In this section you can configure the <a href="http://codex.wordpress.org/Template_Hierarchy" target="_blank">default wordpress templates</a>. Most of the templates support the following configurations:</p>
        <ul>
            <li>How to display posts in the default wordpress loops</li>
            <li>Sidebar position</li>
            <li>What sidebar to show</li>
        </ul>
    </div>

    <div class="td-box-row-margin-bottom"></div>
</div>
<?php echo td_panel_generator::box_end();?>


<!-- 404 template -->
    <?php echo td_panel_generator::box_start('404 template', false); ?>

    <div class="td-box-description td-box-full">
        <p>When a user requests a page or post that doesn't exists, WordPress will use this template.</p>
        <ul>
            <li>This template is located in <strong>404.php</strong> file.</li>
            <li>Shows the latest 6 posts from your site and "Ooops... Error 404, Sorry, but the page you are looking for doesn't exist." message</li>
            <li>See here a <a href="<?php echo get_home_url()?>/?p=9999999" target="_blank">sample 404 error</a> from your site</li>
            <li>Read more: <a href="http://codex.wordpress.org/Creating_an_Error_404_Page" target="_blank">WordPress 404 error</a>, <a target="_blank" href="http://en.wikipedia.org/wiki/HTTP_404">HTTP 404</a></li>
        </ul>
    </div>


    <!-- Custom Sidebar + position -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">ARTICLE DISPLAY VIEW</span>
            <p>Select a module type, this is how your article list will be displayed</p>
        </div>
        <div class="td-box-control-full td-panel-module">
            <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_option',
                'option_id' => 'tds_404_page_layout',
                'values' => td_panel_generator::helper_display_modules('enabled_on_loops')
            ));
            ?>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>





<!-- Archive page -->
    <?php echo td_panel_generator::box_start('Archive template', false); ?>


    <?php
        // prepare the archive links
        $cur_archive_year = date('Y');
        $cur_archive_month = date('n');
        $cur_archive_day = date('j');
    ?>
    <div class="td-box-description td-box-full">
        <p>This template si used by WordPress to generate the archives. By default WordPress generates daily, monthly and yearly archives</p>
        <ul>
            <li>This template is located in <strong>archive.php</strong> file.</li>
            <li>
                Shows the latest posts by day, month or year. You can link to any year or month or day, not just the current one.
                <a href="http://codex.wordpress.org/Creating_an_Archive_Index">Read more</a>
            </li>
            <li>WordPress will emit a 404 error if there are no posts published in the selected period. This is good for SEO</li>
            <li>
                Sample archives from your blog:
                <a href="<?php echo get_year_link($cur_archive_year) ?>" target="_blank">current year</a>,
                <a href="<?php echo get_month_link($cur_archive_year, $cur_archive_month) ?>" target="_blank">current month</a>,
                <a href="<?php echo  get_day_link($cur_archive_year, $cur_archive_month, $cur_archive_day) ?>" target="_blank">today</a>
            </li>
        </ul>
    </div>


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
                'option_id' => 'tds_archive_page_layout',
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
                    'option_id' => 'tds_archive_sidebar_pos',
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
                    'option_id' => 'tds_archive_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>





<!-- Attachment template -->
    <?php echo td_panel_generator::box_start('Attachment template', false); ?>

    <div class="td-box-description td-box-full">
        <p>This template is used to show an attachment (usually an image). Usually is not used by WordPress on the front end only by the default gallery.</p>
        <ul>
            <li>This template is located in <strong>attachment.php</strong> file.</li>
            <li>To view this template go to Media ⇢ Library ⇢ open an image ⇢ click View attachement page</li>
        </ul>
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
                    'option_id' => 'tds_attachment_sidebar_pos',
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
                    'option_id' => 'tds_attachment_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>




<!-- AUTHOR page -->
    <?php echo td_panel_generator::box_start('Author template', false); ?>

    <div class="td-box-description td-box-full">
        <p>The author template is shown when a user clicks on the author in the front end of the site.</p>
        <ul>
            <li>This template is located in <strong>author.php</strong> file.</li>
            <li>Under the author header, this template has a loop of the latest posts (loop.php)</li>
            <li>See a <a href="<?php echo get_author_posts_url(get_current_user_id())?>" target="_blank">demo of the author page</a> for your current logged in user.</li>
        </ul>
    </div>


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
                'option_id' => 'tds_author_page_layout',
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
                    'option_id' => 'tds_author_sidebar_pos',
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
                    'option_id' => 'tds_author_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>




<!-- Blog and posts template -->
    <?php echo td_panel_generator::box_start('Blog and posts template', false); ?>

    <div class="td-box-description td-box-full">
        <p>This setting is for two templates: </p>
        <ul>
            <li><strong>single.php</strong> - the single post template (Only the sidebar position and the default sidebar is applied here)</li>
            <li><strong>index.php</strong> - the default blog index (the page where all the posts are listed one after another) - all the settings form this box apply to this template</li>
            <li><strong>Just a tip</strong> - when you set a sidebar position or another sidebar while editing a post, that one will overwrite the one you set here.</li>
        </ul>
    </div>

    <!-- ARTICLE DISPLAY VIEW -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">ARTICLE DISPLAY VIEW</span>
            <p>Select a module type, this is how your article list will be displayed</p>
        </div>
        <div class="td-box-control-full td-panel-module">
            <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_option',
                'option_id' => 'tds_home_page_layout',
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
                    'option_id' => 'tds_home_sidebar_pos',
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
                    'option_id' => 'tds_home_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>









<!-- Page template -->
<?php echo td_panel_generator::box_start('Page template', false); ?>


    <div class="td-box-description td-box-full">
        <p>Select the page sidebar position and sidebar from here. The two settings are changeable on a per page basis.</p>
        <ul>
            <li>This template is located in <strong>page.php</strong> file.</li>
        </ul>
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
                    'option_id' => 'tds_page_sidebar_pos',
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
                    'option_id' => 'tds_page_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>



    <!-- Disable comments on pages -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">DISABLE COMMENTS ON PAGES</span>
            <p>Enable or disable the comments on pages, on the entire site. This option is disabled by default</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::checkbox(array(
                'ds' => 'td_option',
                'option_id' => 'tds_disable_comments_pages',
                'true_value' => '',
                'false_value' => 'show_comments'
            ));
            ?>
        </div>
    </div>



<?php echo td_panel_generator::box_end();?>




<!-- Search page -->
    <?php echo td_panel_generator::box_start('Search template', false); ?>

    <div class="td-box-description td-box-full">
        <p>Select the layout for the search page.</p>
        <ul>
            <li>Check a <a href="<?php echo get_search_link('and') ?>" target="_blank">sample search page</a> from your site.</li>
            <li>This template is located in <strong>search.php</strong> file.</li>
        </ul>
    </div>


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
                'option_id' => 'tds_search_page_layout',
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
                    'option_id' => 'tds_search_sidebar_pos',
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
                    'option_id' => 'tds_search_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>




<!-- TAG page -->
<?php echo td_panel_generator::box_start('Tag template', false); ?>


    <div class="td-box-description td-box-full">
        <p>Set the default layout for all the tags.</p>
        <ul>
            <li>You can view each tag page by going to Posts ⇢ Tags ⇢ hover on a tag ⇢ select view</li>
            <li>This template is located in <strong>tag.php</strong> file.</li>
        </ul>
    </div>


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
                'option_id' => 'tds_tag_page_layout',
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
                    'option_id' => 'tds_tag_sidebar_pos',
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
                    'option_id' => 'tds_tag_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>




<!-- Woocommerce template -->
<?php echo td_panel_generator::box_start('Woocommerce template', false); ?>
    <div class="td-box-description td-box-full">
        <p>Set the custom sidebar and position for the woocommerce pages.</p>
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
                    'option_id' => 'tds_woo_sidebar_pos',
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
                    'option_id' => 'tds_woo_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
</div>
<?php echo td_panel_generator::box_end();?>




<!-- bbPress template -->
<?php echo td_panel_generator::box_start('bbPress template', false); ?>

    <div class="td-box-description td-box-full">
        <p>Set the bbPress template settings from here</p>
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
                    'option_id' => 'tds_bbpress_sidebar_pos',
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
                    'option_id' => 'tds_bbpress_sidebar'
                ));
                ?>
                <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
            </div>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>