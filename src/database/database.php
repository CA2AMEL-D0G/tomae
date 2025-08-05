<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tomae";

// Create connection
$connection = mysqli_connect($servername, $username, $password);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$hasdatabase = false;
//setup
$dbCheck = mysqli_query($connection, "SHOW DATABASES LIKE '$dbname'");
if (mysqli_num_rows($dbCheck) === 0) {
    if (!mysqli_query($connection, "CREATE DATABASE `$dbname`")) {
        
        die("Database creation failed: " . mysqli_error($connection));
    }
}else{
    $hasdatabase = true;
}



    mysqli_select_db($connection, 'tomae');

if (!$hasdatabase){

$queries = ["CREATE TABLE bebida (
    id_bebida INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_bebida TEXT,
    preco DECIMAL(10, 2),
    estoque INTEGER,
    metadados TEXT,
    caminho_foto TEXT,
    fk_categoria_id_categoria INTEGER
);","CREATE TABLE admin (
    id_admin INTEGER PRIMARY KEY AUTO_INCREMENT,
    login TEXT,
    senha TEXT
);
",
"CREATE TABLE categoria (
    id_categoria INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_categoria TEXT
);
",
"ALTER TABLE bebida ADD CONSTRAINT FK_bebida_2
    FOREIGN KEY (fk_categoria_id_categoria)
    REFERENCES categoria (id_categoria)
    ON DELETE SET NULL;"






];

foreach ($queries as $query) {
    if (!mysqli_query($connection, $query)) {
        die("Error running query: " . mysqli_error($connection));
    }
}
 }
    










//DATABASE MISC FUNCTIONS

function db_query($connection, $query, $params = [], $fetchOne = false) {
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Query error: " . mysqli_error($connection));
    }

    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // assumes all strings
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($fetchOne) {
        return mysqli_fetch_assoc($result);
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}











    $user = db_query($connection, "SELECT * FROM admin WHERE login = ?", ["admin"], true);
    if(!$user){

        $sql = mysqli_prepare($connection,"INSERT INTO admin (login, senha) 
VALUES (
    'admin',
    'admin'
    
);");
  mysqli_stmt_execute($sql);

}








?>