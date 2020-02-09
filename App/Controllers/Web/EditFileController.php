<?php


namespace App\Controllers\Web;

use App\Controllers\FileController;
use function App\Helpers\makeLink;
use function App\Helpers\userFileUrl;
use function App\Helpers\userThumbnailUrl;

class EditFileController
{
    public function index()
    {
        $file_id = $_GET['id'];
        $user_id = $_GET['u'];
        $controller = new FileController($user_id);
        $file = $controller->get($file_id);
        include ROOT_PATH . '/views/edit_file.php';
    }
}