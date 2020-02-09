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

        $url = userFileUrl($file['filename'], $user_id);
        if (in_array($file['filetype'], ['jpg', 'png'])) {
            $thumb_url = $url;
        } else {
            $thumb_url = null;
        }

        include ROOT_PATH . '/views/edit_file.php';
    }
}