<?php
/**
 * Used only on dev! - it is removed from the package by our deploy ;)
 * V2.1
 */


/**
 * Class td_less_compiler
 *
 * STOP!
 *
 * WARNING!!!   This file is also used by the deploy system of the theme and it's not included with the deployed theme
 *
 */
class td_less_compiler {


	// WARNING STOP! -  This variable is overwritten by the theme's deploy system
	static $compiler_cmd = 'includes\wp_booster\external\td_node_less\node.exe includes\wp_booster\external\td_node_less\lessjs\bin\lessc';



	/**
	 *
	 * STOP!
	 *
	 * WARNING!!!   This file and function is also used by the deploy system of the theme and it's not included with the deployed theme
	 *
	 * check if the less compiler is included in @link td_less_style.css.php by the dev theme. If it's included like that we will run the compiler for the ?=part
	 * if no ?part= is specified, this file is included in the td_deploy project and not in the main theme.
	 *
	 */
	static function init() {
		global $td_less_files;

		// from td_less_style.css.php
		if (isset($_GET['part'])) {
			if (!empty($td_less_files[$_GET['part']])) {
				td_less_compiler::compile_and_import(
					$td_less_files[$_GET['part']]['source'],
					$td_less_files[$_GET['part']]['destination']
				);
			} else {
				echo "ERROR!!!!! NO ?=part registered in td_less_style.css.php with name: " . $_GET['part'];
			}
		}
	}



	/**
	 * STOP!
	 *
	 * WARNING!!!   This file and function is also used by the deploy system of the theme and it's not included with the deployed theme
	 *
	 * Compiles and imports a less file. It is used by the theme in the dev mode ONLY. The deploy system uses the @see td_less_compiler::compile_less_file not this function.
	 * @param $source
	 * @param $destination
	 */
	private static function compile_and_import($source, $destination) {
		$response = self::compile_less_file($source, $destination);
		if ($response === true) {
			header('Content-type: text/css');
			echo "@import url('$destination');";



			// status report
			echo PHP_EOL . PHP_EOL . '/*' . PHP_EOL . PHP_EOL;

				echo 'status: Compiled OK!' .  PHP_EOL . PHP_EOL;

				// show the full url of the compiled .css
				echo 'compiled file full path (can be opened in browser):' . PHP_EOL;
				$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo dirname ($actual_link) . '/' . $destination . PHP_EOL;


				// show the less source
				echo PHP_EOL . PHP_EOL . 'less source: ' . PHP_EOL . $source . PHP_EOL;

			echo PHP_EOL . '*/';
		}
	}



	/**
	 *
	 * STOP!
	 *
	 * WARNING!!!   This file and function is also used by the deploy system of the theme and it's not included with the deployed theme
	 *
	 * Compiles a less file to css
	 *
	 * @param $less_source
	 * @param $destination
	 *
	 * @return bool
	 */
	static function compile_less_file($less_source, $destination) {
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


		$cmd = self::$compiler_cmd . ' "' . $less_source . '" "' . $destination . '" --no-color';
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
				// error
				echo '<pre>' . $stderr . '</pre>';
				die;
			} else {
				// everything worked ok
				return true;
			}


//                // Output test:
//                echo "STDOUT:<br />";
//                echo "<pre>".$stdout."</pre>";
//                echo "STDERR:<br />";
//                echo "<pre>".$stderr."</pre>";
//				echo "Exited with status: $return_status";
		} else {
			echo 'td_error: no resource';
			die;
		}
	}
}



td_less_compiler::init();


















