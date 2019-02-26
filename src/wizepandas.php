<?php include('Wizepandas.class.php'); ?>
<?php
	// Wizepandas config
	$wizepandas_config = array(
		'apiKey' => ''
	);

	// Create new Wizepandas class object
	$wizepandas = new Wizepandas($wizepandas_config);

	// This function fetches the required Javascript
	$wizepandas->getLatestJavaScript();
?>
