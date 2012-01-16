
# pnd

PHPs Not Dead!


## What is pnd?

Pnd is a minimal set of non-OO libraries for punk programmers using PHP 5.3+.

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

Libs can be included directly as well:

``` php
index.php
<?php

	require '/path/to/pnd/template/template.lib.php';

	// invoke template funcs...

?>
```

Browse to a libs folder to read its documentation.


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
Read [more about pnd.glue](https://github.com/sandeepshetty/pnd/tree/master/glue)


## Download

Download the [latest version of pnd](https://github.com/sandeepshetty/pnd/archives/master):

```shell
$ curl -L http://github.com/sandeepshetty/pnd/tarball/master | tar xvz
$ mv sandeepshetty-pnd-* pnd
```

**Oi! Now go build something.**