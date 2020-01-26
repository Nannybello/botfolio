<?php

use App\Commands\NoActionCommand;
use App\Commands\NotFoundCommand;
use App\Commands\Text\FileList;
use App\Commands\Text\Hi;


return array(
    Hi::class,
    FileList::class,

    //
    NoActionCommand::class,
    //
    NotFoundCommand::class,
);