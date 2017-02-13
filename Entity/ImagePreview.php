<?php
namespace SmartInformationSystems\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use SmartInformationSystems\FileBundle\Common\AbstractEntity;

/**
 * Превью изображения.
 *
 * @ORM\Entity(repositoryClass="SmartInformationSystems\FileBundle\Repository\ImagePreviewRepository")
 * @ORM\Table(
 *     name="sis_image_preview",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="ui_name", columns={"image_id", "name"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class ImagePreview extends AbstractEntity
{
    /**
     * Имя превью
     *
     * @var string
     *
     * @ORM\Column(nullable=false)
     */
    protected $name;

    /**
     * Изображение
     *
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="previews", cascade="all")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false)
     */
    protected $image;

    /**
     * Файл
     *
     * @var File
     *
     * @ORM\OneToOne(targetEntity="File", cascade="all")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    protected $file;

    /**
     * Ширина картинки
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $width;

    /**
     * Высота картинки
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $height;

    /**
     * {@inheritdoc}
     */
    public function __construct($originalFile = null)
    {
        parent::__construct($originalFile);

        if ($this->getOriginalFile()) {
            if (!($info = getimagesize($this->getOriginalFile()->getRealPath()))) {
                throw new \Exception('Файл не является картинкой: ' . $this->getOriginalFile()->getRealPath());
            }

            $this->file = new File($originalFile);
            $this->width = $info[0];
            $this->height = $info[1];
        }
    }

    /**
     * Устанавливает имя
     *
     * @param string $name Имя
     *
     * @return ImagePreview
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Возвращает имя.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Возвращает ширину
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Возвращает высоту
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Устанавливает изображение
     *
     * @param Image $image Изображение
     *
     * @return ImagePreview
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Возвращает изображение
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Возвращает файл
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Является ли файл картинкой
     *
     * @return bool
     */
    public function isImage()
    {
        return true;
    }

    /**
     * Выполняется перед сохранением в БД
     *
     * @ORM\PrePersist
     */
    public function prePersistHandler()
    {
        parent::prePersistHandler();
    }
}
