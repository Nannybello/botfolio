<?php


namespace App\Controllers\Web;

use App\Database\Models\FilesInfo;
use function App\Helpers\getUserFileStoragePath;

class EditFileSubmitController
{
    public function index()
    {
        $file_id = $_GET['id'];
        $user_id = $_GET['u'];

        $new_filename = $_POST['u'];
        $ori_filename = $_FILES["f"]["name"];

        $s = explode('.', $ori_filename);
        $ext = end($s);
        $filename = date('Ymd-His') . '.' . $ext;

        $target_file = getUserFileStoragePath($filename, $user_id);

        $res = move_uploaded_file($_FILES["f"]["tmp_name"], $target_file);

        $rec = FilesInfo::query()->where('id', '=', $file_id)->first();
        $rec->filename = $filename;
        $rec->filename_original = $new_filename ? $new_filename : $ori_filename;
        $rec->filetype = $ext;
        $rec->updated_at = date('Y-m-d H:i:s');
        $rec->save();

        (new EditFileController())->index();
    }
}