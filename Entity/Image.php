<?php

namespace SmartInformationSystems\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use SmartInformationSystems\FileBundle\Common\OriginalFile;
use SmartInformationSystems\FileBundle\Common\AbstractEntity;

/**
 * Изображение.
 *
 * @ORM\Entity(repositoryClass="SmartInformationSystems\FileBundle\Entity\ImageRepository")
 * @ORM\Table(name="sis_image")
 * @ORM\HasLifecycleCallbacks()
 */
class Image extends AbstractEntity
{
    /**
     * Файл.
     *
     * @var File
     *
     * @ORM\OneToOne(targetEntity="File", cascade="all")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    protected $file;

    /**
     * Ширина картинки.
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $width;

    /**
     * Высота картинки.
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $height;

    /**
     * Превью.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ImagePreview", mappedBy="image", cascade="all", orphanRemoval=true)
     */
    protected $previews;

    /**
     * {@inheritdoc}
     */
    public function __construct(OriginalFile $originalFile = NULL)
    {
        parent::__construct($originalFile);

        $this->previews = new ArrayCollection();

        if ($this->getOriginalFile()) {
            if (!($info = getimagesize($this->getOriginalFile()->getRealPath()))) {
                throw new \Exception('Файл не является картинкой: ' . $this->getOriginalFile()->getRealPath());
            }

            $this->setFile(new File($originalFile));
            $this->setWidth($info[0]);
            $this->setHeight($info[1]);
        }
    }

    /**
     * Устанвливает ширину картинки.
     *
     * @param integer $width Ширина картинки
     *
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Возвращает ширину картинки.
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Устанавливает высоту картинки.
     *
     * @param integer $height Высота картинки
     *
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Возвращает высоту картинки.
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Устанавливает файл.
     *
     * @param File $file Файл
     *
     * @return Image
     */
    private function setFile(File $file)
    {
        $this->file = $file;

        return $this;
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
     * Является ли файл картинкой.
     *
     * @return bool
     */
    public function isImage()
    {
        return TRUE;
    }

    /**
     * Добавление превью.
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
     * Удаление превью.
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
     * Возвращает список превью.
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
        return NULL;
    }

    /**
     * Выполняется перед сохранением в БД.
     *
     * @ORM\PrePersist
     */
    public function prePersistHandler()
    {
        parent::prePersistHandler();
    }
}
