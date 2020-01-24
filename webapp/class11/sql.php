<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" >
    <title>form01</title>
</head>
<body>
<h1>SQL接続</h1>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <p>
        <input type="text" name="query">
        <input type="text" name="key">
        <input type="submit" value="送信">
    </p>
</form>
<?php
$query = isset($_POST['query']) ? $_POST['query'] : null;
$key = isset($_POST['key']) ? $_POST['key'] : null;

if(!$query || !$key) die(); // どちらかnullなら処理をやめる

function insert_sql($table, $data, $query) {
    try {
        $pdh = new PDO('sqlite:test.db');
        $sql = 'insert into list (url, contents) values (?, ?)';
        $sth = $pdh->prepare($sql);
        $sth->execute($data);

        $q = "'%$query%'";
        $sql = "select * from $table where contents like $q";
        $sth = $pdh->prepare($sql);
        $sth->execute();

        while ($row = $sth->fetch()) {
            echo $row['id'] . $row['url'] . $row['contents'] . "<br>";
        }

        $sql = "select count(*) from $table where contents like $q";
        $sth = $pdh->prepare($sql);
        $sth->execute();
        $cnt = $sth->fetchColumn();
        echo $cnt . "<br>";

    } Catch (PDOException $e) {
        print "error! " . $e->getMessage() . "<br>";
        die();
    }
}

$url = $query;
$contents = 'test data05';
insert_sql('list', [$url, $contents], $key);


?>