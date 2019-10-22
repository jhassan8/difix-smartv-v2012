<?php
class db
{
  protected $cn;
  protected $result;

  public function conect(){
    $this->cn = new mysqli('localhost', 'root', '', 'difix');
    mysqli_set_charset($this->cn, "utf8");
    if($this->cn->connect_errno)
      return false;
    return true;
  }

  public function getConnection() {
    return $this->cn;
  }

  public function disable_commit() {
    mysqli_autocommit($this->cn, FALSE);
  }

  public function commit() {
    $this->cn->commit();
  }

  public function rollback(){
     $this->cn->rollback();
  }

  public function disconect(){
    if($this->result != null)
      $this->result->free();
    $this->cn->close();
  }

  public function insert_not_exist($query) {
    echo '<br />execute = :'. $query;
    if(!$this->cn->query($query))
      return -1;
    return $this->cn->insert_id;
  }

  public function execute($query) {
    echo '<br />execute: '. $query;
    if(!$this->result = $this->cn->query($query))
      return;
    return @$this->result->fetch_all(MYSQLI_ASSOC)[0];
  }

  // Login functions
  public function login($mac,$user,$pass){
    if(!$this->result = $this->cn->query("CALL login('$mac','$user','$pass')"))
      return false;
    return $this->result->fetch_all(MYSQLI_ASSOC)[0];
  }

  public function start($mac,$token){
    if(!$this->result = $this->cn->query("CALL start('$mac','$token')"))
      return false;
    return $this->result->fetch_all(MYSQLI_ASSOC)[0];
  }

  public function token($token){
    if(!$this->result = $this->cn->query("CALL token('$token')"))
      return false;
    return $this->result->fetch_all(MYSQLI_ASSOC)[0];
  }

}
?>
