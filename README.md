# PHP extended

**Extra methods you might never neeed.**

Medhods are split into folders with own `index.php` so you can include every fuction in that folder. This way you can even include everyting:

```php
<?php require_once('vendor/attitude/php/src/index.php');
```

or just what you need:

```php
<?php require_once('vendor/attitude/php/src/Functions/arra/array_flatten.php');
```


## Install via Git

```bash
$ git clone
```

## Install via Composer

1. Update `"repositories"` property of `composer.json`:
    ```json
    {
        "name": "your/propect",
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/attitude/php"
            }
        ]
    }
    ```
2. Require just parts you want/need:
   ```php
   <?php require_once('vendor/attitude/php/src/Functions/array/index.php');
   ```

---

That's it ✨
