<?php
                                require_once(__DIR__."/service/sendmail.class.php");
                                $strMail = "Count: 8, Rows: 2, Cols: 4, Time: 2017-11-29 17:27:49.";
                                $mail = new MySendMail();
                                // $mail->setServer("smtp@126.com", "XXXXX@126.com", "XXXXX"); //设置smtp服务器，普通连接方式
                                $mail->setServer("smtp.qq.com", "dingyalei22@qq.com", "jvnbahaxkocqcacg", 465, false); //设置smtp服务器，到服务器的SSL连接
                                $mail->setFrom("dingyalei22@qq.com"); //设置发件人
                                $mail->setReceiver("dingyalei22@126.com"); //设置收件人，多个收件人，调用多次
                                // $mail->setCc("XXXX"); // 设置抄送，多个抄送，调用多次
                                // $mail->setBcc("XXXXX"); // 设置秘密抄送，多个秘密抄送，调用多次
                                // $mail->addAttachment("XXXX"); // 添加附件，多个附件，调用多次
                                $mail->setMail("Grids apply comming!!!", $strMail); // 设置邮件主题、内容
                                $mail->sendMail(); //发送
