<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Sat, 15 Oct 2016 03:30:10 GMT
 */
class EmailMarketing
{

    public function Send($from, $to, $subject, $message)
    {
        global $module_config;
        
        if ($module_config['emailmarketing']['server'] == 1) {
            if (!empty($module_config['emailmarketing']['sendgrid_apiKey'])) {
                
                require (NV_ROOTDIR . '/modules/emailmarketing/class/sendgrid/sendgrid-php.php');
                
                if (!empty($from)) {
                    if (is_array($from)) {
                        $from = new SendGrid\Email($from[1], $from[0]);
                    } else {
                        $from = new SendGrid\Email(null, $from);
                    }
                } else {
                    return false;
                }
                
                if (empty($to)) {
                    return false;
                } else {
                    if (is_array($to)) {
                        $to = new SendGrid\Email($to[1], $to[0]);
                    } else {
                        $to = new SendGrid\Email(null, $to);
                    }
                }
                
                $content = new SendGrid\Content("text/html", $message);
                $mail = new SendGrid\Mail($from, $subject, $to, $content);
                
                $sg = new \SendGrid($module_config['emailmarketing']['sendgrid_apiKey']);
                
                $response = $sg->client->mail()
                    ->send()
                    ->post($mail);
                return $response->statusCode();
            }
        }
    }

    public function addToList($from_email, $from_name, $to, $subject, $message, $files = '', $AddEmbeddedImage = false)
    {
        global $db;
        
        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_emailmarketing_queue (from_email, from_name, to_email, subject, message, files, embeddedimage) VALUES (:from_email, :from_name, :to_email, :subject, :message, :files, :embeddedimage)');
        $stmt->bindParam(':from_email', $from_email, PDO::PARAM_STR);
        $stmt->bindParam(':from_name', $from_name, PDO::PARAM_STR);
        $stmt->bindParam(':to_email', $to, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':files', $files, PDO::PARAM_STR);
        $stmt->bindParam(':embeddedimage', $AddEmbeddedImage, PDO::PARAM_INT);
        $exc = $stmt->execute();
    }
}