## Wizepandas for PHP
Version 0.1

#### Production Steps:

1) Place the contents of the `/src` folder into the new project, preferably a library or includes folder.

2) On any page you that you want the plugin to work, you must add the following code at the top of the PHP code and in the `<head>` section of the page:

```
<?php require_once('path/to/src/wizepandas.php'); ?>

<!DOCTYPE html>
<html>
<head>
...
<?php echo $wizepandas->getHeadCode(); ?>
</head>
</html>
```

This code injection is often placed in file that the site uses globally as in the test site provided in the `/example` folder (see `/example/header.php`) or may be need to be added to a series of individual pages. As for the head code, that only needs to go someone within the `<head>` tag, but the `require_once` code *must to appear before any code is rendered to the DOM* in other words *before* the initial `<html>` tag.

3) Within the `wizepandas.php` file, put your API key for the site in the following spot:

```
$wizepandas_config = array(
		'apiKey' => 'Your-API-Key-Here',
		...
	);
```

4) Finally, on any page where ads appear, add the following code somewhere within the `<body>` tag:

```
<?php echo $wizepandas->getBodyCode(); ?>
```

This code is part of our ad block detection tests. It should be at the top of the body to run as early as possible.

### Code Snippet Caching / Updating

The `wizepandas.php` file includes a sample implementation of the required injected code snippet persistent store. This implementation
uses a combination of sessions and simple filesystem storage. <b>This requires that you create a writable directory
named `fs` on your server.</b> Change the `JSPATH` setting to select a different location for storage of the code snippet.

Our ad-block handling code is regularly updated in response to changes to ad-block rules, so it is best practice to retrieve the
latest version once a day. The provided implementation retrieves the latest code on the first run and stores it in the
defined location on the filesystem and also stores a copy in the `$_SESSION` space.

You can provide your own `loadScript` and `saveScript` functions using database, memcache, redis or other technologies, or use the
provided implementation.

 
