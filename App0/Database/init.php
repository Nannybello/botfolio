<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;


$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'beautyan_botfolio',
    'username' => 'beautyan',
    'password' => 'Iw8wYlb222',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
]);

//$capsule->addConnection([
//    'driver' => 'mysql',
//    'host' => 'localhost',
//    'database' => 'botfolio',
//    'username' => 'root',
//    'password' => '',
//    'charset' => 'utf8',
//    'collation' => 'utf8_general_ci',
//    'prefix' => '',
//]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();