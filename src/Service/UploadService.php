<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private $slugger;
    private $imageDirectory;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->imageDirectory = "image/upload/";
    }

    public function upload(UploadedFile $newFile, string $oldPath = ""): string
    {
        $originalFileName = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);
        $finalFileName = "$safeFileName-" . uniqid() . '.' . $newFile->guessExtension();
        $newFile->move($this->imageDirectory, $finalFileName);
        $this->delete($oldPath);
        return $finalFileName;
    }

    public function delete(string $oldPath):void
    {
        if($oldPath)
        {
            unlink($this->imageDirectory . $oldPath);
        }
    }
}