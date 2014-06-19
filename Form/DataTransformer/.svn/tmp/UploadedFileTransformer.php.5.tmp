<?php

namespace SmartSystems\FileBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

use SmartSystems\FileBundle\Common\OriginalFile;
use SmartSystems\FileBundle\Common\AbstractRepository;
use SmartSystems\FileBundle\Storage\AbstractStorage;
use SmartSystems\FileBundle\Entity\ImageRepository;


/**
 * Обработка загруженных файлов.
 *
 */
class UploadedFileTransformer implements DataTransformerInterface
{
    /**
     * Подключение к БД.
     *
     * @var ObjectManager
     */
    private $om;

    /**
     * Хранилище.
     *
     * @var AbstractStorage
     */
    private $storage;

    /**
     * Repository.
     *
     * @var string
     */
    private $repository;

    /**
     * Класс, в котором сохраняется ихображение.
     *
     * @var string
     */
    private $entityClass;

    /**
     * Имя поля с изображением.
     *
     * @var string
     */
    private $propertyName;

    /**
     * Конструктор.
     *
     * @param ObjectManager $om Подключение к БД
     * @param AbstractStorage $storage Хранилище
     * @param string $repository Repository
     * @param string $entityClass Класс, в котором сохраняется ихображение
     * @param string $propertyName Имя поля с изображением
     */
    public function __construct(ObjectManager $om, AbstractStorage $storage, $repository, $entityClass, $propertyName)
    {
        $this->om = $om;
        $this->storage = $storage;
        $this->repository = $repository;
        $this->entityClass = $entityClass;
        $this->propertyName = $propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($value === NULL) {
            return NULL;
        }

        /** @var AbstractRepository $rep */
        $rep = $this->om->getRepository($this->repository);

        if (is_string($value)) {
            $file = $rep->find($value);

            if ($file) {
                return $file;
            }
        }

        if (!($value instanceof UploadedFile)) {

            throw new TransformationFailedException('Возможно обратное преобразование только UploadedFile.');
        }

        $originalFile = OriginalFile::createFromUploadedFile($value);
        $options = ImageRepository::getCreateOptions($originalFile, $this->entityClass, $this->propertyName);

        return $rep->createEntity($originalFile, $options);
    }
}
