# Secure Antiopa 2.0 project
A place where an user can collect and organize, found images.

## Root placement
The server root needs to be placed on the antiopa.2 git folder.

## Database
The database should be created first:
Execute `sql/antiopa.2.sql`.

### db_config.php
Then the access data will be saved in the `config/db_config.php` file, which still has to be created:
```
<?php
 $config = [
    'database' => [
        'host' => 'localhost',
        'name' => 'antiopa.2',
        'user' => '',
        'password' => ''
    ],
    'web' => [
        'root' => 'Antiopa.2/',
    ]
];
?>
```

## Stored images
To save the uploaded images, first create the directory `data / tmp`.
