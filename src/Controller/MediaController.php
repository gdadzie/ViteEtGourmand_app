<?php
declare(strict_types=1);

namespace Controller;

use Repository\MediaRepository;

final class MediaController
{
    public function __construct(private MediaRepository $mediaRepository) {}

    public function show(): void
    {
        $type = (string) ($_GET['type'] ?? '');
        $id = (int) ($_GET['id'] ?? 0);

        if (!in_array($type, ['menu', 'plat'], true) || $id <= 0) {
            $this->notFound();
        }

        $media = $this->mediaRepository->find($type, $id);
        if ($media) {
            header('Content-Type: ' . $media['mime_type']);
            header('Cache-Control: public, max-age=86400');
            echo $media['content'];
            exit;
        }

        $name = basename((string) ($_GET['name'] ?? ''));
        $path = ROOT . '/public/uploads/' . $name;
        if ($name !== '' && is_file($path)) {
            $mime = mime_content_type($path) ?: 'application/octet-stream';
            header('Content-Type: ' . $mime);
            readfile($path);
            exit;
        }

        $this->notFound();
    }

    private function notFound(): never
    {
        http_response_code(404);
        exit;
    }
}
