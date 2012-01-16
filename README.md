
# PND

__pnd__ is a minimal set of non-OO libraries for PHP 5.3+.


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


Libs can also be included directly without having to explicitly include `pnd.php` or use `requires`:


``` php
index.php
<?php

	require '/path/to/pnd/template/template.php';

	// invoke template funcs...

?>
```
Read more about the libs from the documentation available within their respective folders.


## Need More?

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
Read more about [pnd.glue](https://github.com/sandeepshetty/pnd/tree/master/glue).


## Download

Download the [latest version of pnd](https://github.com/sandeepshetty/pnd/archives/master):

```shell
$ curl -L http://github.com/sandeepshetty/pnd/tarball/master | tar xvz
$ mv sandeepshetty-pnd-* pnd
```