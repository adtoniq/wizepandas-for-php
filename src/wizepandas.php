<?php session_start(); // if not already using sessions, be sure to start sessions here ?>
<?php include('Wizepandas.class.php'); ?>
<?php
	// example save/load service using Sessions and file storage
	// provide functions you wish, using database, memcache, redis etc.

	// need to create the fs directory (or modify to the location you wish to cache the Javascript
	define('JSPATH', __DIR__ . '/../fs/wizepandas.js');

	// defines the expiry - this implementation refreshes the javascript from the server once a day
	define('JS_EXPIRY', 86400);

	function loadWizePandasScript() {
		if (isset($_SESSION['wizepandasJS'])) {
			return $_SESSION['wizepandasJS'];
		}
		try {
			if (file_exists(JSPATH)) {
				$filemtime = filemtime(JSPATH);
				$now = time();
				if ($now - $filemtime < JS_EXPIRY) {
					$script = file_get_contents(JSPATH);
					$_SESSION['wizepandasJS'] = $script;
					return $script;
				}
			}
		} catch (Exception $e) {
			return null;
		}
		return null;
	}

	function saveWizePandasScript($script) {
		$_SESSION['wizepandasJS'] = $script;
		$pid = getmypid();
		$tmpfile = JSPATH . $pid;
		try {
			file_put_contents($tmpfile, $script);
			// atomic on UNIX/Linux
			rename($tmpfile, JSPATH);
		} catch (Exception $e) {
			// error handling
		}
	}

	// Wizepandas config
	$wizepandas_config = array(
		'apiKey' => '',
		'loadScript' => 'loadWizePandasScript',
		'saveScript' => 'saveWizePandasScript'
	);

	// Create new Wizepandas class object
	$wizepandas = new Wizepandas($wizepandas_config);


	// This function fetches the required Javascript
	$wizepandas->getLatestJavaScript();
?>
