<?php
require_once 'Database.php';

class Notes extends Database
{
    protected $cn;


    public function __construct($connection)
    {
        $this->cn = $connection;
    }

    public function get($creature_id)
    {
        try {
            $data = null;

            $sql = sprintf("SELECT * FROM notes WHERE creature_id = %d ORDER BY created_at DESC", $creature_id);

            $query = mysqli_query($this->cn, $sql);

            if ($query) {
                $i = 0;
                while ($result = mysqli_fetch_assoc($query)) {
                    $data[$i]['id'] = $result['id'];
                    $data[$i]['notes'] = $result['notes'];
                    $data[$i]['created_at'] = $result['created_at'];

                    $i++;
                }
            }

        } catch (Exception $e) {
            throw $e;
        }

        return $data;
    }

    public function save(array $data, $id = null)
    {
        return parent::save($this->cn, $data, 'notes', $id);
    }
}