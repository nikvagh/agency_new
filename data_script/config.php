<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function open_conn_live()
{
    $dbhost = "localhost:3308";
    $dbuser = "root";
    $dbpass = "";
    $db = "agency_live";
    $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
    return $conn;
}

function close_conn_live($conn)
{
    $conn->close();
}

function open_conn_local()
{
    $dbhost = "localhost:3308";
    $dbuser = "root";
    $dbpass = "";
    $db = "tambwksf_theagency";
    $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
    return $conn;
}

function close_conn_local($conn)
{
    $conn->close();
}

