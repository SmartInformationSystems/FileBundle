<?php
namespace SmartInformationSystems\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SmartInformationSystems\FileBundle\Common\OriginalFile;
use SmartInformationSystems\FileBundle\Common\AbstractEntity;

/**
 * Изображение
 *
 * @ORM\Entity(repositoryClass="SmartInformationSystems\FileBundle\Repository\ImageRepository")
 * @ORM\Table(name="sis_image")
 * @ORM\HasLifecycleCallbacks()
 */
class Image extends AbstractEntity
{
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
     * Превью
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ImagePreview", mappedBy="image", cascade="all", orphanRemoval=true)
     */
    protected $previews;

    /**
     * {@inheritdoc}
     */
    public function __construct(OriginalFile $originalFile = null)
    {
        parent::__construct($originalFile);

        $this->previews = new ArrayCollection();

        if ($this->getOriginalFile()) {
            if (!($info = getimagesize($this->getOriginalFile()->getRealPath()))) {
                throw new \Exception('Файл не является картинкой: ' . $this->getOriginalFile()->getRealPath());
            }

            $this->file = new File($originalFile);
            $this->width = $info[0];
            $this->height =$info[1];
        }
    }

    /**
     * Возвращает ширину картинки
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Возвращает высоту картинки
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Возвращает файл.
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
     * Добавление превью
     *
     * @param ImagePreview $previews Превью
     *
     * @return Image
     */
    public function addPreview(ImagePreview $previews)
    {
        $this->previews[] = $previews;

        return $this;
    }

    /**
     * Удаление превью
     *
     * @param ImagePreview $previews Превью
     *
     * @return Image
     */
    public function removePreview(ImagePreview $previews)
    {
        $this->previews->removeElement($previews);

        return $this;
    }

    /**
     * Возвращает список превью
     *
     * @return ArrayCollection|ImagePreview[]
     */
    public function getPreviews()
    {
        return $this->previews;
    }

    /**
     * Возвращает превью по имени
     *
     * @param string $name Имя превью
     *
     * @return ImagePreview
     */
    public function getPreview($name)
    {
        foreach ($this->getPreviews() as $preview) {
            if ($preview->getName() == $name) {
                return $preview;
            }
        }

        return null;
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
