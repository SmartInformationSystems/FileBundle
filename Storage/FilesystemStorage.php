<?php

namespace SmartInformationSystems\FileBundle\Storage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use SmartInformationSystems\FileBundle\Entity\File;

/**
 * Хранилище в файловой системе.
 *
 */
class FilesystemStorage extends AbstractStorage
{
    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        parent::init();

        if (!is_dir($this->getParam('path'))) {
            mkdir($this->getParam('path'), 0555, TRUE);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($file)
    {
        return $this->getParam('url') . $file->getFile()->getExternalToken();
    }

    /**
     * {@inheritdoc}
     */
    public function store(File $file)
    {
        if ($file->getFile()->getExternalToken()) {
            return $file->getFile()->getExternalToken();
        }

        $originalFile = $file->getOriginalFile();

        if ($originalFile === NULL) {
            throw new \Exception('Нечего сохранять в хранилище.');
        }

        $token = $file->getFile()->getToken();

        $path = substr($token, 0, 2) . '/' . substr($token, 2, 2) . '/';

        if ($originalFile instanceof UploadedFile) {
            $extension = $originalFile->getClientOriginalExtension();
        } else {
            $extension = $originalFile->getExtension();
        }


        $filename = $token . '.' . $extension;

        $originalFile->move(
            $this->getParam('path') . '/' . $path,
            $filename
        );

        return $path . $filename;
    }
}
