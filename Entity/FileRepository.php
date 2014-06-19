<?php

namespace SmartSystems\FileBundle\Entity;

use SmartSystems\FileBundle\Common\OriginalFile;
use SmartSystems\FileBundle\Common\AbstractRepository;

/**
 * Репозиторий файлов.
 *
 */
class FileRepository extends AbstractRepository
{
    /**
     * Создание экземпляра объекта.
     *
     * @param OriginalFile $originalFile Оригиналный файл
     * @param array $options Настройки
     *
     * @return File
     */
    public function createEntity(OriginalFile $originalFile, array $options = array())
    {
        return new File($originalFile);
    }
}
