<?php session_start(); ?>
<?php
class WizePandas {

  // User API key generated at https://www.adtoniq.com/
	private $_apiKey = '';

  // path to create the fs directory in which you wish to cache the Javascript
	// can be overriden -- this is a default
	private $_jsPath = './fs/wizepandas.js';

	// defines the expiry of javascript file
	// this default refreshes the javascript from the server once a day
	private $_jsExpiry = 86400;

  // Javascript string to inject in the <head> of the user site
	private $_javascript = '';

	// Current API version for this plugin
	private $_apiVersion = 'https://api.wizepandas.net/api/v2';

	// Plugin version number
	private $_version = 'WizePandas-PHP-1.0.0';

  // Script loading functions
	private $_loadScript = null;

	private $_saveScript = null;

	// Check for and set javascript from persistent store
	function __construct($options) {
		// overrides for private load nad save script fiunctions
		$this->_loadScript = isset($options['loadScript']) ? $options['loadScript'] : 'WizePandas::_loadWizePandasScript';
		$this->_saveScript = isset($options['saveScript']) ?  $options['saveScript'] : 'WizePandas::_saveWizePandasScript';

    // set vars for cached JS file
		if (isset($options['jsPath'])) {
			$this->_jsPath = $options['jsPath'];
		}
		if (isset($options['jsExpiry'])) {
			$this->_jsExpiry = $options['jsExpiry'];
		}
		// optionally override the API server for testing/debug
		if (isset($options['apiVersion'])) {
			$this->_apiVersion = $options['apiVersion'];
		}
		// set API key
		$this->_apiKey = $options['apiKey'];
		if (!is_callable($this->_loadScript)) {
			throw new Exception('loadScript is required and must be a PHP function');
			die();
		}
		if (!is_callable($this->_saveScript)) {
			throw new Exception('saveScript is required and must be a PHP function');
			die();
		}

		$script = call_user_func($this->_loadScript);
		if (!is_null($script)) {
			$this->_javascript = $script;
		}
	}

	private function _loadWizePandasScript() {
		if (isset($_SESSION['wizepandasJS'])) {
			return $_SESSION['wizepandasJS'];
		}
		try {
			if (file_exists($this->_jsPath)) {
				$filemtime = filemtime($this->_jsPath);
				$now = time();
				if ($now - $filemtime < $this->_jsExpiry) {
					$script = file_get_contents($this->_jsPath);
					$_SESSION['wizepandasJS'] = $script;
					return $script;
				}
			}
		} catch (Exception $e) {
			return null;
		}
		return null;
	}

	private function _saveWizePandasScript($script) {
		$_SESSION['wizepandasJS'] = $script;
		$pid = getmypid();
		$tmpfile = $this->_jsPath . $pid;
		try {
			file_put_contents($tmpfile, $script);
			// atomic on UNIX/Linux
			rename($tmpfile, $this->_jsPath);
		} catch (Exception $e) {
			// error handling
		}
	}

	// Javascript generators
	private function _getLatestJavaScript($nonce = '') {
		if ($this->_javascript === '' || $nonce !== '') {
			$data = array('nonce' => $nonce, 'version' => $this->_version);
			$url = $this->_apiVersion . '/website/update';
			$response = $this->_post_v2($url, $data);
			if ($response->success) {
				call_user_func($this->_saveScript, $response->script);
				$this->_javascript = call_user_func($this->_loadScript);
			} else {
				throw new Exception('Error initializing Wizepandas for PHP.');
				die();
			}
		}
	}

	private function _getJavascript() {
    return $this->_javascript;
	}

  // API functions
	private function _processRequest() {
		$passed_api_key = isset($_POST['adtoniqAPIKey']) ? filter_var($_POST['adtoniqAPIKey'], FILTER_SANITIZE_STRING) : '';
		$nonce = isset($_POST['adtoniqNonce']) ? filter_var($_POST['adtoniqNonce'], FILTER_SANITIZE_STRING) : '';
		$valid_nonce = $passed_api_key === $this->_apiKey && strlen($nonce) > 0;
		if ($valid_nonce) {
			$this->_getLatestJavaScript($nonce);
		}
	}

	private function _post_v2($url, $data = null) {
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n".
					"X-ApiKey: $this->_apiKey\r\n",
				'method'  => 'POST',
				'content' => json_encode($data)
			)
		);
		$context  = stream_context_create($options);
		$response = trim(file_get_contents($url, false, $context));
		$json_response = json_decode($response);
		return $json_response;
	}

  // Public functions

	/** Returns the Adtoniq Javascript library for insertion into the target site.
	 * @return String The refreshed javascript from the server
	 */
	public function getLatestJavaScript() {
		if (!isset($this->_javascript) || is_null($this->_javascript) || empty($this->_javascript)) {
			$this->_getLatestJavaScript();
		}
	}

	/** Returns the HTML that should be inserted into the head section of the website
	 * @param query_string A query string (e.g. adontiqAPIKey='api_key'&adtoniqNonce='abc')
	 * @return String The code that should be inserted into the head section
	 */
	public function getHeadCode() {
		if(isset($_POST['adtoniqNonce'])) {
			$this->_processRequest();
		}
		return $this->_getJavascript();
	}

	/** Returns the HTML that should be inserted into the body section of the website
	 * @return String The code that should be inserted into the body section
	 */
	public function getBodyCode() {
		return "<iframe id='aq-ch' src='//static-42andpark-com.s3.amazonaws.com/html/danaton3.html' width='1' height='1' style='width:1px;height:1px;position:absolute;left:-1000;' frameborder=0></iframe>";
	}

	// Public getter and setters

	/** Returns user Adtoniq API Key
	 * @return String
	 */
	public function getApiKey() {
		return $this->_apiKey;
	}

	/** Set the user Adtoniq API key
	 * @param String
	 */
	public function setApiKey($apiKey) {
		if (!is_string($apiKey)) {
			return null;
		}
		$this->_apiKey = $apiKey;
	}

	/** Returns Javascript
	 * @return String
	 */
	public function getJavascript() {
		return $this->_javascript;
	}

}
