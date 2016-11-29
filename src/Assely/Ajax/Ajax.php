<?php

namespace Assely\Ajax;

use Assely\Contracts\Routing\RouteInterface;
use Assely\Hook\HookFactory;
use Assely\Nonce\NonceFactory;
use Assely\Routing\ControllerActionResolver;
use Assely\Routing\Router;
use Assely\Support\Accessors\HasArguments;
use Assely\Support\Accessors\HasSlug;
use Illuminate\Contracts\Container\Container;

class Ajax implements RouteInterface
{
    use HasSlug, HasArguments, ControllerActionResolver;

    /**
     * Dispacher instance.
     *
     * @var \Assely\Ajax\Dispatcher
     */
    protected $router;

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
     * @param \Assely\Routing\Router $router
     * @param \Assely\Hook\HookFactory $hook
     * @param \Assely\Nonce\NonceFactory $nonce
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(
        Router $router,
        HookFactory $hook,
        NonceFactory $nonce,
        Container $container
    ) {
        $this->router = $router;
        $this->hook = $hook;
        $this->nonce = $nonce;
        $this->container = $container;

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

            if (! $this->nonceValid()) {
                echo json_encode($this->getErrorMessage());

                die();
            }

            echo json_encode($this->run());

            die();
        })->dispatch();
    }

    /**
     * Verify nonce token.
     *
     * @return bool
     */
    public function nonceValid()
    {
        return $this->nonce->check('assely-ajax', $_REQUEST['nonce']);
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
}
