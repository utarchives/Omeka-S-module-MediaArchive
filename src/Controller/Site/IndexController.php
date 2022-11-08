<?php
namespace MediaArchive\Controller\Site;

use Zend\Mvc\Controller\AbstractActionController;
use Exception;
use ZipArchive;

class IndexController extends AbstractActionController
{
    public function downloadAction()
    {
        $basePath = OMEKA_PATH . '/files/original/';
        $archivePath = OMEKA_PATH . '/files/media-archive/';
        if (!file_exists($archivePath)) {
            mkdir($archivePath);
        }
        $response = $this->api()->read('items', $this->params('id'));
        $item = $response->getContent();
        $zipFileName = uniqid('media-zip_') . '.zip';
        $zipFilePath = $archivePath . $zipFileName;
        // create zip file
        $zip = new ZipArchive();
        if($zip->open($zipFilePath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== true){
            throw new Exception('Could not create zip file ' . $zipFileName);
            die('zip fail');
            return null;
        }
        $mediaCount = 0;
        $archivedCount = 0;
        foreach ($item->media() as $media) {
            $targetFile = $basePath . $media->storageId() . '.' . $media->extension();
            if (!file_exists($targetFile)) {
                continue;
            }
            $mediaCount++;
            $fileNameArray = explode('/', $media->source());
            $fileName = $fileNameArray[count($fileNameArray) - 1];
            $extension = substr($fileName, strlen($fileName) - strlen($media->extension()));
            if (strcmp($extension, $media->extension()) != 0) {
                $fileName = 'media_' . strval(++$archivedCount)  . '.' . $media->extension();
            }
            $result = $zip->addFile($targetFile, mb_convert_encoding($fileName, 'SJIS'));
        }
        if ($mediaCount == 0) {
            $this->redirect()->toUrl($item->siteUrl());
            return;
        } else {
            $zip->close();
            $fileName = $item->displayTitle() . '.zip';
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $fileName);
            header('Content-Length: ' . filesize($zipFilePath));
            readfile($zipFilePath);
            delete($zipFilePath);
        }
    }
}

