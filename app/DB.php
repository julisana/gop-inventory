<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-08
 * Time: 17:00
 */

namespace GOP\Inventory;

use PDO;
use Exception;
use PDOStatement;

/**
 * Class DB
 *
 * A very simple PDO wrapper. Only supports simple SELECT and UPDATE queries
 */
class DB
{
    /**
     * @var string
     */
    private $hostname;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $database;
    /**
     * @var string
     */
    private $table;
    /**
     * @var PDO
     */
    protected $connection;
    /**
     * The previous query performed. For debugging, if there was an issue.
     *
     * @var PDOStatement
     */
    protected $previousQuery;

    /**
     * @var array
     */
    protected $fields = [ '*' ];
    /**
     * @var array
     */
    protected $where;
    /**
     * @var int
     */
    protected $start;
    /**
     * @var int
     */
    protected $limit;
    /**
     * @var string
     */
    protected $orderBy;

    /**
     * DB constructor.
     *
     * Set the database connection data on the object
     */
    public function __construct()
    {
        $this->hostname = DB_HOSTNAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_DATABASE;

        $this->connect();
    }

    /**
     * Reset all the query values
     *
     * @return $this
     */
    protected function reset()
    {
        $this->table( '' )
            ->fields()
            ->where()
            ->start()
            ->limit()
            ->orderBy();

        return $this;
    }

    /**
     * @param $table
     *
     * @return $this
     */
    public function table( $table )
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function fields( array $fields = [ '*' ] )
    {
        //If the function was passed and empty value and not the default, set it to the default
        if ( empty( $fields ) ) {
            $fields = [ '*' ];
        }

        //If the function wasn't passed an array, make it an array
        if ( !is_array( $fields ) ) {
            $fields = [ $fields ];
        }

        $this->fields = $fields;

        return $this;
    }

    /**
     * Values must be passed as an associative array, where the field is the key
     *
     * @param array $where
     *
     * @return $this
     */
    public function where( $where = [] )
    {
        if ( empty( $where ) || !is_array( $where ) ) {
            return $this;
        }

        $this->where = $where;

        return $this;
    }

    /**
     * @param string|int $start
     *
     * @return $this
     */
    public function start( $start = '' )
    {
        if ( !is_numeric( $start ) || empty( $start ) ) {
            return $this;
        }

        $this->start = $start;

        return $this;
    }

    /**
     * @param string|int $limit
     *
     * @return $this
     */
    public function limit( $limit = '' )
    {
        if ( !is_numeric( $limit ) || empty( $limit ) ) {
            return $this;
        }

        $this->limit = $limit;

        return $this;
    }

    /**
     * @param string $orderBy
     *
     * @return DB
     */
    public function orderBy( $orderBy = '' )
    {
        if ( empty( $orderBy ) ) {
            return $this;
        }

        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Create the connection to the MySQL Database
     */
    public function connect()
    {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->hostname . ';dbname=' . $this->database,
                $this->username,
                $this->password,
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
            );
        } catch ( Exception $exception ) {
            /** @todo Do something with connection error */

            die( $exception->getMessage() );
        }
    }

    /**
     * Perform a MySQL Query
     *
     * @param string $statement
     * @param array  $options
     *
     * @return PDOStatement|bool
     */
    protected function query( $statement, $options = [] )
    {
        try {
            $query = $this->connection->prepare( $statement );
            $this->previousQuery = $this->connection->prepare( $statement );

            foreach ( $options as $key => $value ) {
                $query->bindParam( $key, $value );
                $this->previousQuery->bindParam( $key, $value, PDO::PARAM_STR );
            }

            if ( $query->execute() ) {
                return $query;
            }
        } catch ( Exception $exception ) {
            die( $exception->getMessage() );
        }

        return false;
    }

    /**
     * Perform an INSERT query against the MySQL Database
     *
     * @throws Exception
     */
    public function insert()
    {
        $options = [];

        $query = 'insert into ' . $this->table;

        if ( empty( $this->fields ) ) {
            throw new Exception( 'No data has been set for insert.' );
        }

        $isAssociative = false;
        //Try and determine if fields is an assoc array. This isn't foolproof, but it's something
        //https://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential/4254008#4254008
        if ( count( array_filter( array_keys( $this->fields ), 'is_string' ) ) > 0 ) {
            $isAssociative = true;
        }
        $keys = array_keys( $this->fields );

        //Add in the field names if they have been supplied
        if ( $isAssociative ) {
            $query .= ' (';
            $last = end( $keys );
            foreach ( $keys as $field ) {
                $query .= ' ' . $field;

                if ( $field != $last ) {
                    $query .= ',';
                }
            }

            $query .= ')';
        }

        //And the table values
        $query .= ' VALUES (';
        $last = end( $keys );
        foreach ( $this->fields as $field => $value ) {
            $query .= ' "' . $value . '"';
            if ( $field != $last ) {
                $query .= ',';
            }
        }

        $query .= ')';

        $results = $this->query( $query, $options );
        $this->reset();

        return $results;
    }

    /**
     * Perform a SELECT query against the MySQL Database
     *
     * @return array
     */
    public function select()
    {
        $options = [];

        //Build the query
        $query = 'select ' . implode( ',', $this->fields ) . ' from ' . $this->table;

        if ( !empty( $this->where ) ) {
            $query .= ' where';

            $keys = array_keys( $this->where );
            $last = end( $keys );
            foreach ( $this->where as $field => $value ) {
                $query .= ' ' . $field . '="' . $value . '"';

                if ( $field != $last ) {
                    $query .= ' and';
                }
            }
        }

        if ( !empty( $this->limit ) ) {
            $query .= ' limit';
            if ( !empty( $this->start ) ) {
                $query .= ' ' . $this->start . ',';
            }

            $query .= ' ' . $this->limit;
        }

        if ( !empty( $this->orderBy ) ) {
            $query .= ' order by ' . $this->orderBy;
        }

        try {
            $results = $this->query( $query, $options );
        } catch ( Exception $exception ) {
            /** @todo Do something with this query error */

            die( $exception->getMessage() );
        }

        $this->reset();
        if ( !$results instanceof PDOStatement ) {
            return [];
        }

        return $results->fetchAll( PDO::FETCH_ASSOC );
    }

    /**
     * Perform an UPDATE query against the MySQL Database
     *
     * @return int
     *
     * @throws Exception
     */
    public function update()
    {
        $options = [];

        if ( empty( $this->fields ) ) {
            throw new Exception( 'No data have been set for update.' );
        }

        $query = 'update ' . $this->table . ' set';

        $keys = array_keys( $this->fields );
        $last = end( $keys );
        foreach ( $this->fields as $field => $value ) {
            $query .= ' ' . $field . '="' . $value . '"';
            if ( $field != $last ) {
                $query .= ',';
            }
        }

        if ( !empty( $this->where ) ) {
            $query .= ' where';

            $keys = array_keys( $this->where );
            $last = end( $keys );
            foreach ( $this->where as $field => $value ) {
                $query .= ' ' . $field . '="' . $value . '"';

                if ( $field != $last ) {
                    $query .= ' and';
                }
            }
        }

        if ( !empty( $this->limit ) ) {
            $query .= ' limit ' . $this->limit;
        }

        $result = $this->query( $query );

        $this->reset();

        return $result->rowCount();
    }

    /**
     * Perform a DELETE query against the MySQL Database
     *
     * @return int
     */
    public function delete()
    {
        return 0;
    }
}
