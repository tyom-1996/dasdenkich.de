<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";

class ShortUrl {
    
    public $mysqli;
   
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
        $this->index();
    }
    
    public function index()
    {
        if(isset($_GET['l']) && !empty($_GET['l'])) 
        {
           
            $token  = $_GET['l'];
            $query  = "SELECT * FROM url_shorten WHERE short_code = '{$token}' ";
            $result = $this->mysqli->query($query); 
            $res_content = $result->fetch_assoc();
            
            if($result->num_rows >= 1) {
                $this->location($res_content['url']);
            } else {
                $this->location('/');
            }
        }
    }
    
    public function location($url)
    {
        header("location:".$url);
    }
    
    
}    


$ShortUrl = new ShortUrl($mysqli);
