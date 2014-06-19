<?php

namespace SmartInformationSystems\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use SmartInformationSystems\FileBundle\Common\OriginalFile;
use SmartInformationSystems\FileBundle\Common\AbstractEntity;

/**
 * Файл.
 *
 * @ORM\Entity(repositoryClass="SmartInformationSystems\FileBundle\Entity\FileRepository")
 * @ORM\Table(name="sis_file")
 * @ORM\HasLifecycleCallbacks()
 */
class File extends AbstractEntity
{
    /**
     * Имя файла.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * Уникальный ключ.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false, unique=true)
     */
    protected $token;

    /**
     * Внешний уникальный ключ, зависит от системы хранения.
     *
     * @var string
     *
     * @ORM\Column(name="external_token", type="string", length=255, nullable=false, unique=true)
     */
    protected $externalToken;

    /**
     * Тип файла.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $mime;

    /**
     * Размер файла.
     *
     * @var integer
     *
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=false)
     */
    protected $size;

    /**
     * {@inheritdoc}
     */
    public function __construct(OriginalFile $originalFile = NULL)
    {
        parent::__construct($originalFile);

        if ($file = $this->getOriginalFile()) {
            $this->setName($file->getOriginalName());
            $this->setSize($file->getSize());
            $this->setMime($file->getMimeType());
        }
    }

    /**
     * Устанавливает имя.
     *
     * @param string $name Имя
     *
     * @return File
     */
    private function setName($name)
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
     * Устанавливает токен.
     *
     * @param string $token Токен
     *
     * @return File
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Возвращает токен.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Устанавливает токен во внешней системе.
     *
     * @param string $externalToken Токен во внешней системе
     *
     * @return File
     */
    public function setExternalToken($externalToken)
    {
        $this->externalToken = $externalToken;

        return $this;
    }

    /**
     * Возвращает токен во внешней системе.
     *
     * @return string
     */
    public function getExternalToken()
    {
        return $this->externalToken;
    }

    /**
     * Устанавливает тип.
     *
     * @param string $mime Тип
     *
     * @return File
     */
    private function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Возвращает тип.
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Устанавливает размер.
     *
     * @param integer $size Размер
     *
     * @return File
     */
    private function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Возвращает размер.
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Выполняется перед сохранением в БД.
     *
     * @ORM\PrePersist
     */
    public function prePersistHandler()
    {
        parent::prePersistHandler();

        if (!$this->getToken()) {
            $this->setToken(md5(microtime() . $this->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return $this;
    }

    /**
     * Является ли файл картинкой.
     *
     * @return bool
     */
    public function isImage()
    {
        return preg_match('/^image\//', $this->getMime());
    }
}
