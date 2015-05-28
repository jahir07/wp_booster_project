<div class="wrap about-wrap td-wp-admin-header ">


    <h2 class="nav-tab-wrapper">
        <a href="admin.php?page=td_theme_welcome" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_welcome') { echo 'nav-tab-active'; }?> "><?php _e( 'Welcome' ); ?></a>
        <a href="admin.php?page=td_theme_plugins" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_plugins') { echo 'nav-tab-active'; }?>"><?php _e( 'Plugins' ); ?></a>
        <a href="admin.php?page=td_theme_demos" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_demos') { echo 'nav-tab-active'; }?>"><?php _e( 'Install demos' ); ?></a>
	    <a href="admin.php?page=td_theme_support" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_support') { echo 'nav-tab-active'; }?>"><?php _e( 'Support' ); ?></a>
        <a href="admin.php?page=td_theme_panel" class="nav-tab"><?php _e( 'Theme panel' ); ?></a>
    </h2>
</div>