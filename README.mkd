# PHPs Not Dead.

A minimal set of non-OO libraries for punk programmers using PHP 5.3+.

Make your time poseur frameworks, `index.php` are belong to us!

## Need Less?
``` php
index.php
<?php

	require '/path/to/pnd.php';

	// include only the libs you need
	requires ('helpers', 'template', 'form');

	// do what you want to do...

?>
```


## Need More?

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.lib.php';

	handle_get('/', function ()
	{
		return 'Hello World';
	});

	yield_to_glue();

?>
```

**Oi! Now go build something...**