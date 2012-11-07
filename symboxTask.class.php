<?php

class symboxTask extends twodialogBaseTask
{
    protected function configure()
    {
      parent::configure();

      $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      ));

      $this->namespace        = '';
      $this->name             = 'symbox';
      $this->briefDescription = 'A sandbox for the product models.';
      $this->detailedDescription = <<<EOF
The [symbox|INFO] task takes a yml file and lets you work with the product models dynamically.
Call it with:

  [php symfony symbox|INFO]
EOF;
      $this->exit = false;
      $this->assignments = array();
    }

    /**
     * Run the task
     * @param array $arguments
     * @param array $options
     * @return null
     * @throws Exception
     */
    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        echo "Lets play!\n";
        $this->configureDatabase( $arguments, $options );


        while(!$this->exit)
        {
            $this->input = readline("> ");
            readline_add_history($input);

            #preg_match('/([a-zA-z]+[a-zA-Z0-9]*)::([a-zA-Z0-9]+)\((.*)\)/', $input, $match);
            $this->DetectNewObjectInstance();

            var_dump($this->assignments);
        }
        // End
    }


    private function DetectNewObjectInstance()
    {
        $assignment = $this->DetectAssignment();

        # Object Instantion
        $match = array();
        preg_match('/ *new *([a-zA-Z]+[a-zA-Z0-9]*)\((.*)\)/', $this->input, $match);
        var_dump($match);

        if(count($match) == 3)
        {
            $class = $match[1];
            $args = $this->parseArgs($match[2]);

            $reflectClass = new ReflectionClass($class);
            $object = $reflectClass->newInstanceArgs($args);
        }

        if($assignment && isset($object))
        {
            $this->assignments[$variable] = $object;
        }
    }

    private function DetectAssignment()
    {
        # Assignment
        $match = array();
        preg_match('/\$([a-zA-Z0-9]+) *=(.*)/', $this->input, $match);
        var_dump($match);

        if(count($match) == 3)
        {
            $this->input = trim($match[2]);
            $this->assignments[$match[1]] = null;

            return true;
        }

        return false;
    }

    private function parseArgs($args = '')
    {
        if($args == '')
            $args = array();
        else
            $args = preg_split(', *', $args);

        return $args;
    }
}
