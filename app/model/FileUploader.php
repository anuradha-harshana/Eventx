<?php
class FileUploader {
    private string $uploadDir;
    private array $allowedTypes;
    private int $maxSize;

    public function __construct(
        string $uploadDir,
        array $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'],
        int $maxSize = 2097152 // 2MB
    ) {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;

        $this->ensureDirectoryExists();
    }

    public function upload(array $file, string $prefix = 'file_'): string {

        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error'] ?? 0));
        }

        // Validate MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $this->allowedTypes)) {
            throw new Exception("Invalid file type");
        }

        // Validate size
        if ($file['size'] > $this->maxSize) {
            throw new Exception("File too large");
        }

        $extension = $this->getExtensionFromMime($mime);

        $filename = $prefix . uniqid() . '_' . date('Ymd_His') . '.' . $extension;

        $fullPath = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new Exception("Upload failed");
        }

        // Convert to relative path dynamically
        $projectRoot = realpath($_SERVER['DOCUMENT_ROOT'] . '/Eventx');

        $absoluteUploadDir = realpath($this->uploadDir);

        $relativeDir = str_replace($projectRoot, '', $absoluteUploadDir);

        $relativeDir = str_replace('\\', '/', $relativeDir);

        return rtrim($relativeDir, '/') . '/' . $filename;
    }

    private function getExtensionFromMime(string $mime): string {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg'
        ];
        return $map[$mime] ?? 'bin';
    }

    private function ensureDirectoryExists(): void {
        if (!file_exists($this->uploadDir) && !mkdir($this->uploadDir, 0755, true)) {
            throw new Exception("Upload directory '{$this->uploadDir}' could not be created");
        }
        if (!is_writable($this->uploadDir)) {
            throw new Exception("Upload directory '{$this->uploadDir}' is not writable");
        }
    }

    private function getUploadErrorMessage(int $errorCode): string {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds server upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by PHP extension'
        ];
        return $errors[$errorCode] ?? 'Unknown upload error';
    }
}
