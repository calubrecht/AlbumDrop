<?php

class dbHolder
{
  private $dbInited = false;
  private $db;
  public $error;

  public function getDB()
  {
    $this->dbInit();
    return $this->db;
  }

  public function queryAll($query, $arguments)
  {
    try
    { 
      unset($this->error);
      $this->dbInit();
      $params = is_array($arguments) ? $arguments : array($arguments);
      $statement = $this->db->prepare($query);
      if (!$statement)
      {
        $this->error = "PrepareStatement Error:" + $this->db->errorInfo();
        return false;
      }
      if($statement->execute($params))
      {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
      }
      $this->error = "ExecuteStatement Error:" + $this->db->errorInfo();
      return false;
		}
    catch (PDOException $e) {
			$this->error = "PDOException: ".$e->getMessage();
      return false;
		}
  }
  
  public function queryOneRow($query, $arguments)
  {
    $result = $this->queryAll($query, $arguments);
    if (!$result)
    {
      if (!isset($this->error))
      {
        $this->error = "No rows returned.";
      }
      return false;
    }
    return $result[0];
  }
  
  public function queryOneColumn($query, $column, $arguments)
  {
    $result = $this->queryOneRow($query, $arguments);
    if (!$result)
    {
      return false;
    }
    return $result[$column];
  }

  public function dbInit()
  {
    global $AD_CONFIG;
    if ($this->dbInited)
    {
      return;
    }
		try
    {
		  $host = $AD_CONFIG["DB_HOST"];
      $dbName = $AD_CONFIG["DB_NAME"];
      $dbport = $AD_CONFIG["DB_PORT"];
      $dbuser = $AD_CONFIG["DB_USER"];
      $dbpass = $AD_CONFIG["DB_PASSWORD"];
			$pdo_connect = 'mysql:host='.$host.';dbname='.$dbName;
		  $pdo_connect .= ';port='.$dbport;
			$this->db = new PDO($pdo_connect, $dbuser, $dbpass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
      $this->dbInited = true;

		} catch (PDOException $e) {
			$this->error = "PDOException: ".$e->getMessage();
		}

  }
}

$db = new dbHolder();
?>
