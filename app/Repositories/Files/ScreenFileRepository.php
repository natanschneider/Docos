<?php

declare(strict_types=1);

namespace App\Repositories\Files;

use App\Http\Requests\FileRequest;
use App\Models\Screen;

final class ScreenFileRepository
{
    public function upload(FileRequest $request, Screen $screen): array
    {
        $fileRepository = new FileRepository();

        if ($screen->doc_file) {
            return $fileRepository->replace($request->file('doc_file'), 'docs', $screen->doc_file);
        }

        $upload = $fileRepository->upload($request->file('doc_file'), 'docs');

        $screen->update([
            'doc_file' => $upload['name'],
        ]);

        return $upload;
    }

    public function delete(Screen $screen): array
    {
        $delete = (new FileRepository())->delete('docs', $screen->doc_file);

        $screen->update([
            'doc_file' => null,
        ]);

        return $delete;
    }
}
