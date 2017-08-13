<?php

class Database
{
    private $db_info;


    public function __construct()
    {
        if (!file_exists('config/database.php')) {
            set_exception_handler(array($this, 'exception_handler'));
            throw new Exception("Database Error: Missing database config file");
        }

        $this->db_info = include('config/database.php');
    }

    public function exception_handler($exception)
    {
        echo "Database Error: ". $exception->getMessage() ."\n";
    }

    public function connect()
    {
        try {

            $cn = mysqli_connect($this->db_info['host'], $this->db_info['user'], $this->db_info['password'], $this->db_info['database'], $this->db_info['port']);

            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }

        } catch (Exception $e) {
            throw $e;
        }

        return $cn;
    }

    protected function save($link, array $data, $table, $id = null)
    {
        try {

            if (!count($data)) {
                set_exception_handler(array($this, 'exception_handler'));
                throw new Exception("Database Error: Missing details");
            }

            $field_str = '';
            $value_str = '';

            if (is_null($id)) {
                foreach ($data as $key => $item) {
                    $field_str .= "{$key}, ";

                    $value_str .= "'".mysqli_real_escape_string($link, $item)."', ";
                }

                $field_str = trim($field_str, ', ');

                $value_str = trim($value_str, ', ');

                $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table, $field_str, $value_str);
            } else {
                foreach ($data as $key => $value) {
                    $value_str .= "{$key} = '".mysqli_real_escape_string($link, $value)."', ";
                }

                $value_str = trim($value_str, ', ');

                $id = (int)$id;
                $sql = sprintf("UPDATE %s SET %s WHERE id = %d", $table, $value_str, $id);
            }

            $response = mysqli_query($link, $sql);

        } catch (Exception $e) {
            throw $e;
        }

        return $response;
    }
}
