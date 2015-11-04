<?php
/**
 * Used only on dev! - it is removed from the package by our deploy ;)
 * V2.0
 */


/**
 * @param $source
 * @param $destination
 */

function td_compile_less_file($source, $destination) {
	if (file_exists($destination)) {
		// if the file is in used, try 10 times with 1 seconds delay
		for ( $i = 0 ; $i < 10; $i++) {
			$unlink_status = @unlink($destination);   // this returns false if the file is in use
			if ($unlink_status === true) {
				break;
			}
			sleep(1);
		}
	}
	$cmd = 'includes\wp_booster\external\td_node_less\node.exe includes\wp_booster\external\td_node_less\lessjs\bin\lessc "' . $source . '" "' . $destination . '" --no-color';
	$descriptorspec = array(
		0 => array("pipe", "r"), // STDIN
		1 => array("pipe", "w"), // STDOUT
		2 => array("pipe", "w"), // STDERR
	);
	$cwd = getcwd();
	$env = null;
	$proc = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);
	if (is_resource($proc)) {
		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		$return_status = proc_close($proc);

		if ($return_status == 1) {
			echo '<pre>' . $stderr . '</pre>';
			die;
		} else {
			header('Location: ' . $destination);
		}

//                // Output test:
//                echo "STDOUT:<br />";
//                echo "<pre>".$stdout."</pre>";
//                echo "STDERR:<br />";
//                echo "<pre>".$stderr."</pre>";

		echo "Exited with status: $return_status";
	} else {
		echo 'td_error: no resource';
	}
}





if (isset($_GET['part'])) {
	switch ($_GET['part']) {

		case 'style.css_v2':
			td_compile_less_file('includes/less_files/main.less', 'style.css');
			break;

		case 'editor-style':
			td_compile_less_file('includes/less_files/editor-style.less', 'editor-style.css');
			break;

		case 'woocommerce':
			td_compile_less_file('includes/less_files/woocommerce/main.less', 'style-woocommerce.css');
			break;

		case 'wp-admin.css':
			td_compile_less_file('includes/wp_booster/wp-admin/css/wp-admin.less', 'includes/wp_booster/wp-admin/css/wp-admin.css');
			break;

		case 'style.css_mobile':
			td_compile_less_file('mobile/includes/less_files/main.less', 'mobile/style.css');
			break;

	}
}