
# Pnd.glue

Pnd.glue is a PHP 5.3+ micro-framework built by combining a few [Pnd libs](https://github.com/sandeepshetty/pnd).


## Getting Started

If you're serving your app from _Apache_ and want _clean URLs_, move the provided `.htaccess` file from `/path/to/pnd/glue/` to your apps root directory (where your `index.php` is located).


## Request Handlers

Handlers lets you respond to HTTP requests by maping callback functions to them.


``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';


	handle_head('/', function ()
	{
		return 'I'm the HEAD request handler';
	});


	handle_get('/', function ()
	{
		return 'I'm the GET request handler';
	});


	handle_query('/', function ()
	{
		return 'I'm the GET request handler that only reponds if the query string is present';
	});


	handle_post('/', function ()
	{
		return 'I'm the POST request handler';
	});


	handle_post_action('/', 'save_me' function ()
	{
		return "I'm the POST request handler that only responds if the posting form contained a variable called 'action' (usually the name of the submit input type field) with the value 'save_me' (with or without the underscore and case-insensitive, so 'Save Me', 'save me' and 'SaVe_Me` all work). I'm handy when you have to submit on the same form.";
	});

	handle_put('/', function ()
	{
		return 'I'm the PUT request handler';
	});


	handle_delete('/', function ()
	{
		return 'I'm the DELETE request handler';
	});

	respond();

?>
```

Notice how we `return` instead of `echo`. There will be more on this later.