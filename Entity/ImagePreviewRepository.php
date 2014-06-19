<?php

namespace SmartSystems\FileBundle\Entity;

use SmartSystems\FileBundle\Common\OriginalFile;
use SmartSystems\FileBundle\Common\AbstractRepository;

/**
 * Репозиторий изображений.
 *
 */
class ImagePreviewRepository extends AbstractRepository
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
        return new ImagePreview($originalFile);
    }

    /**
     * Возвращает превью изображения по имени.
     *
     * @param Image $image Изображение
     * @param string $name Имя
     *
     * @return ImagePreview
     */
    public function getByName(Image $image, $name)
    {
        if (!$image->getId()) {
            return NULL;
        }

        return $this->findOneBy(array(
            'image' => $image,
            'name' => $name,
        ));
    }

    /**
     * Создание превью из файла.
     *
     * @param OriginalFile $originalFile Файл
     * @param string $name
     * @param integer $width
     * @param integer $height
     * @param boolean $crop
     *
     * @return ImagePreview
     */
    public static function createPreviewFromFile(OriginalFile $originalFile, $name, $width, $height, $crop)
    {
        $file = $originalFile->duplicate();
        ImageRepository::resizeFile($file, $width, $height, $crop);

        $preview = new ImagePreview($file);
        $preview->setName($name);

        return $preview;
    }
}
