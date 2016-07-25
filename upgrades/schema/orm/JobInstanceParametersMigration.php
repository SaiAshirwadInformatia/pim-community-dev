<?php

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @author    Adrien Pétremann <adrien.petremann@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class JobInstanceParametersMigration
{
    const JOB_INSTANCE_TABLE = 'akeneo_batch_job_instance';

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /** @var ConsoleOutput */
    protected $output;

    /**
     * @param ConsoleOutput $output
     * @param ArgvInput     $input
     */
    public function __construct(ConsoleOutput $output, ArgvInput $input)
    {
        $this->output = $output;

        $kernel = $this->bootKernel($input->getParameterOption(['-e', '--env'], 'dev'));
        $this->container = $kernel->getContainer();
    }

    /**
     * Fetch all job instances then update only "product export" jobs parameters.
     */
    public function updateJobInstanceParameters()
    {
        $jobInstanceRepo = $this->container->get('pim_import_export.repository.job_instance');
        $channelRepo = $this->container->get('pim_catalog.repository.channel');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $validator = $this->container->get('validator');

        $this->output->write('Fetching job instances... ');
        $jobInstances = $jobInstanceRepo->findBy(['type' => 'export']);
        $this->output->writeln('<info>OK</info>');

        foreach ($jobInstances as $jobInstance) {
            $parameters = $jobInstance->getRawParameters();

            // Only product exports have a parameter named 'channel'
            if (isset($parameters['channel'])) {
                $this->output->write(sprintf('Updating job instance [%s]... ', $jobInstance->getCode()));

                $channel = $channelRepo->findOneByIdentifier($parameters['channel']);

                if (null === $channel) {
                    throw new EntityNotFoundException(sprintf('No channel with code "%s"', $channel->getCode()));
                }

                $locales = $channel->getLocales();
                $localeCodes = [];
                foreach ($locales as $locale) {
                    $localeCodes[] = $locale->getCode();
                }

                $parameters['filters'] = [
                    'data'      => [],
                    'structure' => [
                        'scope'   => $channel->getCode(),
                        'locales' => $localeCodes
                    ]
                ];

                unset($parameters['channel']);
                $jobInstance->setRawParameters($parameters);
                $errors = $validator->validate($jobInstance);

                if (count($errors) === 0) {
                    $entityManager->persist($jobInstance);
                    $entityManager->flush();

                    $this->output->writeln('<info>OK</info>');
                } else {
                    $this->output->writeln(sprintf('<error>Error: %s</error>', (string) $errors));
                }
            }
        }

        $this->output->writeln('');
        $this->output->writeln('<info>Done!</info>');
    }

    /**
     * Boot kernel
     *
     * @param string $env
     *
     * @return AppKernel
     */
    protected function bootKernel($env)
    {
        $kernel = new AppKernel($env, $env === 'dev');
        $kernel->loadClassCache();
        $kernel->boot();

        return $kernel;
    }
}
