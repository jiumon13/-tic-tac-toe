<?php

mysql_connect('localhost', 'root', 'vertrigo') or die(mysql_error());
mysql_select_db('test') or die(mysql_error());

ini_set('max_execution_time', 0);

//$sql = "SELECT name FROM clients, orders WHERE orders.client_id = clients.id AND DATEDIFF (NOW(), order_date) >= 7";
//$sql4 = "SELECT merchandise.name FROM orders, merchandise WHERE orders.item_id = merchandise.id AND orders.status != 'complete'";
//$sql2 = "SELECT clients.name, cost FROM clients, merchandise, orders WHERE orders.client_id = clients.id AND orders.item_id = merchandise.id ORDER BY cost DESC LIMIT 10";
//$sql1 = "SELECT name, client_id, COUNT(orders.client_id) AS qty FROM orders, clients WHERE orders.client_id = clients.id GROUP BY client_id ORDER BY qty DESC LIMIT 5";
//mysql_query($sql) or die(mysql_error());


$dateTime = new DateTime();
$dateTime->modify('-1 year');
$dateTime->format("Y-m-d");

$from = $_GET['from'];
$to = $_GET['to'];

$dateTime->modify("+" . "$from" . "day");

while ($from < $to){
    $i = 5480;
    while ($i > 0) {
        $k = rand(0, 200000);
        $f = rand(0, 200);
        $q = rand(1,4);

        $d = $dateTime->format("Y-m-d");

        $sql = "INSERT INTO orders(client_id, item_id, order_date, status) VALUES ($k, $f, '$d', $q)";

        mysql_query($sql) or die(mysql_error());

        $i--;
    }
        $dateTime->modify("+1 day");
        $from++;
}


