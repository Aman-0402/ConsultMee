<?php
declare(strict_types=1);

namespace ConsultMee\Services;

class UploadService
{
    public function upload(array $file, string $type = 'consultants'): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload error code: ' . $file['error']);
        }

        if ($file['size'] > MAX_UPLOAD_SIZE) {
            throw new \RuntimeException('File exceeds maximum size of 5 MB.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        if (!in_array($mime, ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException('Invalid file type. Only JPG, PNG, WEBP allowed.');
        }

        $ext  = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => throw new \RuntimeException('Unsupported MIME type.'),
        };

        $dir = $type === 'users' ? UPLOAD_PATH_USERS : UPLOAD_PATH_CONSULTANTS;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $dest     = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        return $filename;
    }

    public function delete(string $filename, string $type = 'consultants'): void
    {
        if (empty($filename) || in_array($filename, ['default.png', 'default.jpg'], true)) {
            return;
        }
        $dir  = $type === 'users' ? UPLOAD_PATH_USERS : UPLOAD_PATH_CONSULTANTS;
        $path = $dir . basename($filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
