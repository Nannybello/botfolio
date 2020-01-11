<?php

use App\Commands\NotFoundCommand;
use App\Commands\Text\FileList;
use App\Commands\Text\Hi;


return array(
    Hi::class,
    FileList::class,

    //
    NotFoundCommand::class,
);