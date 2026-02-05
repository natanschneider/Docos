<?php

declare(strict_types=1);

namespace App\Repositories\Files;

use App\Http\Requests\FileRequest;
use App\Models\Endpoint;

final class EndpointFileRepository
{
    public function upload(FileRequest $request, Endpoint $endpoint): array
    {
        $fileRepository = new FileRepository();

        if ($endpoint->doc_file) {
            return $fileRepository->replace($request->file('doc_file'), 'docs', $endpoint->doc_file);
        }

        $upload = $fileRepository->upload($request->file('doc_file'), 'docs');

        $endpoint->update([
            'doc_file' => $upload['name'],
        ]);

        return $upload;
    }

    public function delete(Endpoint $endpoint): array
    {
        $delete = new FileRepository()->delete('docs', $endpoint->doc_file);

        $endpoint->update([
            'doc_file' => null,
        ]);

        return $delete;
    }
}
