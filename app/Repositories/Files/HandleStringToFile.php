<?php

declare(strict_types=1);

namespace App\Repositories\Files;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\FileRequest;

final class HandleStringToFile
{
    public function handle(Request $request): FileRequest
    {
        $filename = 'temp_'. uniqid() .'.md';

        $md = $request->markdown;
        $request->markdown = str_replace("\\", "", $md);

        Storage::disk('local')->put("temp/{$filename}", $request->markdown);

        $path = Storage::disk('local')->path("temp/{$filename}");
        $file = new UploadedFile(
            $path,
            $filename,
            'text/markdown',
            null,
            true
        );

        $request->files->set('doc_file', $file);
        $fileRequest = FileRequest::createFrom($request);

        return $fileRequest;
    }
}
