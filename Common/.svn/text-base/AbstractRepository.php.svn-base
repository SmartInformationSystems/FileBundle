<?php

namespace SmartSystems\FileBundle\Common;

use Doctrine\ORM\EntityRepository;

use SmartSystems\FileBundle\Entity\File;

/**
 * Базовый репозиторий для файлов.
 *
 */
abstract class AbstractRepository extends EntityRepository
{
    /**
     * Создание экземпляра объекта.
     *
     * @param OriginalFile $originalFile Оригинальный файл
     * @param array $options Настройки
     *
     * @return File
     */
    abstract public function createEntity(OriginalFile $originalFile, array $options = array());
}
