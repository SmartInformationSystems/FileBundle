<?php

namespace SmartSystems\FileBundle\Common;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Исходный файл ждя сохранения в хранилище.
 *
 */
class OriginalFile extends File
{
    /**
     * Каталог для временных файлов.
     *
     * @var string
     */
    const TMP_DIR = '/tmp/smart_systems/fileBundle';

    /**
     * Оригинальное имя.
     *
     * @var string
     */
    private $originalName;

    /**
     * Установка оригинального имени.
     *
     * @param string $name Оригинальное имя
     *
     * @return OriginalFile
     */
    public function setOriginalName($name)
    {
        $this->originalName = $name;

        return $this;
    }

    /**
     * Возвращает оригинальное имя.
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Дублирование файла.
     *
     * @return OriginalFile
     */
    public function duplicate()
    {
        $newFilename = $this->getPath() . '/' . md5(microtime() . $this->getRealPath()) . '_' . $this->getFilename();
        copy($this->getRealPath(), $newFilename);

        $newFile = new OriginalFile($newFilename);
        $newFile->setOriginalName($this->getOriginalName());

        return $newFile;
    }

    /**
     * Создание файла из загруженного файла.
     *
     * @param UploadedFile $uploadedFile
     *
     * @return OriginalFile
     */
    public static function createFromUploadedFile(UploadedFile $uploadedFile)
    {
        if (!is_dir(self::TMP_DIR)) {
            mkdir(self::TMP_DIR, 0770, TRUE);
        }

        $filename = md5(microtime() . $uploadedFile->getClientOriginalName()) . '.' . $uploadedFile->getClientOriginalExtension();
        $originalFile = new self($uploadedFile->move(self::TMP_DIR, $filename)->getRealPath());
        $originalFile->setOriginalName($uploadedFile->getClientOriginalName());

        return $originalFile;
    }
}
