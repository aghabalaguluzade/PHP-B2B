<?php 
     require_once './system/function.php';
     require_once './route/web.php';

     class SellersController {
          public function register() {
               if($_POST) {
                    $seller_name = post('seller_name');
                    $seller_email = post('seller_email');
                    $seller_password = post('seller_password');
                    $seller_confirm_password = post('seller_confirm_password');
                    $seller_phone = post('seller_phone');
                    $seller_tax = post('seller_tax');
                    $seller_tax_administration = post('seller_tax_administration');
          
                    $seller_code = uniqid();
                    $crypto = sha1(md5($seller_password));
          
                    if(!$seller_name || !$seller_email || !$seller_password || !$seller_confirm_password || !$seller_phone || !$seller_tax || !$seller_tax_administration) {
                         echo "empty";
                    }else if(!filter_var($seller_email,FILTER_VALIDATE_EMAIL)) {
                         echo "format";
                    }else {
                         if($seller_password != $seller_confirm_password) {
                              echo "match";
                         }else {
                              $already = $GLOBALS['db']->prepare("SELECT seller_email FROM sellers WHERE seller_email=:s");
                              $already->execute([":s"=>$seller_email]);
                              if($already->rowCount()) {
                                   echo "already";
                              }else {
          
                                   $data = [
                                        'seller_name'=>$seller_name,
                                        'seller_email'=>$seller_email,
                                        'seller_password'=>$crypto,
                                        'seller_code'=>$seller_code,
                                        'seller_tax'=>$seller_tax,
                                        'seller_tax_administration'=>$seller_tax_administration,
                                        'seller_phone'=>$seller_phone,
                                        'seller_ip' => ip()
                                   ];
          
                                   $result = create("sellers",$data);
          
                                   if($result->rowCount() > 0) {
                                        echo "ok";
                                   }else {
                                        echo "error";
                                   };
                              }
                         }
                    }
               }
          }

          public function registera() {
               var_dump('aas');
          }
      }


     

?>