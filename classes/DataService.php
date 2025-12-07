<?php
namespace Grav\Plugin\EolManager;

use Grav\Common\Grav;

class DataService
{
    protected $grav;
    protected $dataPath;
    protected $seedPath;

    public function __construct(Grav $grav)
    {
        $this->grav = $grav;
        // User data location: user/data/eol-manager/ocean-data.json
        $this->dataPath = $this->grav['locator']->findResource('user://data', true) . '/eol-manager/ocean-data.json';
        // Plugin (seed) location
        $this->seedPath = __DIR__ . '/../data/ocean-data.json';
        
        $this->initData();
    }

    protected function initData()
    {
        $dir = dirname($this->dataPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        if (!file_exists($this->dataPath) && file_exists($this->seedPath)) {
            copy($this->seedPath, $this->dataPath);
        }
    }

    public function getData()
    {
        if (file_exists($this->dataPath)) {
            $content = file_get_contents($this->dataPath);
            return json_decode($content, true);
        }
        return [];
    }

    public function saveData($data)
    {
        if ($data) {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return file_put_contents($this->dataPath, $json) !== false;
        }
        return false;
    }
}
