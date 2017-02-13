<?php
namespace SmartInformationSystems\FileBundle\Repository;

use SmartInformationSystems\FileBundle\Common\OriginalFile;
use SmartInformationSystems\FileBundle\Common\AbstractRepository;
use SmartInformationSystems\FileBundle\Entity\File;

class FileRepository extends AbstractRepository
{
    /**
     * Создание экземпляра объекта
     *
     * @param OriginalFile $originalFile Оригиналный файл
     * @param array $options Настройки
     *
     * @return File
     */
    public function createEntity(OriginalFile $originalFile, array $options = [])
    {
        return new File($originalFile);
    }
}
