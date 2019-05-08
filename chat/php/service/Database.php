<?php

class Database extends Service
{
    // Constants

    const DEFAULT_PORT = 3306;

    // Fields

    private $db,
            $config,
            $logger,
            $stats,
            $lastQuery;

    // Constructor

    public function onRegister()
    {
        parent::onRegister();

        // -----

        $this->config = $this->get('config');
        $this->logger = $this->get('logger');
        $this->stats  = $this->get('stats');
        $this->reconnect();
    }

    // Methods

    public function connect($dsn, $user, $pass)
    {
        try
        {
            $this->db = new PDO($dsn, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch(Exception $e)
        {
            // Don't throw exceptions if application is not yet installed

            $installed = isset($this->config->data['appSettings']['installed']) && $this->config->data['appSettings']['installed'];

            // Log

            $this->get('logger')->error($e->getMessage());

            if($installed)
            {
                throw $e;
            }

            return false;
        }

        return !empty($this->db);
    }

    public function reconnect()
    {
        $installed  = isset($this->config->data['appSettings']['installed']) && $this->config->data['appSettings']['installed'];
        $connection = $installed ? $this->config->data['dbConnection'] : $this->config->data['dbConnectionRaw'];

        return $this->connect($connection, $this->config->data['dbUser'], $this->config->data['dbPassword']);
    }

    public function execute($q, $params = null)
    {
        $this->ensureValidConnection();

        // Split all queries into separate ones

        $queries = $this->splitQueries($q);

        // Execute all queries and return the result of the last one

        $result = false;

        try
        {
            foreach($queries as $q)
            {
                $this->lastQuery = $q;

                $statement = $this->db->prepare($q);

                if($statement)
                {
                    $result = $statement->execute($params);

                    $this->stats->inc('db_queries');
                }
            }

            return $result;
        }
        catch(Exception $e)
        {
            // Log

            $this->logger->error($e->getMessage());

            return false;
        }

        return false;
    }

    public function query($q, $params = null)
    {
        $this->ensureValidConnection();

        // Split all queries into separate ones

        $queries = $this->splitQueries($q);

        // Execute all queries and return the result of the last one

        $result = false;

        try
        {
            foreach($queries as $q)
            {
                $this->lastQuery = $q;

                $statement = $this->db->prepare($q);

                if($statement)
                {
                    $statement->execute($params);

                    $result = $statement->fetchAll();

                    $this->stats->inc('db_queries');
                }
            }

            return $result;
        }
        catch(Exception $e)
        {
            // Log

            $this->logger->error($e->getMessage());

            return false;
        }

        return false;
    }

    public function queryOne($q, $params = null)
    {
        $this->ensureValidConnection();

        try
        {
            $this->lastQuery = $q;

            $statement = $this->db->prepare($q);

            if($statement)
            {
                $statement->execute($params);

                $this->stats->inc('db_queries');

                return $statement->fetch();
            }
        }
        catch(Exception $e)
        {
            // Log

            $this->logger->error($e->getMessage());

            return false;
        }

        return false;
    }

    public function lastInsertId()
    {
        $this->ensureValidConnection();

        return intval($this->db->lastInsertId());
    }

    public function getLastQuery()
    {
        return $this->lastQuery;
    }

    public function getTables()
    {
        $this->ensureValidConnection();

        $result = array();
        $tables = $this->query('SHOW TABLES');

        $this->stats->inc('db_queries');

        if($tables)
        {
            foreach($tables as $tableInfo)
            {
                $result[] = $tableInfo[0];
            }
        }

        return $result;
    }

    protected function splitQueries($q)
    {
        // Replace \r\n with \n

        $nlFixed = str_replace("\r\n", "\n", $q);

        // Remove comments and trim

        $pureQueries = trim(preg_replace(array(

            "/\/\*.*(\n)*.*(\*\/)?/",
            "/\s*--.*\n/",
            "/\s*#.*\n/"

        ), "\n", $nlFixed));

        // Split

        $queries = explode(";\n", $pureQueries);

        // Remove white space

        foreach($queries as &$query)
        {
            $query = trim($query);
        }

        return $queries;
    }

    protected function ensureValidConnection()
    {
        if(!$this->db)
        {
            throw new Exception('Trying to use database with an invalid connection.');
        }
    }
}

?>
