<?php
/**
 * td_style_customizer.js is added in td_global.php -> $js_files array @see td_global.php
*/

//the bottom code for analitics and stuff
function td_theme_style_footer() {
    ?>
    <div id="td-theme-settings" class="td-theme-settings-small">
        <div class="td-skin-header">DEMO STACKS</div>
        <div class="td-skin-content">
            <div class="td-set-theme-style"><a href="http://demo.tagdiv.com/newsmag" class="td-set-theme-style-link">DEFAULT</a></div>
            <div class="td-set-theme-style"><a href="http://demo.tagdiv.com/newsmag_fashion" class="td-set-theme-style-link">FASHION</a></div>
            <div class="td-set-theme-style"><a href="http://demo.tagdiv.com/newsmag_tech" class="td-set-theme-style-link" data-value="">TECH</a></div>
            <div class="td-set-theme-style"><a href="http://demo.tagdiv.com/newsmag_video" class="td-set-theme-style-link">VIDEO</a></div>
            <div class="td-set-theme-style"><a href="http://demo.tagdiv.com/newsmag_sport" class="td-set-theme-style-link">SPORT</a></div>
            <div class="td-set-theme-style"><a href="http://demo.tagdiv.com/newsmag_classic_blog" class="td-set-theme-style-link">CLASSIC BLOG</a></div>
        </div>
        <div class="clearfix"></div>
        <div class="td-set-hide-show"><a href="#" id="td-theme-set-hide">HIDE</a></div>
    </div>
<?php
}

add_action('wp_footer', 'td_theme_style_footer');

