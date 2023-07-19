<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
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
            $html = '<div class="errorcontainer">';
            $html .= '<h2>Connect to Mikrotik</h2>';
            $html .= '<FORM method="GET" action="mikrotik.php">';
            $html .= '<label for="IP">IP-Address</label>';
            $html .= '<input name="IP" 
                   pattern="^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$"
                   placeholder="xxx.xxx.xxx.xxx"
                   type="text"
                   autofocus
                   required>';
            $html .= '<br>';
            $html .= '<br>';
            $html .= '<label for="Login">Login</label>';
            $html .= '<input name="Login" 
                   placeholder="Login"
                   type="text"
                   required>';
            $html .= '<br>';
            $html .= '<br>';
            $html .= '<label for="Password">Password</label>';
            $html .= '<input name="Password"
                   placeholder="Password"
                   type="text"
                   required>';
            $html .= '<br>';
            $html .= '<br>';
            $html .= '<button>Connect</button>';
            $html .= '</FORM>';
            $html .= '</div>';
            echo $html;
        ?>
    </body>
</html>
