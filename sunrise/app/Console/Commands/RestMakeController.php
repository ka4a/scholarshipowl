<?php namespace App\Console\Commands;

use App\Http\Requests\RestRequest;
use Doctrine\ORM\EntityManager;
use League\Fractal\TransformerAbstract;
use Illuminate\Console\Command;
use Illuminate\Routing\Controller;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Action\CreateAction;
use Pz\LaravelDoctrine\Rest\Action\DeleteAction;
use Pz\LaravelDoctrine\Rest\Action\IndexAction;
use Pz\LaravelDoctrine\Rest\Action\ShowAction;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;
use Pz\LaravelDoctrine\Rest\Traits\WithRestAbilities;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class RestMakeController extends Command
{
    const ENTITY_NAMESPACE = 'App\Entities';

    const POLICY_NAMESPACE = 'App\Policy';

    const TRANSFORMER_NAMESPACE = 'App\Transformers';

    const CONTROLLER_NAMESPACE = 'App\Http\Controllers\Rest';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rest:make:controller {entity : Entity class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entity = ucfirst($this->argument('entity'));
        $entityClass = ClassType::from(sprintf('App\Entities\%s', $entity));

        $this->generatePolicy($entityClass);
        $this->generateTransformer($entityClass);
        $this->generateController($entityClass);
    }

    /**
     * @param ClassType $entity
     * @return ClassType
     */
    public function generatePolicy(ClassType $entity)
    {
        $policyFile = new PhpFile();
        $policyFile->setStrictTypes();
        $policyFile->setComment('Auto-generated file');

        $policyNamespace = $policyFile->addNamespace(static::POLICY_NAMESPACE);
        $policyNamespace->addUse(WithRestAbilities::class);

        $policyClass = $policyNamespace->addClass(sprintf('%sPolicy', $entity->getName()));
        $policyClass->addTrait(WithRestAbilities::class);

        $this->saveFile(app_path('Policies/'.$policyClass->getName().'.php'), $policyFile);

        return $policyClass;
    }

    /**
     * @param ClassType $entity
     * @return ClassType
     */
    public function generateTransformer(ClassType $entity)
    {
        $transformerFile = new PhpFile();
        $transformerFile->setStrictTypes();
        $transformerFile->setComment('Auto-generated transformer file');

        $transformerNamespace = $transformerFile->addNamespace(static::TRANSFORMER_NAMESPACE);
        $transformerNamespace->addUse(TransformerAbstract::class);
        $transformerNamespace->addUse(static::ENTITY_NAMESPACE.'\\'.$entity->getName(), $entity->getName());

        $transformerClass = $transformerNamespace->addClass($entity->getName().'Transformer');
        $transformerClass->addExtend(TransformerAbstract::class);

        $transformerClass->addMethod('transform')
            ->addComment(sprintf('@param %s $%s', $entity->getName(), lcfirst($entity->getName())))
            ->addComment('@return array')
            ->addBody('return [')
            ->addBody(sprintf("\t'id' => $%s->getId(),", lcfirst($entity->getName())))
            ->addBody('];')
            ->addParameter(lcfirst($entity->getName()))
                ->setTypeHint(static::ENTITY_NAMESPACE.'\\'.$entity->getName());

        $this->saveFile(app_path('Transformers/'.$transformerClass->getName().'.php'), $transformerFile);

        return $transformerClass;
    }

    /**
     * @param ClassType $entity
     * @return ClassType
     */
    public function generateController(ClassType $entity)
    {
        $controllerFile = new PhpFile();
        $controllerFile->setStrictTypes();
        $controllerFile->setComment('Auto-generated controller file');

        $controllerNamespace = $controllerFile->addNamespace(static::CONTROLLER_NAMESPACE);
        $controllerNamespace->addUse(EntityManager::class);
        $controllerNamespace->addUse(Controller::class);
        $controllerNamespace->addUse(RestRequest::class, 'RestRequest');
        $controllerNamespace->addUse(RestResponse::class, 'RestResponse');
        $controllerNamespace->addUse(static::ENTITY_NAMESPACE.'\\'.$entity->getName());
        $controllerNamespace->addUse(static::TRANSFORMER_NAMESPACE.'\\'.$entity->getName().'Transformer');

        $controllerNamespace->addUse(IndexAction::class, 'IndexAction');
        $controllerNamespace->addUse(ShowAction::class, 'ShowAction');
        $controllerNamespace->addUse(CreateAction::class, 'CreateAction');
        $controllerNamespace->addUse(UpdateAction::class, 'UpdateAction');
        $controllerNamespace->addUse(DeleteAction::class, 'DeleteAction');

        $controllerClass = $controllerNamespace->addClass($entity->getName().'Controller');
        $controllerClass->addExtend(Controller::class);

        $controllerClass->addProperty('em')
            ->setVisibility(ClassType::VISIBILITY_PROTECTED)
            ->setComment('@var EntityManager');

        $controllerClass->addMethod('__construct')
            ->addComment('Create rest controller')
            ->addComment('@param EntityManager $em')
            ->addBody('$this->em = $em;')
            ->addParameter('em')
                ->setTypeHint(EntityManager::class);

        $controllerClass->addMethod('getFilterProperty')
            ->addComment('Attribute that will be used for search query. Example: like "%prop%"')
            ->addComment('@return string|null')
            ->setBody('return null;');

        $controllerClass->addMethod('getFilterable')
            ->addComment('List of attributes that allowed for filtering')
            ->addComment('@return array')
            ->setBody('return [];');

        if ($this->confirm('Set-up index action?', true)) {
            $controllerClass->addMethod('index')
                ->addComment('@param RestRequest $request')
                ->addComment('@return RestResponse')
                ->addBody('return (')
                ->addBody("\tnew IndexAction(")
                ->addBody(sprintf("\t\t\$this->em->getRepository(%s),", $entity->getName() . '::class'))
                ->addBody(sprintf("\t\tnew %s()", $entity->getName() . 'Transformer'))
                ->addBody("\t)")
                ->addBody(')')
                ->addBody("\t->setFilterProperty(\$this->getFilterProperty())")
                ->addBody("\t->setFilterable(\$this->getFilterable())")
                ->addBody("\t->dispatch(\$request);")
                ->addParameter('request')
                ->setTypeHint(RestRequest::class);
        }

        $this->generateControllerAction($controllerClass, $entity, 'show');
        $this->generateControllerAction($controllerClass, $entity, 'create');
        $this->generateControllerAction($controllerClass, $entity, 'update');
        $this->generateControllerAction($controllerClass, $entity, 'delete');

        $filepath = app_path('Http/Controllers/Rest/'.$controllerClass->getName().'.php');
        $this->saveFile($filepath, $controllerFile);

        return $controllerClass;
    }

    public function generateControllerAction(ClassType $controllerClass, ClassType $entity, string $action)
    {
        switch ($action) {
            case 'index':
                $baseAction = IndexAction::class;
                break;
            case 'show':
                $baseAction = ShowAction::class;
                break;
            case 'create':
                $baseAction = CreateAction::class;
                break;
            case 'update':
                $baseAction = UpdateAction::class;
                break;
            case 'delete':
                $baseAction = DeleteAction::class;
                break;
            default:
                throw new \InvalidArgumentException('Unknown action: '.$action);
                break;
        }

        $controllerNamespace = sprintf('%s\%s',
            $controllerClass->getNamespace()->getName(),
            $controllerClass->getName()
        );

        @mkdir($path = app_path('Http/Controllers/Rest/'.$controllerClass->getName()));

        if (!$this->confirm(sprintf('Set-up "%s" action?', $action), true)) {
            return;
        }

        $requestClass = 'RestRequest';
        $requestClassFull = RestRequest::class;
        $actionClass = substr(strrchr($baseAction, '\\'), 1);
        $actionClassFull = $baseAction;

        if ($this->confirm('Generate custom request?', in_array($action, ['create', 'update']))) {
            $requestClass = sprintf('%sRequest', ucfirst($action));
            $requestClassFull = $controllerNamespace.'\\'.$requestClass;
            $requestFile = new PhpFile();
            $requestFile->setStrictTypes()
                ->setComment('Auto-generated class file')
                ->addNamespace($controllerNamespace)
                ->addUse(RestRequest::class)
                ->addClass($requestClass)
                ->addExtend(RestRequest::class);

            $this->saveFile($path . '/' . $requestClass . '.php', $requestFile);
        }

        if ($this->confirm('Generate custom action', in_array($action, ['create', 'update']))) {
            $actionClass = sprintf('%sAction', ucfirst($action));
            $actionClassFull = $controllerNamespace.'\\'.$actionClass;
            $actionFile = new PhpFile();
            $actionFile->setStrictTypes()
                ->setComment('Auto-generated action class')
                ->addNamespace($controllerNamespace)
                ->addUse($baseAction, 'BaseAction')
                ->addClass($actionClass)
                ->addExtend($baseAction);

            $this->saveFile($path . '/' . $actionClass . '.php', $actionFile);
        }

        $controllerClass->getNamespace()
            ->addUse($requestClassFull)
            ->addUse($actionClassFull);

        $controllerClass->addMethod($action)
            ->addComment(sprintf("@param $requestClass \$request"))
            ->addComment('@return RestResponse')
            ->addBody('return (')
            ->addBody(sprintf("\tnew %s(", $actionClass))
            ->addBody(sprintf("\t\t\$this->em->getRepository(%s),", $entity->getName().'::class'))
            ->addBody(sprintf("\t\tnew %s()", $entity->getName().'Transformer'))
            ->addBody("\t)")
            ->addBody(")->dispatch(\$request);")
            ->addParameter('request')
                ->setTypeHint($requestClassFull);
    }

    /**
     * @param string $path
     * @param PhpFile $file
     * @return bool
     */
    private function saveFile(string $path, PhpFile $file)
    {

        foreach ($file->getNamespaces() as $namespace) {
            foreach ($namespace->getClasses() as $class) {
                $this->warn($namespace->getName().'\\'.$class->getName());
            }
        }

        if (!file_exists($path) || $this->confirm(sprintf('Are you sure want override "%s"', $path))) {
            if (!file_put_contents($path, $file)) {
                throw new \RuntimeException(sprintf('Failed saving file to path: %s', $path));
            }

            $this->info(sprintf("File saved:\n%s", $path));
            return true;
        }
        return false;
    }
}
