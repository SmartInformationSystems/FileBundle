<?php

namespace SmartInformationSystems\FileBundle\Entity;

use SmartInformationSystems\FileBundle\Common\OriginalFile;
use SmartInformationSystems\FileBundle\Common\AbstractRepository;

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
