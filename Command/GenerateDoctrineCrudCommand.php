<?php

namespace Xsolve\BootstrapCrudBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand as GenerateDoctrineCrudCommandMain;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Generates a CRUD for a Doctrine entity.
 *
 * @author Piotr Pasich <piotr.pasich@xsolve.pl>
 */
class GenerateDoctrineCrudCommand extends GenerateDoctrineCrudCommandMain
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('entity', '', InputOption::VALUE_REQUIRED, 'The entity class name to initialize (shortcut notation)'),
                new InputOption('route-prefix', '', InputOption::VALUE_REQUIRED, 'The route prefix'),
                new InputOption('with-write', '', InputOption::VALUE_NONE, 'Whether or not to generate create, new and delete actions'),
                new InputOption('format', '', InputOption::VALUE_REQUIRED, 'Use the format for configuration files (php, xml, yml, or annotation)', 'annotation'),
                new InputOption('overwrite', '', InputOption::VALUE_NONE, 'Do not stop the generation if crud controller already exist, thus overwriting all generated files'),
            ))
            ->setDescription('Twitter generates a CRUD based on a Doctrine entity')
            ->setHelp(<<<EOT
The <info>doctrine:generate:crud</info> command generates a CRUD based on a Doctrine entity.

The default command only generates the list and show actions.

<info>php app/console doctrine:generate:crud --entity=AcmeBlogBundle:Post --route-prefix=post_admin</info>

Using the --with-write option allows to generate the new, edit and delete actions.

<info>php app/console doctrine:generate:crud --entity=AcmeBlogBundle:Post --route-prefix=post_admin --with-write</info>

Every generated file is based on a template. There are default templates but they can be overriden by placing custom templates in one of the following locations, by order of priority:

<info>BUNDLE_PATH/Resources/SensioGeneratorBundle/skeleton/crud
APP_PATH/Resources/SensioGeneratorBundle/skeleton/crud</info>

And

<info>__bundle_path__/Resources/SensioGeneratorBundle/skeleton/form
__project_root__/app/Resources/SensioGeneratorBundle/skeleton/form</info>

You can check https://github.com/sensio/SensioGeneratorBundle/tree/master/Resources/skeleton
in order to know the file structure of the skeleton
EOT
            )
            ->setName('xsolve:generate:crud')
            ->setAliases(array('xsolve:doctrine:crud'))
        ;
    }

    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        if (isset($bundle) && is_dir($dir = $bundle->getPath().'/Resources/SensioGeneratorBundle/skeleton')) {
            $skeletonDirs[] = $dir;
        }

        if (is_dir($dir = $this->getContainer()->get('kernel')->getRootdir().'/Resources/SensioGeneratorBundle/skeleton')) {
            $skeletonDirs[] = $dir;
        }

        $bundleDir = dirname($this->getContainer()->get('kernel')->locateResource('@XsolveBootstrapCrudBundle/Command/GenerateDoctrineCrudCommand.php'));
        $skeletonDirs[] = $bundleDir . '/../Resources/skeleton';
        $skeletonDirs[] = $bundleDir . '/../Resources';

        return $skeletonDirs;
    }
}
