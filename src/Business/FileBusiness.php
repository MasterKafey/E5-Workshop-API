<?php

namespace App\Business;

use App\Entity\File;
use App\Entity\FileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileBusiness
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string                 $fileUploadDirectory
    )
    {

    }

    public function uploadFile(UploadedFile $uploadedFile): File
    {
        $file = new File();


        $file
            ->setPath($this->generateUniqueName())
            ->setOriginalName($uploadedFile->getClientOriginalName())
            ->setType($this->getFileType($uploadedFile->getMimeType()));

        $this->entityManager->persist($file);
        $this->entityManager->flush();

        $uploadedFile->move($this->fileUploadDirectory, $file->getPath());

        return $file;
    }

    public function generateUniqueName(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * @throws \Exception
     */
    public function getFileType(string $mimeType): FileType
    {
        if (str_starts_with($mimeType, 'image/')) {
            return FileType::IMAGE;
        } else if (str_starts_with($mimeType, 'video/')) {
            return FileType::VIDEO;
        } else {
            throw new \Exception("$mimeType is not handel");
        }
    }

    public function getFilePath(File $file): string
    {
        return $this->fileUploadDirectory . '/' . $file->getPath();
    }
}