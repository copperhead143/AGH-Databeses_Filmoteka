<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmoteka</title>
</head>
<body>
    <h1>Filmoteka</h1>
    <nav>
        <ul>
            <li><a href="filter.php">Filtruj Filmy</a></li>
            <li><a href="add.php">Dodaj Dane</a></li>
        </ul>
    </nav>
    <?php
    $host = '127.0.0.1';
    $db = 'filmoteka';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    $sql = "
    SELECT 
        movies.movie_id,
        movies.movie_name,
        directors.first_name AS director_first_name,
        directors.last_name AS director_last_name,
        actor1.first_name AS actor1_first_name,
        actor1.last_name AS actor1_last_name,
        actor2.first_name AS actor2_first_name,
        actor2.last_name AS actor2_last_name,
        studio.studio_name,
        studio.studio_location,
        ratings.points,
        ratings.source
    FROM 
        movies
    JOIN 
        directors ON movies.director_id = directors.director_id
    JOIN 
        actors AS actor1 ON movies.actor1_id = actor1.actor_id
    JOIN 
        actors AS actor2 ON movies.actor2_id = actor2.actor_id
    JOIN 
        studio ON movies.studio_id = studio.studio_id
    JOIN 
        ratings ON movies.rating_id = ratings.rating_id
    ORDER BY
        movies.movie_id ASC";

    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();

    // Wyświetlanie danych w jednej tabeli HTML
    echo "<h2>Uwaga! aktorzy są przypisywani do filmów losowo XD</h2>";
    echo "<h2>Wszystkie Filmy</h2>";
    echo "<table border='1'><tr>
        <th>Movie ID</th>
        <th>Movie Name</th>
        <th>Director First Name</th>
        <th>Director Last Name</th>
        <th>Actor 1 First Name</th>
        <th>Actor 1 Last Name</th>
        <th>Actor 2 First Name</th>
        <th>Actor 2 Last Name</th>
        <th>Studio Name</th>
        <th>Studio Location</th>
        <th>Rating Points</th>
        <th>Rating Source</th>
    </tr>";

    foreach ($results as $row) {
        echo "<tr>
            <td>{$row['movie_id']}</td>
            <td>{$row['movie_name']}</td>
            <td>{$row['director_first_name']}</td>
            <td>{$row['director_last_name']}</td>
            <td>{$row['actor1_first_name']}</td>
            <td>{$row['actor1_last_name']}</td>
            <td>{$row['actor2_first_name']}</td>
            <td>{$row['actor2_last_name']}</td>
            <td>{$row['studio_name']}</td>
            <td>{$row['studio_location']}</td>
            <td>{$row['points']}</td>
            <td>{$row['source']}</td>
        </tr>";
    }

    echo "</table>";
    ?>
</body>
</html>
