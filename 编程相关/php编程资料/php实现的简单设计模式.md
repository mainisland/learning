#php �������ģʽ

1������ģʽ

    class config {
        private static $_instance;
        
        private function __construct() {
            echo "error!";
        }
        //����һ��__clone���󣬷�ֹ���󱻿�¡
        public function __clone() {
            echo "error";
        }
        
        //����ģʽ
        public static function getInstance {
            if(!(self::$_instance instanceof self)) {
                self::$_instance = new self;
            }
            return self::$_instance;
        } 
    }
    $config = config::getInstance();
2������ģʽ

    class Rectangle {
        private $width;
        private $height;
        
        public function __construct($width,$height) {
            $this->width = $width;
            $this->height = $height;
        }
        
        //��������
        public function create() {
            return $this->width+$this->height;
        }
    }

    class Rectanglefactory {
        public static function createfactory($width,$height) {
            return new Rectangle($width,$height);
        }
    }

    $Rectanglefactory = Rectanglefactory::createfactory();
    $Rectanglefactory->create();

3������ģʽ��

    interface OutputInterface {
        public function load();
    }
    
    class SerializedArrayOutput implements OutputInterface
    {
        public function load()
        {
            return serialize($arrayOfData);
        }
    }

    class JsonStringOutput implements OutputInterface
    {
        public function load()
        {
            return json_encode($arrayOfData);
        }
    }

    class ArrayOutput implements OutputInterface
    {
        public function load()
        {
            return $arrayOfData;
        }
    }

    <?php
    class client {
        private $output;
        
        public function setOutput(OutputInterface  $outputType) {
            $this->output = $outputType;
        }

        public function loadOutput() {
            return $this->output->load();
        }
    }

    $client = new client();
    $client->setOutput(new ArrayOutput());
    $data = $client->loadOutput();
    
    $client->setOutput(new JsonStringOutput());
    $data = $client->loadOutput();