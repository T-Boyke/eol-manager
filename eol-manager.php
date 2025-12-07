<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Common\Uri;
use Grav\Plugin\EolManager\DataService;

/**
 * Class EolManagerPlugin
 * @package Grav\Plugin
 */
class EolManagerPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Debug Log
        file_put_contents(__DIR__ . '/debug_log.txt', "Plugin Initialized\n", FILE_APPEND);

        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            file_put_contents(__DIR__ . '/debug_log.txt', "Is Admin - returning\n", FILE_APPEND);
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized'   => ['onPageInitialized', 0],
        ]);

        // Autoload Custom Classes
        require_once __DIR__ . '/classes/DataService.php';
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths(): void
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Handle routing for API and Dashboard
     */
    public function onPageInitialized(): void
    {
        $uri = $this->grav['uri'];
        $route = $uri->path();
        
        // Get Config
        $apiRoute = $this->grav['config']->get('plugins.eol-manager.api_route', '/eol-api');
        $dashboardRoute = $this->grav['config']->get('plugins.eol-manager.dashboard_route', '/eol-dashboard');

        file_put_contents(__DIR__ . '/debug_log.txt', "Route Checked: " . $route . "\n", FILE_APPEND);

        // API Routes
        if (strpos($route, $apiRoute) !== false) {
            $this->handleApi($route);
        }

        // Dashboard Route
        if (rtrim($route, '/') === rtrim($dashboardRoute, '/')) {
             $twig = $this->grav['twig'];
             $this->grav['page']->title('Ocean Data Manager');
             header("HTTP/1.1 200 OK");
             echo $twig->processTemplate('eol_dashboard.html.twig');
             exit; 
        }
    }

    protected function handleApi($route)
    {
        $dataService = new DataService($this->grav);
        $method = $_SERVER['REQUEST_METHOD'];
        $config = $this->grav['config']->get('plugins.eol-manager');

        // CORS Handling
        if (!empty($config['cors']['enabled'])) {
            $origin = $config['cors']['allow_origin'] ?? '*';
            $methods = $config['cors']['allow_methods'] ?? 'GET, POST, OPTIONS';
            $headers = $config['cors']['allow_headers'] ?? 'Content-Type, Authorization';

            header("Access-Control-Allow-Origin: " . $origin);
            header("Access-Control-Allow-Methods: " . $methods);
            header("Access-Control-Allow-Headers: " . $headers);
        }

        header('Content-Type: application/json');

        if ($method === 'OPTIONS') {
            exit(0);
        }

        $apiRoute = $config['api_route'] ?? '/eol-api';

        if ($route === $apiRoute . '/data' && $method === 'GET') {
            echo json_encode($dataService->getData());
            exit;
        }

        if ($route === $apiRoute . '/save' && $method === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            if ($input) {
                $success = $dataService->saveData($input);
                echo json_encode(['success' => $success]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON']);
            }
            exit;
        }
    }
}
