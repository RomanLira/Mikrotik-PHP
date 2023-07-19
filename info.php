<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Info</title>
        <style>
            html, body {
                height: 100%;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            h2 {
                color: #333;
                text-align: center;
            }

            p {
                color: #333;
            }

            .errorcontainer {
                max-width: 400px;
                margin: 100px auto;
                padding: 20px;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            
            .container {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }

            .table-container {
              width: 50%;
            }

            .form-container {
              width: 50%;
              margin-bottom: 20px;
            }

            label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 5px;
            }

            input {
                    width: 380px;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 3px;
            }

            button {
                    width: 400px;
                    padding: 10px;
                    background-color: #3962d4;
                    color: #fff;
                    border: none;
                    border-radius: 3px;
                    cursor: pointer;
                    transition: background-color 0.3s;
            }

            button:hover {
                        background-color: #294db3;
            }

            button:active {
                        background-color: #6284e3;
            }
            
            TABLE {
             width: 500px;
             border-collapse: collapse; 
            }
            TD, TH {
             padding: 1px; /* Поля вокруг содержимого таблицы */
             border: 1px solid black; /* Параметры рамки */
            }
            TH {
                background-color: lightgray;
            }
            .table-container input{
            text-align: center;
            display: block;
            height: calc(1.25rem);
            width: calc(8rem);
            padding: 0.375rem 0.75rem;
            font-family: inherit;
            cursor: pointer;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #bdbdbd;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }
            em {
                font-weight: bold;
                font-style: normal;
                color: #f00;
            }
        </style>
    </head>
    <body>
        <?php
            $ip = $_GET['IP'];
            $login = $_GET['Login'];
            $password = $_GET['Password'];
            $mac = $_GET['Mac'];
            $name = $_GET['Name'];
            $macpass = $_GET['MacPassword'];
            use PEAR2\Net\RouterOS;
            require_once 'PEAR2_Net_RouterOS-1.0.0b6/src/PEAR2/Autoload.php';
            $client = new RouterOS\Client($ip, $login, $password, 8728);                                             
            $util = new RouterOS\Util($client);
            try
            {
                $mac = strtoupper($mac);
                $capsAccessArray = $util->setMenu('/caps/access-list')->getAll(array(), RouterOS\Query::where('mac-address', $mac));
                $dhcpServerArray = $util->setMenu('/ip dhcp-server lease')->getAll(array(), RouterOS\Query::where('mac-address', $mac));
                $arpArray = $util->setMenu('/ip/arp')->getAll(array(), RouterOS\Query::where('mac-address'. $mac));
                if(count($capsAccessArray) !== 0 || count($dhcpServerArray) !== 0 || count($arpArray) !== 0)
                {
                    throw new Exception('This mac-address already exists!');
                }
                
                $server = 'WIFI';
                $interface = 'bridge WIFI';
                $clientId = '1:'.$mac;
                $clientId = strtolower($clientId);
                
                $macip ='';
                $counter = 0;
                while($counter !== 1)
                {
                    $x = rand(80,87);
                    $y = rand(2,254);
                    $macip = "192.168.{$x}.{$y}";
                    $util = new RouterOS\Util($client);
                    $dhcpServerArray = $util->setMenu('/ip dhcp-server lease')->getAll(array(), RouterOS\Query::where('address', $macip));
                    $arpArray = $util->setMenu('/ip/arp')->getAll(array(), RouterOS\Query::where('address'. $macip));
                    if (count($dhcpServerArray) == 0 && count($arpArray) == 0) 
                    {
                       $counter++;
                    }
                }
                
                $addRequest = new RouterOS\Request('/caps/access-list/add');
                $addRequest->setArgument('mac-address', $mac);
                $addRequest->setArgument('comment', $name);
                $addRequest->setArgument('private-passphrase', $macpass);
                if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL)
                {
                    throw new Exception("Error when creating CAPsMAN's Access entry for {$mac}");
                }
                $html = '<div class="errorcontainer">';
                $html .= '<h2>Added to CAPsMAN/Access List</h2>';
                
                $addRequest = new RouterOS\Request('/ip/dhcp-server/lease/add');
                $addRequest->setArgument('mac-address', $mac);
                $addRequest->setArgument('address', $macip);
                $addRequest->setArgument('comment', $name);
                $addRequest->setArgument('server', $server);
                $addRequest->setArgument('client-id', $clientId);
                if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL)
                {
                    throw new Exception("Error when creating DHCP-Server Lease entry for {$mac}");
                }
                $html .= '<h2>Added to DHCP-Server Lease</h2>';
                
                $addRequest = new RouterOS\Request('/ip/arp/add');
                $addRequest->setArgument('mac-address', $mac);
                $addRequest->setArgument('address', $macip);
                $addRequest->setArgument('comment', $name);
                $addRequest->setArgument('interface', $interface);
                if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL)
                {
                    throw new Exception("Error when creating ARP entry for {$mac}");
                }
                $html .= '<h2>Added to IP/ARP</h2>';
                $html .= '</div';
                echo $html;
            } 
            catch (Exception $ex) 
            {
                die('<div class="errorcontainer">
                        <h2>ERROR! '.$ex->getMessage().'</h2>
                     </div>');
            }
        ?>
    </body>
</html>
