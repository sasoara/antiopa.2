# Secure Antiopa 2.0 project
A place where an user can collect and organize, found images.

## Database
The database should be created first:
Execute `sql/antiopa.2.sql`.

### db_config.php
Then the access data will be saved in the `config/db_config.php` file, which still has to be created:
```
<?php
 $config = [
    'database' => [
        'host' => '127.0.0.1',
        'name' => 'antiopa.2',
        'user' => '',
        'password' => ''
    ],
    'web' => [
        'root' => '',
    ]
];
?>
```

## Stored images
To save the uploaded images, first create the directory `data / tmp`.