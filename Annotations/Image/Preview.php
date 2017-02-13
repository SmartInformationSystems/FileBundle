<?php
namespace SmartInformationSystems\FileBundle\Annotations\Image;

use Doctrine\ORM\Mapping\Annotation;

/**
 * Превью картинки.
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Preview implements Annotation
{
    /**
     * Имя превью.
     *
     * @var string
     */
    public $name;

    /**
     * Ширина.
     *
     * @var integer
     */
    public $width;

    /**
     * Высота.
     *
     * @var integer
     */
    public $height;

    /**
     * Обрезать ли при уменьшении.
     *
     * @var boolean
     */
    public $crop = false;
}
