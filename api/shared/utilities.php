<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../libs/phpmailer/autoload.php';

class Utilities{
 
    public function getPaging($page, $total_rows, $records_per_page, $page_url){
 
        // paging array
        $paging_arr=array();
 
        // button for first page
        $paging_arr["first"] = $page>1 ? "{$page_url}page=1" : "";
 
        // count all products in the database to calculate total pages
        $total_pages = ceil($total_rows / $records_per_page);
 
        // range of links to show
        $range = 2;
 
        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
 
        $paging_arr['pages']=array();
        $page_count=0;

        for($x=$initial_num; $x<$condition_limit_num; $x++){
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if(($x > 0) && ($x <= $total_pages)){
                $paging_arr['pages'][$page_count]["page"]=$x;
                $paging_arr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";
 
                $page_count++;
            }
        }
 
        // button for last page
        $paging_arr["last"] = $page<$total_pages ? "{$page_url}page={$total_pages}" : "";
 
        // json format
        return $paging_arr;
    }

    public function getPagingNovo($page, $total_rows, $records_per_page, $page_url){

        // paging array
        $paging_arr=array();

        // button for first page
        $paging_arr["first"] = $page>1 ? "{$page_url}page=1" : "";

        // count all products in the database to calculate total pages
        $total_pages = ceil($total_rows / $records_per_page);

        // range of links to show
        $range = 2;

        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;

        $condition_limit_num = ($page + $range)  + 1;

        $paging_arr['pages']=array();
        $page_count=0;
        $paging_arr["totalItems"]=$total_pages;
        $paging_arr["rowsPerPageItems"]=$records_per_page;
        $paging_arr["pagina_atual"]= $page;

        for($x=$initial_num; $x<$condition_limit_num; $x++){
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if(($x > 0) && ($x <= $total_pages)){
                $paging_arr['pages'][$page_count]["page"]=$x;
                $paging_arr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";

                $page_count++;
            }
        }

        // button for last page
        $paging_arr["last"] = $page<$total_pages ? "{$page_url}page={$total_pages}" : "";

        // json format
        return $paging_arr;
    }

    public function enviarEmail($email, $nome, $subject, $body){

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();
//            $mail->Timeout  =   60;// Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'notificacoesjudbrass@gmail.com';                     // SMTP username
            $mail->Password   = 'noti2019';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to
            $mail->setLanguage('pt');
            $mail->setFrom('notificacoesjudbrass@gmail.com', 'Notificação judbrass');
            $mail->addAddress($email,  $nome);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->CharSet = 'UTF-8';

            if($mail->send()){
               return true;
            };

        } catch (Exception $e) {
            return false;
        }
    }

    public function enviarEmailMultiplos($recipients, $subject, $body){

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();
//            $mail->Timeout  =   60;// Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'notificacoesjudbrass@gmail.com';                     // SMTP username
            $mail->Password   = 'noti2019';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to
            $mail->setLanguage('pt');
            $mail->setFrom('notificacoesjudbrass@gmail.com', 'Notificação judbrass');

            foreach($recipients as $email => $nome)
            {
                $mail->addAddress($email,  $nome);
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->CharSet = 'UTF-8';

            if($mail->send()){
                return true;
            };

        } catch (Exception $e) {
            return false;
        }
    }

    public function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public function emailSicredi($email, $subject, $body){
        $client = new SoapClient('http://10.96.45.32:5001/Integracao.svc?WSDL', array("trace" => 1,"exceptions"=>0));

        $token = 'sd%$gghhkk..__#@1125..3';

        $function = 'SendMail';


        $arguments= array('SendMail' => array(
            'client_token' => $token,
            'subject' => $subject,
            'message' => $body,
            'mail_to' => $email,
            'isbody_html' => true
        ));


        $options = array('location' => 'http://10.96.45.32:5001/Integracao.svc');


        $result = $client->__soapCall($function, $arguments, $options);


        if($result){
            return true;
        }else{
            return false;
        }
    }
 
}
?>