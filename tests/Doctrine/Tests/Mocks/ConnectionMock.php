<?php

declare(strict_types=1);

namespace Doctrine\Tests\Mocks;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use function is_string;

/**
 * Mock class for Connection.
 */
class ConnectionMock extends Connection
{
    /** @var mixed */
    private $fetchOneResult;

    /** @var \Exception|null */
    private $fetchOneException;

    /** @var Statement|null */
    private $queryResult;

    /** @var DatabasePlatformMock */
    private $platformMock;

    /** @var int */
    private $lastInsertId = 0;

    /** @var array */
    private $inserts = [];

    /** @var array */
    private $executeUpdates = [];

    /**
     * @param array              $params
     * @param Driver             $driver
     * @param Configuration|null $config
     * @param EventManager|null  $eventManager
     */
    public function __construct(array $params, $driver, $config = null, $eventManager = null)
    {
        $this->platformMock = new DatabasePlatformMock();

        parent::__construct($params, $driver, $config, $eventManager);

        // Override possible assignment of platform to database platform mock
        $this->platform = $this->platformMock;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return $this->platformMock;
    }

    /**
     * {@inheritdoc}
     */
    public function insert($tableName, array $data, array $types = [])
    {
        $this->inserts[$tableName][] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function executeUpdate($query, array $params = [], array $types = [])
    {
        $this->executeUpdates[] = ['query' => $query, 'params' => $params, 'types' => $types];
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($seqName = null)
    {
        return $this->lastInsertId;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn($statement, array $params = [], $colnum = 0, array $types = [])
    {
        if ($this->fetchOneException !== null) {
            throw $this->fetchOneException;
        }

        return $this->fetchOneResult;
    }

    /**
     * {@inheritdoc}
     */
    public function query() : Statement
    {
        return $this->queryResult;
    }

    /**
     * {@inheritdoc}
     */
    public function quote($input, $type = null)
    {
        if (is_string($input)) {
            return "'" . $input . "'";
        }
        return $input;
    }

    /* Mock API */

    /**
     * @param mixed $fetchOneResult
     *
     */
    public function setFetchOneResult($fetchOneResult)
    {
        $this->fetchOneResult = $fetchOneResult;
    }

    public function setFetchOneException(?\Exception $exception = null)
    {
        $this->fetchOneException = $exception;
    }

    /**
     * @param AbstractPlatform $platform
     *
     */
    public function setDatabasePlatform($platform)
    {
        $this->platformMock = $platform;
    }

    /**
     * @param int $id
     *
     */
    public function setLastInsertId($id)
    {
        $this->lastInsertId = $id;
    }

    public function setQueryResult(Statement $result)
    {
        $this->queryResult = $result;
    }

    /**
     * @return array
     */
    public function getInserts()
    {
        return $this->inserts;
    }

    /**
     * @return array
     */
    public function getExecuteUpdates()
    {
        return $this->executeUpdates;
    }

    public function reset()
    {
        $this->inserts      = [];
        $this->lastInsertId = 0;
    }
}
