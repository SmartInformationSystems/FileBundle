<?php
namespace SmartInformationSystems\FileBundle\Annotations;

use Doctrine\ORM\Mapping\Annotation;

/**
 * Картинка.
 *
 * @Annotation
 * @Target("PROPERTY")
 */
final class Image implements Annotation
{
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
     * Обрезать при ресайзе.
     *
     * @var boolean
     */
    public $crop = false;

    /**
     * @var array<SmartInformationSystems\FileBundle\Annotations\Image\Preview>
     */
    public $previews = [];
}
