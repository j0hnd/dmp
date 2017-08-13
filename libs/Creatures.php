<?php
require_once 'Database.php';
require_once 'Crimes.php';
require_once 'Notes.php';


class Creatures extends Database
{
    private $crimes;
    private $notes;
    protected $cn;


    public function __construct($connection)
    {
        parent::__construct();
        $this->cn = $connection;

        $this->crimes = new Crimes($this->cn);
        $this->notes = new Notes($this->cn);
    }

    public function get($creature_id = null, $criteria = null)
    {
        try {
            $data = null;

            if (is_null($creature_id) and is_null($criteria)) {
                $sql = "SELECT
                          id,
                          creature_name,
                          gender,
                          race,
                          place_of_birth,
                          date_of_birth,
                          ever_carried_the_ring,
                          enslaved_by_sauron,
                          created_at
                        FROM creatures 
                        WHERE is_deceased = 0
                        ORDER BY id DESC";
            } elseif (is_null($creature_id) and !is_null($criteria)) {
                $where_sql = "";

                if ($criteria != '') {
                    foreach ($criteria as $key => $_criteria) {
                        if ($key == 'no_of_crimes') {
                            $where_sql .= "(SELECT count(cr1.id) no_of_crimes 
                                            FROM creatures c1 
                                             LEFT JOIN crimes cr1 ON cr1.creature_id = c1.id 
                                            WHERE cr1.creature_id = c.id 
                                            GROUP BY cr1.creature_id) = '{$_criteria}' AND ";
                        } else {
                            $where_sql .= "{$key} = '{$_criteria}' AND ";
                        }
                    }

                    $where_sql = trim($where_sql, 'AND ');

                    $sql = sprintf("SELECT
                                          c.id,
                                          c.creature_name,
                                          c.gender,
                                          c.race,
                                          c.place_of_birth,
                                          c.date_of_birth,
                                          c.ever_carried_the_ring,
                                          c.enslaved_by_sauron,
                                          c.created_at 
                                       FROM creatures c
                                         LEFT JOIN crimes cr ON cr.creature_id = c.id
                                       WHERE %s AND c.is_deceased = 0
                                       ORDER BY id DESC",
                        $where_sql);
                }

            } else {
                $creature_id = (int)$creature_id;
                $sql = sprintf("SELECT
                                          id,
                                          creature_name,
                                          gender,
                                          race,
                                          place_of_birth,
                                          date_of_birth,
                                          ever_carried_the_ring,
                                          enslaved_by_sauron,
                                          created_at 
                                       FROM creatures 
                                       WHERE id = %d AND is_deceased = 0
                                       ORDER BY id DESC",
                    $creature_id);
            }

            $query = mysqli_query($this->cn, $sql);

            if ($query) {
                $i = 0;
                while ($result = mysqli_fetch_assoc($query)) {
                    $data[$i]['id'] = $result['id'];
                    $data[$i]['creature_name'] = $result['creature_name'];
                    $data[$i]['gender'] = $result['gender'];
                    $data[$i]['race'] = $result['race'];
                    $data[$i]['place_of_birth'] = $result['place_of_birth'];
                    $data[$i]['date_of_birth'] = $result['date_of_birth'];
                    $data[$i]['ever_carried_the_ring'] = $result['ever_carried_the_ring'];
                    $data[$i]['enslaved_by_sauron'] = $result['enslaved_by_sauron'];
                    $data[$i]['crimes'] = $this->crimes->get($result['id']);
                    $data[$i]['notes'] = $this->notes->get($result['id']);
                    $data[$i]['created_at'] = $result['created_at'];

                    $i++;
                }
            }

        } catch (Exception $e) {
            throw $e;
        }

        return $data;
    }

    public function get_place_of_births()
    {
        try {
            $place_of_births = null;

            $sql = "SELECT place_of_birth FROM creatures GROUP BY place_of_birth ORDER BY place_of_birth ASC;";

            $query = mysqli_query($this->cn, $sql);

            if ($query) {
                $i = 0;
                while ($row = mysqli_fetch_assoc($query)) {
                    $place_of_births[$i] = $row['place_of_birth'];
                    $i++;
                }
            }

        } catch (Exception $e) {
            throw $e;
        }

        return json_encode($place_of_births);
    }

    public function save(array $data, $id = null)
    {
        return parent::save($this->cn, $data, 'creatures', $id);
    }
}
