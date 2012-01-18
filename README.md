
# Pnd

Pnd is a minimal set of non-OO micro-libraries for PHP 5.3+.


## Need Less?

Use only the libs you need:

``` php
index.php
<?php

	require '/path/to/pnd.php';

	// include only the libs you need
	requires ('template', 'form');

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

To know more about the libs read the documentation available in their respective folders.



## Need More?

Use [Pnd.glue](https://github.com/sandeepshetty/pnd/tree/master/glue), a micro-framework built by combining a few Pnd libs:

``` php
index.php
<?php

	require '/path/to/pnd/glue/glue.php';

	handle_get('/', function ()
	{
		return 'Hello World';
	});

	respond();

?>
```


## Getting Started

Download the latest version of Pnd:
* [as zip](https://github.com/sandeepshetty/pnd/zipball/master)
* [as tar.gz](https://github.com/sandeepshetty/pnd/tarball/master)
* On *nix you can:

```shell
$ curl -L http://github.com/sandeepshetty/pnd/tarball/master | tar xvz
$ mv sandeepshetty-pnd-* pnd
```