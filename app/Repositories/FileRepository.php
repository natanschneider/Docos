<?php

declare(strict_types=1);

namespace App\Repositories;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
    public function upload(UploadedFile $file, string $folder): array
    {
        $name = $this->generateUniqueFilename($file);
        $file->storeAs($folder, $name, 's3');

        return [
            'name' => $name,
            'mimetype' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'originalName' => $file->getClientOriginalName(),
        ];
    }

    public function replace(UploadedFile $file, string $folder, string $name): array
    {
        $this->validateFile($file);
        Storage::disk('s3')->delete("$folder/$name");
        $file->storeAs($folder, $name, 's3');

        return [
            'name' => $name,
            'mimetype' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'originalName' => $file->getClientOriginalName(),
        ];
    }

    public function delete(string $folder, string $name): bool
    {
        Storage::disk('s3')->delete("$folder/$name");

        return true;
    }

    public function get(string $folder, string $name): string
    {
        return Storage::disk('s3')->get("$folder/$name");
    }

    protected function validateFile(UploadedFile $file): void
    {
        $maxFileSize = 5 * 1024; // 5MB

        if ($file->getSize() > $maxFileSize) {
            throw new Exception('File is too large');
        }
    }

    protected function generateUniqueFilename(UploadedFile $file): string
    {
        return Hash::make($file->getClientOriginalName().time()).'.'.$file->getClientOriginalExtension();
    }
}
