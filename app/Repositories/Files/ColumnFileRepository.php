<?php

declare(strict_types=1);

namespace App\Repositories\Files;

use App\Http\Requests\FileRequest;
use App\Models\Column;

final class ColumnFileRepository
{
    public function upload(FileRequest $request, Column $column): array
    {
        $fileRepository = new FileRepository();

        if ($column->doc_file) {
            return $fileRepository->replace($request->file('doc_file'), 'docs', $column->doc_file);
        }

        $upload = $fileRepository->upload($request->file('doc_file'), 'docs');

        $column->update([
            'doc_file' => $upload['name'],
        ]);

        return $upload;
    }

    public function delete(Column $column): array
    {
        $delete = (new FileRepository())->delete('docs', $column->doc_file);

        $column->update([
            'doc_file' => null,
        ]);

        return $delete;
    }
}
