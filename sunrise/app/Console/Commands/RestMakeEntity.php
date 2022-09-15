<?php namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Pz\Doctrine\Rest\RestRepository;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class RestMakeEntity extends Command
{
    const ENTITY_NAMESPACE = 'App\Entities';

    const REPOSITORY_NAMESPACE = 'App\Repositories';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rest:make:entity {entity : Entity name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * RestMakeEntity constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = ucfirst($this->argument('entity'));
        $resourceKey = $this->ask('Resource key?', strtolower(snake_case($name)));

        /** @var PhpFile $entityFile */
        /** @var PhpFile $repositoryFile */
        list($entityFile, $repositoryFile) = $this->generateEntityClass($name, $resourceKey);

        $entityClass = array_first(array_first($entityFile->getNamespaces())->getClasses());
        $repositoryClass = array_first(array_first($repositoryFile->getNamespaces())->getClasses());

        $this->saveFile(app_path(sprintf("Entities/%s.php", $entityClass->getName())), $entityFile);
        $this->saveFile(app_path(sprintf("Repositories/%s.php", $repositoryClass->getName())), $repositoryFile);

    }

    /**
     * @param string $name
     * @param string $resourceKey
     * @return array|[
     */
    public function generateEntityClass(string $name, string $resourceKey)
    {
        $timestamps = $this->confirm('Does entity need to have timestamps?', true);

        $entityFile = new PhpFile();
        $entityFile->setComment('Auto-generated entity class');
        $entityFile->setStrictTypes();

        $entityNamespace = $entityFile->addNamespace(static::ENTITY_NAMESPACE);
        $entityNamespace->addUse('Doctrine\ORM\Mapping', 'ORM');
        $entityNamespace->addUse(JsonApiResource::class);

        $entityClass = $entityNamespace->addClass($name);
        $entityClass->addImplement(JsonApiResource::class);

        $entityClass->addMethod('getResourceKey')
            ->setStatic()
            ->addComment('@return string')
            ->setBody("return \"$resourceKey\";");

        $entityClass->addProperty('id')
            ->setVisibility('protected')
            ->addComment('@ORM\Id()')
            ->addComment('@ORM\GeneratedValue(strategy="AUTO")')
            ->addComment('@ORM\Column(type="integer")');

        $entityClass->addMethod('getId')
            ->addComment('@return int')
            ->setBody('return $this->id;');

        if ($timestamps) {
            $entityNamespace->addUse(Timestamps::class);
            $entityClass->addTrait(Timestamps::class);
        }

        $repositoryFile = new PhpFile();
        $repositoryFile->setComment('Auto-generated rest repository class');
        $repositoryFile->setStrictTypes();

        $repositoryNamespace = $repositoryFile->addNamespace(static::REPOSITORY_NAMESPACE);
        $repositoryNamespace->addUse(RestRepository::class);

        $repositoryClass = $repositoryNamespace->addClass(sprintf('%sRepository', $entityClass->getName()));
        $repositoryClass->addExtend(RestRepository::class);

        $entityClass->addComment(sprintf('@ORM\Entity(repositoryClass="%s%s")',
            $repositoryNamespace->getName(),
            $repositoryNamespace->unresolveName($repositoryClass->getName())
        ));

        return [$entityFile, $repositoryFile];
    }

    /**
     * @param string $path
     * @param PhpFile $file
     */
    private function saveFile(string $path, PhpFile $file)
    {
        foreach ($file->getNamespaces() as $namespace) {
            foreach ($namespace->getClasses() as $class) {
                $this->output->getFormatter()->setStyle('warn', new OutputFormatterStyle('yellow'));
                $this->output->writeln(sprintf(
                    '<info>Generate: </info><warn>%s</warn>',
                    $namespace->getName().'\\'.$class->getName())
                );
            }
        }

        if (!file_exists($path) || $this->confirm(sprintf('Are you sure want override "%s"', $path))) {
            if (!file_put_contents($path, $file)) {
                throw new \RuntimeException(sprintf('Failed saving file to path: %s', $path));
            }
        } else {
            if ($this->confirm('Are you want to see file content', true)) {
                $this->output->write($file);
            }
        }
    }
}
