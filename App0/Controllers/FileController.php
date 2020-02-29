<?php


namespace App\Controllers;

use App\Database\Models\FileInfo;
use function App\Helpers\getUserFileStoragePath;
use function App\Helpers\makeLink;
use function App\Helpers\userFileUrl;

class FileController extends BaseController
{
    public function index(): array
    {
        $files = FileInfo::query()->where('user_id', '=', $this->userId)->orderBy('created_at', 'desc')->get()->toArray();
        return $files;
    }
    public function get($id): array
    {
        $files = FileInfo::query()->where('user_id', '=', $this->userId)->where('id', '=', $id)->orderBy('created_at', 'desc')->first()->toArray();
        return $files;
    }

    public function delete($id): array
    {
        $files = FileInfo::query()->where('id', '=', $id)->delete();
        return $files;
    }

    public function saveUserFile(string $ori_filename, $binaryData, int $userId)
    {
        $s = explode('.', $ori_filename);
        $ext = end($s);
        $filename = date('Ymd-His') . '.' . $ext;

        try {

            $this->logger->debug(json_encode([
                'filename' => $filename,
                'ori_filename' => $ori_filename,
            ]));

            if (!$this->saveFile($filename, $binaryData)) {
                $this->logger->alert('CANNOT SAVE FILE');
                return false;
            }

            $rec = new FileInfo();
            $rec->filename = $filename;
            $rec->filename_original = $ori_filename;
            $rec->filetype = $ext;
            $rec->user_id = $userId;
            $rec->created_at = date('Y-m-d H:i:s');
            $rec->save();
            $this->logger->debug(json_encode($rec->toArray()));

        } catch (Exeption $e) {
            $this->logger->alert($e->getMessage());
        }

        return true;
    }

    public function saveFile(string $filename, $binaryData)
    {
        try {
            file_put_contents(getUserFileStoragePath($filename, $this->userId), $binaryData);
            return true;
        } catch (Exception $e) {
            $this->logger->alert($e->getMessage());
            return false;
        }
    }
}