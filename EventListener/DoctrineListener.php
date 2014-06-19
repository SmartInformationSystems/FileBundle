<?php

namespace SmartInformationSystems\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

use SmartSystems\FileBundle\Entity\File;
use SmartSystems\FileBundle\Storage\AbstractStorage;
use SmartSystems\FileBundle\Storage\ConfigurationContainer;
use SmartSystems\FileBundle\Storage\StorageFactory;

/**
 * Обработчик событий Doctrine.
 *
 */
class DoctrineListener
{
    /**
     * Хранилище файлов.
     *
     * @var AbstractStorage
     */
    private $storage;

    /**
     * Конструктор.
     *
     * @param ConfigurationContainer $configuration Настройки
     */
    public function __construct(ConfigurationContainer $configuration)
    {
        $this->storage = StorageFactory::create($configuration);
    }

    /**
     * Обработчик события "prePersist".
     *
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof File) {
            $entity->setExternalToken(
                $this->storage->store($entity)
            );
        }
    }
}
