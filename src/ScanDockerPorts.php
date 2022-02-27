<?php
use Symfony\Component\Yaml\Yaml;

class ScanDockerPorts {

    public array $scanLocations;
    public array $services;
    public array $config;

    public function __construct($config) {

        $this->config = $config;
        $this->scanLocations = $config['projectLocations'];
        $this->services = $config['services'];
    }

    /**
     * Getting the port from docker-compose files.
     *
     * @return array
     */
    public function getPorts():array {

        $output = [];

        if (!empty($this->getDockerComposeFiles())) {

            foreach ($this->getDockerComposeFiles() as $dockerComposeFile) {

                $dockerComposeContent = $this->getServicesFromFile($dockerComposeFile);

                foreach ($this->services as $serviceKey => $service) {

                    $serviceResult[$serviceKey] = 0;
                    if ($port = $this->getServicePort($dockerComposeContent, $service)) {
                        $serviceResult[$serviceKey] = (int)$port;
                    }
                }

                if (! empty($serviceResult)) {
                    $output[$dockerComposeFile] = $serviceResult;
                }
            }
        }

        return $output;
    }

    public function getNextHighestPorts($ports):array {

        $output = ['Next Highest'];

        foreach ($this->services as $serviceName => $service) {

            $port = (int)max(array_column($ports, $serviceName));
            if ($port > 0) {
                $output[] = $port +1;
            }
            else {
                $output[] = '-';
            }


        }

        return $output;
    }

    /**
     * Get all the services inside this docker-compose file.
     *
     * @param $file
     * @return array|mixed
     */
    private function getServicesFromFile($file) {

        $content = Yaml::parse(file_get_contents($file));

        if (isset($content['services'] )) {
            return $content['services'];
        }

        return [];
    }

    /**
     * Get the port for this service
     *
     * @param $services
     * @param $filter
     * @return string
     */
    private function getServicePort($services, $filter):string {

        foreach ($services as $serviceName => $service) {

            if (in_array($serviceName, $filter) && isset($service['ports'])) {

                $port = explode(':', $service['ports'][0]);

                return $port[0];
            }
        }

        return '-';
    }

    /**
     * Get docker-compose.json files.
     *
     * @return array|false
     */
    private function getDockerComposeFiles(): bool|array {

        static $dockerComposeFiles;

        if (empty($dockerComposeFiles)) {

            $dockerComposeFiles = [];

            foreach ($this->scanLocations as $location) {

                $path = realpath($this->config['rootDir']).'/{'.$location.'/docker-compose.yml}';

                if (! empty(glob($path, GLOB_BRACE))) {
                    $dockerComposeFiles = array_merge($dockerComposeFiles, glob($path, GLOB_BRACE));
                }
            }
        }

        return $dockerComposeFiles;
    }
}