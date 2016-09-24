<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Yaml\Yaml;

class AppKernel extends Kernel
{
    private $_bundles = [];
    
    private $_config = [
        //'parameters' => ['kernel.secret' => 'whassup']
    ];
    
    protected $name = '';
    
    public function __construct($environment, $debug)
    {        
        $this->name = str_replace('.','',uniqid('A',true));
        parent::__construct($environment, $debug);
    }
    
    public function __destruct()
    {
        $dir = $this->getCacheDir();
        $files = scandir($dir);
        foreach($files as $file) {
            if ($file[0] != '.') {
                unlink("$dir/$file");
            }
        }
    }

    public function addBundle($bundleInstance) {
        $this->_bundles[] = $bundleInstance;
    }
    
    public function registerBundles()
    {
        return $this->_bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return __DIR__.'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return __DIR__.'/var/logs';
    }

    public function setConfig($config) {
        $this->_config = array_merge_recursive($this->_config, $config);
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $file = $this->getRootDir() . '/config.yml';
        $contents = Yaml::dump($this->_config);
        file_put_contents($file, $contents);
        $loader->load($file);
    }
}
