<?php

declare(strict_types=1);

namespace App\Repositories\Files;

use App\Http\Requests\FileRequest;
use App\Models\Table;

final class TableFileRepository
{
    public function upload(FileRequest $request, Table $table): array
    {
        $fileRepository = new FileRepository();

        if ($table->doc_file) {
            return $fileRepository->replace($request->file('doc_file'), 'docs', $table->doc_file);
        }

        $upload = $fileRepository->upload($request->file('doc_file'), 'docs');

        $table->update([
            'doc_file' => $upload['name'],
        ]);

        return $upload;
    }

    public function delete(Table $table): array
    {
        $delete = new FileRepository()->delete('docs', $table->doc_file);

        $table->update([
            'doc_file' => null,
        ]);

        return $delete;
    }
}
