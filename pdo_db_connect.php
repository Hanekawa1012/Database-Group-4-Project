<?php
$dsn = 'mysql:host=localhost;dbname=db-group4'; // 数据源名称
$username = 'root'; // 数据库用户名
$password = ''; // 数据库密码

try {
    // 创建 PDO 实例
    $con = new PDO($dsn, $username, $password);
    // 设置错误模式为异常
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connect Success！";
} catch (PDOException $e) {
    echo "连接失败: " . $e->getMessage();
}
?>