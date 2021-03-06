<?php
namespace SmartInformationSystems\FileBundle\Common;

use Doctrine\ORM\Mapping as ORM;

use SmartInformationSystems\FileBundle\Entity\File as SisFile;

/**
 * Абстрактный класс для файлов
 */
abstract class AbstractEntity
{
    /**
     * Идентификатор
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Дата создания
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * Оригинальный файл
     *
     * @var OriginalFile
     */
    protected $originalFile;

    public function __construct(OriginalFile $originalFile = null)
    {
        if ($originalFile) {
            $this->originalFile = $originalFile;
        }
    }

    /**
     * Возвращает идентификатор
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Возвращает дату создания
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Выполняется перед сохранением в БД.
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Возвращает оригинальный файл
     *
     * @return OriginalFile
     */
    public function getOriginalFile()
    {
        return $this->originalFile;
    }

    /**
     * Возвращет файл
     *
     * @return SisFile
     */
    abstract public function getFile();

    /**
     * Является ли файл картинкой
     *
     * @return bool
     */
    abstract public function isImage();
}
