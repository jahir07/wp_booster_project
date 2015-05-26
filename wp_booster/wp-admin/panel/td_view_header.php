<div class="td-admin-container wrap about-wrap">
    <h1>Welcome to <?php echo TD_THEME_NAME?></h1>

    <div class="about-text"><?php echo TD_THEME_NAME?> is now installed and ready to use! Get ready to build something beautiful. Please register your purchase to get support. We hope you enjoy it!</div>

    <div class="wp-badge">Version: <?php echo TD_THEME_VERSION?></div>



    <h2 class="nav-tab-wrapper">
        <a href="admin.php?page=td_theme_welcome" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_welcome') { echo 'nav-tab-active'; }?> "><?php _e( 'Welcome' ); ?></a>
        <a href="admin.php?page=td_theme_plugins" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_plugins') { echo 'nav-tab-active'; }?>"><?php _e( 'Plugins' ); ?></a>
        <a href="admin.php?page=td_theme_demos" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_demos') { echo 'nav-tab-active'; }?>"><?php _e( 'Install demos' ); ?></a>
	    <a href="admin.php?page=td_theme_support" class="nav-tab <?php if(isset($_GET['page']) and $_GET['page'] == 'td_theme_support') { echo 'nav-tab-active'; }?>"><?php _e( 'Support' ); ?></a>
        <a href="admin.php?page=td_theme_panel" class="nav-tab"><?php _e( 'Theme panel' ); ?></a>

    </h2>
</div>