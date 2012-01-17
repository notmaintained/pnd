
# pnd

pnd is a minimal set of non-OO micro-libraries for PHP 5.3+.


## Need Less?

Use only the libs you need:

``` php
index.php
<?php

	require '/path/to/pnd.php';

	// include only the libs you need
	requires ('helpers', 'template', 'form');

	// do what you want to do...

?>
```


Libs can also be included directly without having to explicitly include `pnd.php` and use `requires`:


``` php
index.php
<?php

	require '/path/to/pnd/template/template.php';

	// invoke template funcs...

?>
```

To know more about the libs read the documentation available within their respective folders.



## Need More?

Use [pnd.glue](https://github.com/sandeepshetty/pnd/tree/master/glue), a micro-framework built by combining a few pnd libs:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get('/', function ()
	{
		return 'Hello World';
	});

	yield_to_glue();

?>
```


## Getting Started

Download the [latest version of pnd](https://github.com/sandeepshetty/pnd/archives/master):

```shell
$ curl -L http://github.com/sandeepshetty/pnd/tarball/master | tar xvz
$ mv sandeepshetty-pnd-* pnd
```