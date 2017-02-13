<?php
namespace SmartInformationSystems\FileBundle\Storage;

use SmartInformationSystems\FileBundle\Exception\UnknownStorageException;

/**
 * Фабрика объектов хранилища
 */
class StorageFactory
{
    /**
     * Создание объекта хранилища
     *
     * @param ConfigurationContainer $config
     *
     * @return AbstractStorage
     *
     * @throws UnknownStorageException
     */
    public static function create(ConfigurationContainer $config)
    {
        switch ($config->getStorageType()) {
            case 'filesystem':
                return new FilesystemStorage($config->getStorageParams());
            default:
                throw new UnknownStorageException($config->getStorageType());
        }
    }
}
