<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mikrotik</title>
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
            em {
                font-weight: bold;
                font-style: normal;
                color: #f00;
            }
        </style>
        <script src="jquery.min.js"></script>
        <script>
          var xhr;
          function updateTable() {
              if (xhr && xhr.readyState !== 4) {
                    return; 
                }
              xhr = $.ajax({
              url: '', 
              type: 'POST',
              data: { action: 'get_table_data' },
              cache: false,
              success: function(data) {
                $('#myTable').empty();
                $('#myTable').html(data);
              }
            });
          }
          setTimeout(updateTable, 5000);
        </script>
        
        <script>
            function transliterate(text) {
              var cyrillic = {
                'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh', 'з': 'z', 'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm',
                'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'shch',
                'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya',
                'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'YO', 'Ж': 'ZH', 'З': 'Z', 'И': 'I', 'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M',
                'Н': 'N', 'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C', 'Ч': 'CH', 'Ш': 'SH', 'Щ': 'SHCH',
                'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'YU', 'Я': 'YA'
              };

              return text.replace(/[ъьЪЬ]/g, '').split('').map(function (char) {
                return cyrillic[char] || char;
              }).join('');
            }

            document.getElementById('form').addEventListener('submit', function (event) {
              var input = document.getElementById('Name');
              input.value = transliterate(input.value);
            });
        </script>
    </head>
    <body>
        <?php
            $ip = $_GET['IP'];
            $login = $_GET['Login'];
            $password = $_GET['Password'];
            use PEAR2\Net\RouterOS;
            require_once 'PEAR2_Net_RouterOS-1.0.0b6/src/PEAR2/Autoload.php';
            try
            {
                $client = new RouterOS\Client($ip, $login, $password, 8728);
                $cols = 3;
                $html = '<div class="container"';
                $html .= '<div class="table-container">';
                $html .= '<table id="myTable">';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th style="text-align: center;" width="250">Interface</th>';
                $html .= '<th style="text-align: center;" width="250">SSID</th>';
                $html .= '<th style="text-align: center;" width="250">Mac-Address</th>';
                $html .= '</tr';
                $html .= '</thead>';
                $util = new RouterOS\Util($client);
                $registrationTable = $util->setMenu('/caps/registration-table')->getAll(array(), RouterOS\Query::where('ssid', 'test'));
                $id = 0;
                foreach ($registrationTable as $elem) 
                {
                    $id++;
                    if ($elem->getType() === RouterOS\Response::TYPE_DATA) 
                    {
                        $interface = $elem->getProperty('interface');
                        $ssid = $elem->getProperty('ssid');
                        $address = $elem->getProperty('mac-address');
                    }
                    $html .= '<tr>';
                    for ($td=1; $td<=$cols; $td++)
                    { 
                        switch ($td)
                        {
                            case 1:
                                $html .='<td align="center"><pre>'. $interface .'</pre></td>';
                                break;
                            case 2:
                                $html .= '<td align="center"><pre>'. $ssid .'</pre></td>';
                                break;
                            case 3:
                                $html .= '<td align="center"><pre><input 
                                    style="text-align: center;
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
                                    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;"
                                    type="text"  id='.$id.' onClick="myFunction(id)" value='.$address.' align="center" readonly></pre></td>';
                                break;
                        }
                    }
                    $html .= '</tr>';
                }
                $html .= '</table>';

                $html .= '<div class="errorcontainer">';
                $html .= '<div class="form-container">';
                $html .= '<FORM id="form" action="info.php" method="get" target="_blank">';
                $html .= '<input type="hidden" name="IP" value='.$ip.'>';
                $html .= '<input type="hidden" name="Login" value='.$login.'>';
                $html .= '<input type="hidden" name="Password" value='.$password.'>';
                $html .= '<label for="Mac">Mac-Address<em>*</em></label>';
                $html .= '<input name="Mac" 
                       id="mac"
                       pattern="^(?:[0-9A-Fa-f]{2}[:-]){5}(?:[0-9A-Fa-f]{2})$"
                       placeholder="xx-xx-xx-xx-xx-xx"
                       type="text"
                       autofocus
                       required>';
                $html .= '<br>';
                $html .= '<br>';
                $html .= '<label for="Name">Name</label>';
                $html .= '<input id="Name" name="Name" 
                       placeholder="Name"
                       type="text">';
                $html .= '<br>';
                $html .= '<br>';
                $html .= '<label for="MacPassword">Password<em>*</em></label>';
                $html .= '<input name="MacPassword"
                       placeholder="Password"
                       type="text"
                       required>';
                $html .= '<br>';
                $html .= '<br>';
                $html .= '<button>Add</button>';
                $html .= '<br>';
                $html .= '<br>';
                $html .= '<button type="reset">Clear</button>';
                $html .= '</FORM>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';            




                function getTableData()
                {
                    global $ip, $login, $password, $client;
                    $util = new RouterOS\Util($client);
                    $registrationTable = $util->setMenu('/caps/registration-table')->getAll(array(), RouterOS\Query::where('ssid', 'test'));
                    $cols=3;
                    $data = '<div class="container"';
                    $data .= '<div class="table-container">';
                    $data .= '<table id="myTable">';
                    $data .= '<thead>';
                    $data .= '<tr>';
                    $data .= '<th style="text-align: center;" width="250">Interface</th>';
                    $data .= '<th style="text-align: center;" width="250">SSID</th>';
                    $data .= '<th style="text-align: center;" width="250">Mac-Address</th>';
                    $data .= '</tr';
                    $data .= '</thead>';
                    foreach ($registrationTable as $elem) 
                    {
                        if ($elem->getType() === RouterOS\Response::TYPE_DATA) 
                            {
                                $interface = $elem->getProperty('interface');
                                $ssid = $elem->getProperty('ssid');
                                $address = $elem->getProperty('mac-address');
                            }
                            $data .= '<tr>';
                            for ($td=1; $td<=$cols; $td++)
                            { 
                                switch ($td)
                                {
                                    case 1:
                                        $data .='<td align="center"><pre>'. $interface .'</pre></td>';
                                        break;
                                    case 2:
                                        $data .= '<td align="center"><pre>'. $ssid .'</pre></td>';
                                        break;
                                    case 3:
                                        $data .= '<td align="center"><pre><input
                                            style="text-align: center;
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
                                            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;"
                                            type="text"  id='.$address.' onClick="myFunction(id)" value='.$address.' align="center" readonly></pre></td>';
                                        break;
                                }
                            }
                        $data .= '</tr>';
                    }
                    $data .= '</table>';
                    $data .= '</div';
                    $data .= '</div';

                    echo $data;
                }

                if (isset($_POST['action']) && $_POST['action'] === 'get_table_data') 
                {
                    getTableData();
                    exit;
                }
                
                echo $html;
            }
            catch (Exception $e)
            {
                die('<div class="errorcontainer">
                        <h2>ERROR! Not connected!</h2>
                        <p>'.$e->getMessage().'</p>
                     </div>');
            }
        ?>
    </body>
    <script>
            function myFunction(id) {
                /* Get the text field */
                var copyText = document.getElementById(id);

                /* Select the text field */
                copyText.select();
                /* Copy the text inside the text field */
                document.execCommand("copy");

                /* Alert the copied text */
                alert("Copied: " + copyText.value);
            }
    </script>
</html>
