# Работа с файлами

## Требования:
- php 7
- symfony 3.2

## Установка

1. Прописать пакет и ссылку на репозиторий в `composer.json`
```json
{
    // ...
    "require": {
        // ...
        "SmartInformationSystems/file-bundle": "dev-master"
    },
    "repositories": [
        {
            "type" : "vcs",
            "url" : "https://github.com/SmartInformationSystems/FileBundle.git"
        }
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
            // ...
            new SmartInformationSystems\FileBundle\SmartInformationSystemsFileBundle(),
            // ...
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
            url: 'http://localhost/storage'
```

## Использование

### Добавление картинки в сущность

1. Добавляем поле в Entity
```php
use SmartInformationSystems\FileBundle\Entity\Image;
use SmartInformationSystems\FileBundle\Annotations as Files;

class Brand
{
    /**
     * Логотип
     *
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="SmartInformationSystems\FileBundle\Entity\Image", cascade="all")
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", nullable=true)
     *
     * @Files\Image(
     *   width=370, height=210,
     *   previews={
     *     @Files\Image\Preview(name="admin", width=100, height=100),
     *   }
     * )
     */
    private $logo;
}
```

2. Применяем изменения в БД

`php binv/console doctrine:schema:update`

### Добавление коллекции картинок в сущность

1. Добавляем новую сущность

```php
use SmartInformationSystems\FileBundle\Entity\Image;
use SmartInformationSystems\FileBundle\Annotations as Files;

class ProductImage
{
    /**
     * Товар
     *
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $good;

    /**
     * Изображение.
     *
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="SmartInformationSystems\FileBundle\Entity\Image", cascade="all")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false)
     *
     * @Files\Image(
     *   width=700, height=700,
     *   previews={
     *     @Files\Image\Preview(name="admin", width=100, height=100, crop=true),
     *     @Files\Image\Preview(name="small", width=65, height=65, crop=true),
     *     @Files\Image\Preview(name="medium", width=250, height=250, crop=true)
     *   }
     * )
     */
    private $image;
}
```

2. Применяем изменения в БД

`php binv/console doctrine:schema:update`

### Вывод изображени в Sonata Admin

#### Список элементов

1. Добавляем поле в Admin класс

```php
class BrandAdmin extends AbstractAdmin
{
    $listMapper->add('logo', 'string', [
        'template' => 'SmartInformationSystemsFileBundle:sonata-admin:list_image.html.twig',
    ]);
}
```

#### Форма редактирования

1. Добавить шаблон в `app/config/config.yml`

```yaml
twig:
    form_themes:
        - 'SmartInformationSystemsFileBundle:Form:fields.html.twig'

```

2. Добавить поле в Admin класс
```php
use SmartInformationSystems\FileBundle\Form\Type\FileType;

class BrandAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('logo', FileType::class, [
            'entity_class' => Brand::class,
            'data_class' => Image::class,
            'required' => false,
        ]);
    }
}
```