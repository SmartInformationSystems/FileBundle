<?php
namespace SmartInformationSystems\FileBundle\Twig;

use Doctrine\ORM\EntityManager;
use SmartInformationSystems\FileBundle\Entity\Image;
use SmartInformationSystems\FileBundle\Entity\ImagePreview;
use SmartInformationSystems\FileBundle\Repository\ImagePreviewRepository;
use SmartInformationSystems\FileBundle\Storage\AbstractStorage;
use SmartInformationSystems\FileBundle\Storage\ConfigurationContainer;
use SmartInformationSystems\FileBundle\Storage\StorageFactory;

/**
 * Расширение для изображений.
 */
class ImageExtension extends \Twig_Extension
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
        return [
            new \Twig_SimpleFilter('sis_image_preview', [$this, 'previewFilter']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sis_image_extension';
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
     * Возвращает ссылку на превью
     *
     * @param Image $image Изображение
     * @param string $name Имя превью
     *
     * @return string
     */
    public function previewFilter(Image $image, $name)
    {
        /** @var ImagePreviewRepository $rep */
        $rep = $this->em->getRepository(ImagePreview::class);

        if ($preview = $rep->getByName($image, $name)) {
            return $this->getStorage()->getUrl($preview);
        } else {
            return $this->getStorage()->getUrl($image);
        }
    }
}
