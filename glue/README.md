
# Pnd.glue

Pnd.glue is a PHP 5.3+ micro-framework built by combining a few [Pnd libs](https://github.com/sandeepshetty/pnd).


## Getting Started

If you're serving your app from __Apache__ and want __clean URLs__, move the provided `.htaccess` file from `/path/to/pnd/glue/` to your apps root directory (same location as your apps `index.php`).


## Example Application
All Pnd.glue apps need to do 3 things:

``` php
index.php
<?php

	// 1. Require Pnd.glue
	require '/path/to/pnd/glue/glue.php';

	// 2. Define one or more request handlers
	handle_get('/', function ()
	{
		return 'Hello World';
	});

	// 3. Let Pnd.glue respond by finding the request handlers that match the current request.
	respond();

?>
```



## Request Handlers

Handlers lets you respond to HTTP requests by maping callback functions to them.


``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';


	handle_head('/', function ()
	{
		return "I'm the HEAD request handler";
	});


	handle_get('/', function ()
	{
		return "I'm the GET request handler";
	});


	handle_query('/', function ()
	{
		return "I'm the GET request handler that only reponds if the query string is present";
	});


	handle_post('/', function ()
	{
		return "I'm the POST request handler";
	});


	handle_post_action('/', 'save_me' function ()
	{
		return "I'm the POST request handler that only responds if the posting form contained a variable called 'action' (usually the name of the submit input type field) with the value 'save_me' (with or without the underscore and case-insensitive, so 'Save Me', 'save me' and 'SaVe_Me` all work). I'm handy when you have two submit buttons on the same form.";
	});

	handle_put('/', function ()
	{
		return "I'm the PUT request handler";
	});


	handle_delete('/', function ()
	{
		return "I'm the DELETE request handler";
	});


	respond();

?>
```
Request handlers are matched in the order they are declared. If non of the request handlers match the current HTTP request, Pnd.glue will return a 404 Not Found response. Notice how we `return` instead of `echo`. More on this later.

### Matching multiple paths

You can also map multiple paths by passing an array of paths:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get(array('/', '/home'), function ()
	{
		return 'Hello World';
	});

	respond();

?>
```


## Request

All request handler callback functions are passed, as their first paramter, an array containing request details:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get(array('/', '/home'), function ($req)
	{
		// $req contains request details
	});

	respond();

?>
```

`$req` above contains the following keys:

* method
* path
* query
* form
* server_vars
* headers
* body

## Path patterns and matches
Request handlers can be mapped to path patterns instead of fixed paths. Path patterns provide a more readable abstraction over regular expressions and allow you to fallback to regular expressions when needed.

Using path patterns you can match named parts (enclosed in `{` and `}`) and access them from the last parameter passed to the callback function:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get('/hello/{name}', function ($req, $matches)
	{
		return 'Hello '.$matches['name'];
	});

	respond();

?>
```

Matches can be made optional by enclosing them in `[` and `]`:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get('/hello/[{name}]', function ($req, $matches)
	{
		return isset($matches['name']) ? 'Hello '.$matches['name'] : 'Hello World';
	});

	respond();

?>
```
You can specify patterns for named parts to tell them how much to match. A `segment` is the default pattern used when you don't specify one so `{name}` is the same as `{name:segment}` which is the same as `{name:[^/]+}`. Since path patterns are complied into regular expressions, you can use regexs anywhere in a path pattern.

The file `/path/to/pnd/path/filters.config.php` of Pnd.path defines other aliases for named part patterns: `{foo:word}`, `{foo:alpha}`, `{foo:digits}`, `{foo:number}`, `{foo:any}`


## Bidirectional Pipeline

You can `pipe` request handlers together:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get('/', function ()
	{
		return strtoupper(next_handler());
	},
	function ($req, $matches)
	{
		return 'Hello World';
	});

	respond();

?>
```

This is the same as:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get('/', function ()
	{
		return strtoupper(next_handler());
	});

	handle_get('/', function ()
	{
		return 'Hello World';
	});

	respond();

?>
```


By `return`ing the response instead of `echo`ing it, you create bidirectional pipelines by giving back control to the upstream handler.

TODO: info about next_handler() and passing variables downstream

## Reponse
TODO

## Named Paths
TODO

## Templates
TODO
### Layout
TODO

## Error Handling
TODO

## Organizing Request Handlers
TODO: info about handler_macro()