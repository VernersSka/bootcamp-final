<?php
class DB
{
  protected $conn;
  protected $table_name;

  public function __construct($table_name) {
    $this->table_name = $table_name;

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "bootcamp_todo_list";

    $this->conn = new mysqli($servername, $username, $password, $dbname);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  public function __destruct() {
    $this->conn->close();
  }

  public function getAll() {
  $sql = "SELECT * FROM `$this->table_name`";
  $result = $this->conn->query($sql);

  if ($result === false) {
    return [
      'status' => false
    ];
  }
  elseif ($result->num_rows > 0) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $entities = [];
    foreach ($data as $row) {
      $id = $row['id'];
      unset($row['id']);
      $entities[$id] = $row;
    }

      return [
        'entities' => $entities,
        'status' => true
      ];

    } else 
    {
      return [
        'entities' => [],
        'status' => true
      ];
    }
  }

  /**
   * paņem no DB jau aizņemtos laikus
   * 
   */
  // šo izmantotu, lai vienam laikam nevar pieteikties vairāki lietotāji, bet izvēlētais date-picker nepiedāvā 
  // tik smalku laika atlasi. pagaidām labākas alternatīvas neatrodu

  
  // public function getSelectData($column) {
  //   $sql = "SELECT id, $column FROM `$this->table_name`";
  //   $result = $this->conn->query($sql);
    
  //   if ($result->num_rows > 0) {
  //     $data = $result->fetch_all(MYSQLI_ASSOC);
  //     $times_taken = [];
  //     foreach ($data as $row) {
  //       $id = $row['id'];
  //       unset($row['id']);
  //       $times_taken[$id] = $row['time'];
  //     }
  //   }
    
  //   return $times_taken;
  // }


  /**
   * @param array $entity = [
   *      'table_column' => value
   *      ...
   * ]
   * 
   * 
   * @return array [
   *  'status' => true,
   *  'entity' => [
   *      'id' => id_number
   *      'table_column' => value
   *      ...
   *  ]
   * ]
   * 
   * or
   * 
   * [
   *  'status' => false,
   *  'message' => message
   * ]
   */
  public function add(array $entity) {
    $columns = '';
    $values = '';

    $elements_left = count($entity);
    foreach ($entity as $column => $value) {
      $columns .= $column;

      $value = $this->conn->real_escape_string($value);
      $values .= "'$value'";

      if ($elements_left-- > 1) {
        $columns .= ', ';
        $values .= ', ';
      }
    }

    $sql = "INSERT INTO `$this->table_name` ($columns)
    VALUES ($values)";

    if ($this->conn->query($sql) === true) {
      $id = $this->conn->insert_id;

      $entity['id'] = $id;

      return [
        'status' => true,
        'entity' => $entity
      ];
    } else {
      return [
        'status' => false,
        'message' => "Error: " . $sql . "<br>" . $this->conn->error
      ];
    }
  }
}