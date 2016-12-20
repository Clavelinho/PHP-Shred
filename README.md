# Shred PHP

Safely deletes files v0.5b

## Requeriments
PHP 5.3

## Installation

composer.json

`{
	"require": {
		"clavelinho/shred": "dev-master"
	}
}`

Run `composer install`

## Usage
```php
// load autoload composer
require 'vendor/autoload.php';

use Shred\Shred;

$shred = new Shred\Shred($n); // $n <= Number of iterations. Default 3.

$shred->remove('folder/file.txt'); // <= Overwrite and Remove.
$shred->remove('folder/file.txt', false); // <= Only overwrite.

// Check if remove
if ($shred->remove('folder/file.txt')) {
	// The file is truncated & removed.
} else {
	// Impossible to overwrite or remove the file. See filepath & file permissions.
}
```

Shred overwrite 'n' times the file for make more difficult to recovery (Imposible is nothing!). Obviously inspired in shred for linux.
If you want to delete large files, or repeat a large number of times the interaction should increase the execution time of the script.

```php
ini_get('max_execution_time'); // Max execution script time in seconds.
set_time_limit($s); // $s => Set max time limit in seconds.
```

## Credits
Shred PHP was created by Dani C. Released under the MIT license.