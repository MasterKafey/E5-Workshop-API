<?php

namespace App\Controller;

use App\Business\FileBusiness;
use App\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/file')]
class FileController extends AbstractController
{
    #[Route(path: '/{id}', name: 'app_file_show')]
    public function show(File $file, FileBusiness $fileBusiness): BinaryFileResponse
    {
        return $this->file($fileBusiness->getFilePath($file), $file->getOriginalName());
    }
}