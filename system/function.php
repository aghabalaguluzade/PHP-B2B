<?php 

    require_once 'config.php'; 
    require_once './route/web.php';

    function create($table, $data) {
        $keys = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        
        $stmt = $GLOBALS['db']->prepare($sql);

        $stmt->execute(array_values($data));

        return $stmt;
    }

    function update($table, $id, $data) {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "$key=:$key,";
        }
        $fields = rtrim($fields, ',');
        $sql = "UPDATE $table SET $fields WHERE id=:id";
        
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        
        return $stmt;
    }

    function show($table) {
        $sql = "SELECT * FROM $table";
        
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function destroy($table, $id) {
        $sql = "DELETE FROM $table WHERE id=:id";
        
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    function all($table) {
        $stmt = $GLOBALS['db']->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function select($table, $columns = "*", $where = [], $orderBy = null, $limit = null) {
        $sql = "SELECT $columns FROM $table";

        // Add WHERE clause if there are conditions
        if (!empty($where)) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "$key = :$key";
            }
            $sql .= implode(" AND ", $conditions);
        }

        // Add ORDER BY clause if there is a specified order
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        // Add LIMIT clause if there is a specified limit
        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        $stmt = $GLOBALS['db']->prepare($sql);

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function urlIs($value) {
        return $_SERVER['REQUEST_URI'] === $value;
    }

    function abort($code) {
        http_response_code($code);
        require "./{$code}.php";
        die();
    }

    function compress($buffer){
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
        return $buffer;
    }

    function dt($par,$status = false){
        if($status == false){
            return date('d.m.Y',strtotime($par));
        }else{
            return date('H:i',strtotime($par));
        }
    }

    function alert($message,$alert){
        echo '<div class="alert alert-'.$alert.'">'.$message.'</div>';
    }


    function post($par){
        return strip_tags(trim($_POST[$par]));
    }

    function get($par){
        return strip_tags(trim($_GET[$par]));
    }

    function go($url, $time = false){

        if($time == false){
            return header('Location:'.$url);
        }else{
        // return header('refresh:'.$time.':url='.$url);
            return header('refresh:'.$time.';url='.$url);
        }

    }


    function mobilecontrol() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|iemobile|ip(hone|od)|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)|iris|mini|mobi|palm|symbian|vodafone|wap|windows (ce|phone)|xda|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }


    function location(){

        $loc =  "http://localhost".$_SERVER['REQUEST_URI'];
        return $loc;
    }


    function ip(){

        if(getenv("HTTP_CLIENT_IP")){
            $ip = getenv("HTTP_CLIENT_IP");
        }elseif(getenv("HTTP_X_FORWARDED_FOR")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
            if (strstr($ip, ',')) {
                $tmp = explode (',', $ip);
                $ip = trim($tmp[0]);
            }
        }else{
            $ip = getenv("REMOTE_ADDR");
        }
        return $ip;
    }



    function pagination($s, $ptotal, $url){
        global $site;

        $forlimit = 3;
        if($ptotal < 2){
            null;
        }else{

            if($s > 4){
                $prev  = $s - 1;
                echo '<li><a  href="'.$site.'/'.$url.'1" ><i class="zmdi zmdi-long-arrow-left"></i></a></li>';
                echo '<li><a  href="'.$site.'/'.$url.($s-1).'" ><</a></li>';
            }

            for($i = $s - $forlimit; $i < $s + $forlimit + 1; $i++){
                if($i > 0 && $i <= $ptotal){
                    if($i == $s){
                        echo '<li><a class="active" href="#">'.$i.'</a></li>';
                    }else{
                        echo '<li><a  href="'.$site.'/'.$url.$i.'" >'.$i.'</a></li>';
                    }
                }
            }

            if($s <= $ptotal - 4){
                $next  = $s + 1;
                echo '<li><a  href="'.$site.'/'.$url.$next.'" > <i class="zmdi zmdi-long-arrow-right"></i></a></li>';
                echo '<li><a  href="'.$site.'/'.$url.$ptotal.'" >»</a></li>';
            }
        }

    }



    function title(){

        global $db;
        global $site;
        global $sitebaslik;
        global $sitekeyw;
        global $sitedesc;
        global $sitelogo;

        $productsef  = @get('productsef');
        $catsef      = @get('catsef');
        $pagesef     = @get('pagesef');


        if($productsef){

            $product     = $db->prepare("SELECT * FROM urunler WHERE urunsef=:u AND urundurum=:d");
            $product->execute([':u' => $productsef,':d' => 1]);
            if($product->rowCount()){

                $prow  = $product->fetch(PDO::FETCH_OBJ);
                $title['title']  = $prow->urunbaslik." - ".$sitebaslik;
                $title['desc']   = mb_substr($prow->urundesc,0,260,'utf8');
                $title['keyw']   = mb_substr($prow->urunkeyw,0,260,'utf8');
                $title['img']    = $site."/uploads/product/".$prow->urunkapak;

            }

        }else if($catsef){

            $cat     = $db->prepare("SELECT * FROM urun_kategoriler WHERE katsef=:u AND katdurum=:d");
            $cat->execute([':u' => $catsef,':d' => 1]);
            if($cat->rowCount()){

                $crow2  = $cat->fetch(PDO::FETCH_OBJ);
                $title['title']  = $crow2->katbaslik." - ".$sitebaslik;
                $title['desc']   = mb_substr($crow2->katdesc,0,260,'utf8');
                $title['keyw']   = mb_substr($crow2->katkeyw,0,320,'utf8');
                $title['img']    = $site."/uploads/product/".$crow2->katresim;

            }

        }else if($pagesef){

            $cat     = $db->prepare("SELECT * FROM sayfalar WHERE sef=:u AND durum=:d");
            $cat->execute([':u' => $pagesef,':d' => 1]);
            if($cat->rowCount()){

                $crow  = $cat->fetch(PDO::FETCH_OBJ);
                $title['title']  = $crow->baslik." - ".$sitebaslik;
                $title['desc']   = mb_substr($crow->icerik,0,260,'utf8');
                $title['keyw']   = mb_substr($sitekeyw,0,320,'utf8');
                $title['img']    = $site."/uploads/".$crow->kapak;

            }

        }else if(location() == $site."/login-register"){

            $title['title']  = "Kayıt Ol/Giriş Yap - ".$sitebaslik;
            $title['desc']   = mb_substr($sitedesc,0,260,'utf8');
            $title['keyw']   = mb_substr($sitekeyw,0,320,'utf8');
            $title['img']    = $site."/uploads/".$sitelogo;

        }else if(location() == $site."/contact-us"){

            $title['title']  = "Bize Ulaşın - ".$sitebaslik;
            $title['desc']   = mb_substr($sitedesc,0,260,'utf8');
            $title['keyw']   = mb_substr($sitekeyw,0,320,'utf8');
            $title['img']    = $site."/uploads/".$sitelogo;

        }else if(location() == $site."/cart"){

            $title['title']  = "Sepetim - ".$sitebaslik;
            $title['desc']   = mb_substr($sitedesc,0,260,'utf8');
            $title['keyw']   = mb_substr($sitekeyw,0,320,'utf8');
            $title['img']    = $site."/uploads/".$sitelogo;

        }else if(location() == $site."/checkout"){

            $title['title']  = "Ödeme Yap - ".$sitebaslik;
            $title['desc']   = mb_substr($sitedesc,0,260,'utf8');
            $title['keyw']   = mb_substr($sitekeyw,0,320,'utf8');
            $title['img']    = $site."/uploads/".$sitelogo;

        }else if(mb_substr(location(),0,28) == $site."/profile"){

            $title['title']  = "Profilim - ".$sitebaslik;
            $title['desc']   = mb_substr($sitedesc,0,260,'utf8');
            $title['keyw']   = mb_substr($sitekeyw,0,320,'utf8');
            $title['img']    = $site."/uploads/".$sitelogo;

        }else{
            $title['title']  = $sitebaslik;
            $title['desc']   = mb_substr($sitedesc,0,260,'utf8');
            $title['keyw']   = mb_substr($sitekeyw,0,320,'utf8');
            $title['img']    = $site."/uploads/".$sitelogo;
        }


        return $title;

    }

    $title = title();

?>