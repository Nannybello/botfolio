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
        $ori_filename = $_FILES["fileToUpload"]["name"];

        $s = explode('.', $ori_filename);
        $ext = end($s);
        $filename = date('Ymd-His') . '.' . $ext;

        $target_file = getUserFileStoragePath($filename, $user_id);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

            $rec = FilesInfo::query()->where('id', '=', $file_id)->first();
            $rec->filename = $filename;
            $rec->filename_original = $ori_filename;
            $rec->filetype = $ext;
            $rec->updated_at = date('Y-m-d H:i:s');
            $rec->save();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}