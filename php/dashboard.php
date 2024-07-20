<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <?php
    echo "<div class='dashboard-container'>";

    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: index.html");
        exit();
    }

    $server = $_SESSION['server'];
    $database = $_SESSION['database'];
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $connectionInfo = array("Database" => $database, "UID" => $username, "PWD" => $password);
    $conn = sqlsrv_connect($server, $connectionInfo);

    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    echo "<div style='color: green; padding: 20px; border-radius: 5px; box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.1); background-color: #f1f1f1'>";
    echo "<h3>User Name : " . $username."</h3><br>";
    echo "Server : " . $server ."<br><br>";
    echo "Database : " . $database."<br><br>";   
    echo"</div>";

    // Fetch and display data based on user role
    $tables = ['Orders', 'Customers', 'Employees'];
    foreach ($tables as $table) {      
        echo "<h2>$table - Table</h2>";

        $query = "SELECT * FROM $table";
        $stmt = sqlsrv_query($conn, $query);

        if ($stmt === false) {
            echo "<p>You do not have permission to view the $table table.</p>";
        } else {
            $rows = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows[] = $row;
            }
            echo "<p>Record count: " . count($rows) . "</p>";
            echo "<table class='greyGridTable'><tr>";

            // Display table headers
            foreach (array_keys($rows[0]) as $header) {
                echo "<th>$header</th>";
            }
            echo "</tr>";

            // Display table data
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    // Check if the value is a DateTime object and format it
                    if ($value instanceof DateTime) {
                        echo "<td>" . $value->format('Y-m-d H:i:s') . "</td>";
                    } else {
                        echo "<td>$value</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }

    sqlsrv_close($conn);

    echo "</div>";
    ?> 
</body>
</html>