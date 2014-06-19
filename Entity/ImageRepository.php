<?php

namespace SmartSystems\FileBundle\Entity;

use Doctrine\Common\Annotations\AnnotationReader;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

use SmartSystems\FileBundle\Common\OriginalFile;
use SmartSystems\FileBundle\Common\AbstractRepository;

/**
 * Репозиторий изображений.
 *
 */
class ImageRepository extends AbstractRepository
{
    /**
     * Создание экземпляра объекта.
     *
     * @param OriginalFile $originalFile Оригиналный файл
     * @param array $options Настройки
     *
     * @return Image
     */
    public function createEntity(OriginalFile $originalFile, array $options = array())
    {
        $image = new Image($originalFile);

        if (isset($options['previews'])) {
            if (!is_array($options['previews'])) {
                $options['previews'] = array($options['previews']);
            }

            /** @var ImagePreview $preview */
            foreach ($options['previews'] as $preview) {
                $preview->setImage($image);
                $image->addPreview($preview);
            }
        }

        return $image;
    }

    /**
     * Ресайз изображения, хранящегося в файле, и сохранение в этот же файл.
     *
     * @param OriginalFile $file Файл
     * @param integer $width Ширина
     * @param integer $height Высота
     * @param boolean $crop Обрезать ли
     *
     * @return void
     */
    public static function resizeFile(OriginalFile $file, $width, $height, $crop)
    {
        $imagine = new Imagine();
        $imagine
            ->open($file->getRealPath())
            ->thumbnail(
                new Box($width, $height),
                $crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET
            )
            ->save($file->getRealPath())
        ;
        clearstatcache(TRUE, $file->getRealPath());
    }

    /**
     * Возвращает настройки создания изображения по аннотации поля.
     *
     * @param OriginalFile $originalFile Исходный файл
     * @param string $class Имя класса
     * @param string $property Имя поля
     *
     * @return array
     */
    public static function getCreateOptions(OriginalFile $originalFile, $class, $property)
    {
        $options = array();

        // Обработаем изображение
        $reader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($class);
        $reflectionProperty = $reflectionClass->getProperty($property);

        if ($annotation = $reader->getPropertyAnnotation(
            $reflectionProperty,
            'SmartSystems\FileBundle\Annotations\Image'
        )) {
            $options['previews'] = array();

            if (count($annotation->previews) > 0) {

                foreach ($annotation->previews as $previewSettings) {
                    $options['previews'][] = ImagePreviewRepository::createPreviewFromFile(
                        $originalFile,
                        $previewSettings->name,
                        $previewSettings->width,
                        $previewSettings->height,
                        $previewSettings->crop
                    );
                }
            }

            if ($annotation->width && $annotation->height) {
                self::resizeFile(
                    $originalFile,
                    $annotation->width,
                    $annotation->height,
                    $annotation->crop
                );
            }
        }

        return $options;
    }
}
