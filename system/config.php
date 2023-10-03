<?php

     session_start();
     ob_start('compress');
     date_default_timezone_set("Asia/Baku");


     try {
          $db = new PDO("mysql:host=localhost;dbname=b2b;charset=utf8;","root","");
          $db->query('SET CHARACTER SET utf8');
          $db->query('SET NAMES utf8');
     } catch (PDOException $e) {
          print_r($e->getMessage());
     }


     $query = $db->prepare('SELECT * FROM setting LIMIT :lim');
     $query->bindValue(':lim',(int) 1, PDO::PARAM_INT);
     $query->execute();
     
     if($query->rowCount()) {
          $row = $query->fetch(PDO::FETCH_OBJ);
          $site_url = $row->site_url;
          define('site',$site_url);
          define('siteTitle',$row->site_title);
     }


?>