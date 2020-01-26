<?php

use App\Commands\NoActionCommand;
use App\Commands\NotFoundCommand;
use App\Commands\Text\FileList;
use App\Commands\Text\Hi;
use App\Commands\Text\Upload;


return array(
    Hi::class,
    FileList::class,
    Upload::class,

    //
    NoActionCommand::class,
    //
    NotFoundCommand::class,
);