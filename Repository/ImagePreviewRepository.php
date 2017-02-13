<?php
namespace SmartInformationSystems\FileBundle\Repository;

use SmartInformationSystems\FileBundle\Common\OriginalFile;
use SmartInformationSystems\FileBundle\Common\AbstractRepository;
use SmartInformationSystems\FileBundle\Entity\Image;
use SmartInformationSystems\FileBundle\Entity\ImagePreview;

class ImagePreviewRepository extends AbstractRepository
{
    /**
     * Создание экземпляра объекта.
     *
     * @param OriginalFile $originalFile Оригиналный файл
     * @param array $options Настройки
     *
     * @return ImagePreview
     */
    public function createEntity(OriginalFile $originalFile, array $options = [])
    {
        return new ImagePreview($originalFile);
    }

    /**
     * Возвращает превью изображения по имени
     *
     * @param Image $image Изображение
     * @param string $name Имя
     *
     * @return ImagePreview
     */
    public function getByName(Image $image, $name)
    {
        if (!$image->getId()) {
            return null;
        }

        return $this->findOneBy([
            'image' => $image,
            'name' => $name,
        ]);
    }

    /**
     * Создание превью из файла
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
