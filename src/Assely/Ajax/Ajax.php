<?php

namespace Assely\Ajax;

use Assely\Hook\HookFactory;
use Assely\Nonce\NonceFactory;
use Assely\Routing\Controller;
use Assely\Support\Accessors\HasArguments;
use Assely\Support\Accessors\HasSlug;
use Closure;

class Ajax
{
    use HasSlug, HasArguments;

    /**
     * Ajax action callback.
     *
     * @var Closure|string
     */
    protected $action;

    /**
     * Dispacher instance.
     *
     * @var \Assely\Ajax\Dispatcher
     */
    protected $dispatcher;

    /**
     * Hook factory instance.
     *
     * @var \Assely\Hook\HookFactory
     */
    protected $hook;

    /**
     * Nonce factory instance.
     *
     * @var \Assely\Nonce\NonceFactory
     */
    protected $nonce;

    /**
     * Default arguments.
     *
     * @var array
     */
    private $defaults = [
        'accessibility' => 'public',
    ];

    /**
     * Construct ajax.
     *
     * @param \Assely\Ajax\Dispatcher  $dispatcher
     * @param \Assely\Hook\HookFactory $hook
     */
    public function __construct(
        Dispatcher $dispatcher,
        HookFactory $hook,
        NonceFactory $nonce
    ) {
        $this->dispatcher = $dispatcher;
        $this->hook = $hook;
        $this->nonce = $nonce;

        $this->setArguments($this->getDefaults());
    }

    /**
     * Dispatch ajax for registering.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->registerByAccessibility();

        return $this;
    }

    /**
     * Register Ajax by accessibility argument.
     *
     * @return void
     */
    public function registerByAccessibility()
    {
        switch ($this->getArgument('accessibility')) {
            case 'unauthorized':
                $this->registerAsUnauthorized();
                break;

            case 'authorized':
                $this->registerAsAuthorized();
                break;

            default:
                $this->registerAsUnauthorized();
                $this->registerAsAuthorized();
                break;
        }
    }

    /**
     * Register ajax as available authorized.
     *
     * @return void
     */
    public function registerAsAuthorized()
    {
        return $this->register("wp_ajax_{$this->getSlug()}");
    }

    /**
     * Register ajax as available unauthorized.
     *
     * @return void
     */
    public function registerAsUnauthorized()
    {
        return $this->register("wp_ajax_nopriv_{$this->getSlug()}");
    }

    /**
     * Register ajax.
     *
     * @param string $name
     *
     * @return void
     */
    public function register($name)
    {
        $this->hook->action($name, function () {
            header('Content-Type: application/json');

            if ( ! $this->nonceValid()) {
                echo json_encode($this->getErrorMessage());

                die();
            }

            echo json_encode($this->resolveAction());

            die();
        })->dispatch();
    }

    /**
     * Verify nonce token.
     *
     * @return boolean
     */
    public function nonceValid()
    {
        return $this->nonce->check('assely-ajax', $_REQUEST['nonce']);
    }

    /**
     * Resolve Ajax action.
     *
     * @return mixed
     */
    public function resolveAction()
    {
        if ($this->getAction() instanceof Closure) {
            return $this->callAction();
        }

        return $this->resolveController();
    }

    /**
     * Call action callback.
     *
     * @return mixed
     */
    public function callAction()
    {
        $action = Closure::bind($this->getAction(), $this);

        return $this->dispatcher->call($action);
    }

    /**
     * Call controller method assigned to route
     *
     * @throws RoutingException
     *
     * @return mixed
     */
    public function resolveController()
    {
        // Split controller annotation to exctract
        // controller name and method to call.
        list($controller, $method) = explode('@', $this->getAction());

        // Make route controller.
        $this->setController($this->makeController($controller));

        // Call controller method if it exist.
        if ($this->controllerHasMethod($method)) {
            return $this->callControllerMethod($method);
        }

        // Controller do not have defined
        // method. Notify about this.
        $this->getController()->missingMethod($method);
    }

    /**
     * Make route controller.
     *
     * @param  string $name
     *
     * @throws \Assely\Routing\RoutingException
     *
     * @return \Assely\Routing\Controller
     *
     */
    public function makeController($name)
    {
        $class = $this->dispatcher->router->getNamespace() . "\\{$name}";

        if (class_exists($class)) {
            return $this->dispatcher->make($class);
        }

        throw new RoutingException("Controller [{$class}] do not exists.");
    }

    /**
     * Check if controller has defined method.
     *
     * @param  BaseController $controller
     *
     * @return boolean
     */
    public function controllerHasMethod($method)
    {
        return method_exists($this->getController(), $method);
    }

    /**
     * Call controller method.
     *
     * @param  string $method
     *
     * @return void
     */
    public function callControllerMethod($method)
    {
        return $this->dispatcher->call(
            [$this->getController(), $method]
        );
    }

    /**
     * Gets the Route controller.
     *
     * @return \Assely\Routing\Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets the Route controller.
     *
     * @param \Assely\Routing\Controller $controller the controller
     *
     * @return self
     */
    protected function setController(Controller $controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Returns an error message dataset.
     *
     * @return array
     */
    public function getErrorMessage()
    {
        return [
            'error' => [
                'message' => 'Nonce token is invalid or missing.',
            ],
        ];
    }

    /**
     * Gets the value of action.
     *
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the value of action.
     *
     * @param mixed $action the action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }
}
