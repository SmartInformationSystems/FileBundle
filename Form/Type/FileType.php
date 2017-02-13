<?php
namespace SmartInformationSystems\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\FileType as SymfonyFileType;
use SmartInformationSystems\FileBundle\Form\DataTransformer\UploadedFileTransformer;
use SmartInformationSystems\FileBundle\Storage\ConfigurationContainer;
use SmartInformationSystems\FileBundle\Storage\StorageFactory;
use SmartInformationSystems\FileBundle\Storage\AbstractStorage;
use SmartInformationSystems\FileBundle\Entity\Image;
use SmartInformationSystems\FileBundle\Repository\ImagePreviewRepository;

class FileType extends AbstractType
{
    /**
     * Подключение к БД
     *
     * @var ObjectManager
     */
    private $om;

    /**
     * Хранилище файлов
     *
     * @var AbstractStorage
     */
    private $storage;

    public function __construct(ObjectManager $entityManager, ConfigurationContainer $configuration)
    {
        $this->om = $entityManager;
        $this->storage = StorageFactory::create($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->storage->setEntityClass($options['data_class']);

        $builder->addModelTransformer(
            new UploadedFileTransformer(
                $this->om,
                $this->storage,
                $options['data_class'],
                $options['entity_class'],
                $builder->getName()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $file = $form->getData();

        if ($file !== NULL) {

            $fileUrl = $this->storage->getUrl($file);
            $previewUrl = $fileUrl;

            if ($file instanceof Image) {
                /** @var ImagePreviewRepository $rep */
                $rep = $this->om->getRepository('SmartInformationSystemsFileBundle:ImagePreview');
                if ($preview = $rep->getByName($file, 'admin')) {
                    $previewUrl = $this->storage->getUrl($preview);
                }
            }

        } else {
            $fileUrl = NULL;
            $previewUrl = NULL;
        }

        $view->vars['file_url'] = $fileUrl;
        $view->vars['preview_url'] = $previewUrl;
        $view->vars['file_object'] = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'invalid_message' => 'Error storing file.',
            ))
        ;

        $resolver->setRequired(array(
            'data_class',
            'entity_class',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return SymfonyFileType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sis_file_type';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sis_file_type';
    }
}
