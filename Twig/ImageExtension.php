<?php

namespace SmartSystems\FileBundle\Twig;

use Doctrine\ORM\EntityManager;

use SmartSystems\FileBundle\Entity\Image;
use SmartSystems\FileBundle\Entity\ImagePreviewRepository;
use SmartSystems\FileBundle\Storage\AbstractStorage;
use SmartSystems\FileBundle\Storage\ConfigurationContainer;
use SmartSystems\FileBundle\Storage\StorageFactory;

/**
 * Расширение для изображений.
 *
 */
class ImageExtension extends \Twig_Extension
{
    /**
     * Подключение к БД.
     *
     * @var EntityManager
     */
    private $em;
    /**
     * Хранилище файлов.
     *
     * @var AbstractStorage
     */
    private $storage;
    /**
     * Хранилище файлов.
     *
     * @var $storageConfiguration
     */
    private $storageConfiguration;

    /**
     * Конструктор.
     *
     * @param EntityManager $em Подключение к БД
     * @param ConfigurationContainer $storageConfiguration Настройки
     */
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
            new \Twig_SimpleFilter('preview', array($this, 'previewFilter')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'image_extension';
    }

    /**
     * Возвращает хранилище файлов.
     *
     * @return AbstractStorage
     */
    private function getStorage()
    {
        if ($this->storage === NULL) {
            $this->storage = StorageFactory::create($this->storageConfiguration);
        }

        return $this->storage;
    }

    /**
     * Возвращает ссылку на превью.
     *
     * @param Image $image Изображение
     * @param string $name Имя превью
     *
     * @return string
     */
    public function previewFilter(Image $image, $name)
    {
        /** @var ImagePreviewRepository $rep */
        $rep = $this->em->getRepository('SmartSystemsFileBundle:ImagePreview');
        $preview = $rep->getByName($image, $name);

        if ($preview = $rep->getByName($image, $name)) {
            return $this->getStorage()->getUrl($preview);
        } else {
            return $this->getStorage()->getUrl($image);
        }
    }
}
