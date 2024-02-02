<?php
// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:websql.database.windows.net,1433; Database = sqlRED", "dima", "projectCDV@69");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "dima", "pwd" => "projectCDV@69", "Database" => "sqlRED", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:websql.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
?>
