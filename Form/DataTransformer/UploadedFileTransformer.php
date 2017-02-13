<?php
namespace SmartInformationSystems\FileBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use SmartInformationSystems\FileBundle\Common\OriginalFile;
use SmartInformationSystems\FileBundle\Common\AbstractRepository;
use SmartInformationSystems\FileBundle\Storage\AbstractStorage;
use SmartInformationSystems\FileBundle\Repository\ImageRepository;

class UploadedFileTransformer implements DataTransformerInterface
{
    /**
     * Подключение к БД
     *
     * @var ObjectManager
     */
    private $om;

    /**
     * Хранилище
     *
     * @var AbstractStorage
     */
    private $storage;

    /**
     * Класс, в котором сохраняется изображение
     *
     * @var string
     */
    private $dataClass;

    /**
     * Класс, для которого сохраняется ихображение
     *
     * @var string
     */
    private $entityClass;

    /**
     * Имя поля с изображением
     *
     * @var string
     */
    private $propertyName;

    public function __construct(ObjectManager $om, AbstractStorage $storage, $dataClass, $entityClass, $propertyName)
    {
        $this->om = $om;
        $this->storage = $storage;
        $this->dataClass = $dataClass;
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
        if ($value === null) {
            return null;
        }

        /** @var AbstractRepository $rep */
        $rep = $this->om->getRepository($this->dataClass);

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
