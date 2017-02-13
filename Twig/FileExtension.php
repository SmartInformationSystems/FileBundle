<?php
namespace SmartInformationSystems\FileBundle\Twig;

use Doctrine\ORM\EntityManager;
use SmartInformationSystems\FileBundle\Entity\File;
use SmartInformationSystems\FileBundle\Storage\AbstractStorage;
use SmartInformationSystems\FileBundle\Storage\ConfigurationContainer;
use SmartInformationSystems\FileBundle\Storage\StorageFactory;

/**
 * Расширение для файлов
 */
class FileExtension extends \Twig_Extension
{
    /**
     * Подключение к БД
     *
     * @var EntityManager
     */
    private $em;

    /**
     * Хранилище файлов
     *
     * @var AbstractStorage
     */
    private $storage;

    /**
     * Хранилище файлов
     *
     * @var $storageConfiguration
     */
    private $storageConfiguration;

    public function __construct(EntityManager $em, ConfigurationContainer $storageConfiguration)
    {
        $this->em = $em;
        $this->storageConfiguration = $storageConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sis_get_url', [$this, 'fileGetUrlFilter']),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sis_file_extension';
    }

    /**
     * Возвращает хранилище файлов
     *
     * @return AbstractStorage
     */
    private function getStorage()
    {
        if ($this->storage === null) {
            $this->storage = StorageFactory::create($this->storageConfiguration);
        }

        return $this->storage;
    }

    /**
     * Возвращает ссылку на файл
     *
     * @param File $file Файл
     *
     * @return string
     */
    public function fileGetUrlFilter($file)
    {
        return $this->getStorage()->getUrl($file);
    }
}
