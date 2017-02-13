# Работа с файлами

## Требования:
- php 7
- symfony 3.2

## Установка
1. Прописать пакет и ссылку на репозиторий в `composer.json`
```json
{
    ...
    "require": {
        ...
        "SmartInformationSystems/file-bundle": "dev-master",
        ...
    },
    "repositories": [
        {
            "type" : "vcs",
            "url" : "https://github.com/SmartInformationSystems/FileBundle.git"
        },
    ]
}
```

2. Включить бандл в `app/AppKernel.php`
```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new SmartInformationSystems\FileBundle\SmartInformationSystemsFileBundle(),
            ...
        );
    }
}
```

3. Прописать настройки в `app/config/config.yml`
```yaml
smart_information_systems_file:
    storage:
        type: filesystem
        params:
            path: '%kernel.root_dir%/../web/storage'
            url: 'http://localhost/storage/'
```
