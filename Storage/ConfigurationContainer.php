<?php
namespace SmartInformationSystems\FileBundle\Storage;

/**
 * Настройка хранилища
 */
class ConfigurationContainer
{
    /**
     * Настройки
     *
     * @var array
     */
    private $config = array();

    /**
     * Конструктор
     *
     */
    public function __construct()
    {
    }

    /**
     * Установка конфига
     *
     * @param array $config Конфиг
     *
     * @return ConfigurationContainer
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Возвращает тип хранилища
     *
     * @return string
     */
    public function getStorageType()
    {
        return $this->config['storage']['type'];
    }

    /**
     * Возвращает настройки хранилища
     *
     * @return array
     */
    public function getStorageParams()
    {
        return $this->config['storage']['params'];
    }
}
