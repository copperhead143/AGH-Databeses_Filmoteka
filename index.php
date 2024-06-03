<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmoteka</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        nav {
            background-color: #333;
            color: white;
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        nav ul li a:hover {
            text-decoration: underline;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        label, select, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            width: auto;
            background-color: #333;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <h1>Filmoteka</h1>
    <nav>
        <ul>
            <li><a href="filter.php">Filtruj Filmy</a></li>
            <li><a href="add.php">Dodaj Dane</a></li>
        </ul>
    </nav>
    <form action="" method="GET">
        <label for="sort">Sortuj według:</label>
        <select name="sort" id="sort">
            <option value="movie_name ASC">Alfabetycznie A-Z</option>
            <option value="movie_name DESC">Alfabetycznie Z-A</option>
            <option value="movie_id ASC">ID rosnąco</option>
            <option value="movie_id DESC">ID malejąco</option>
            <option value="ratings.points ASC">Ocena rosnąco</option>
            <option value="ratings.points DESC">Ocena malejąco</option>
        </select>
        <br><br>
        <input type="submit" value="Sortuj">
    </form>
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

    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'movie_name ASC';
    $valid_sort_columns = [
        'movie_id ASC', 'movie_id DESC',
        'movie_name ASC', 'movie_name DESC',
        'ratings.points ASC', 'ratings.points DESC'
    ];

    if (!in_array($sort, $valid_sort_columns)) {
        $sort = 'movie_name ASC';
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
        $sort";

    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();

    echo "<h2>Uwaga! aktorzy są przypisywani do filmów losowo XD</h2>";
    echo "<h2>Wszystkie Filmy</h2>";
    echo "<table><tr>
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
    <footer>
        Projekt bazy danych AGH WIMiIP Szymon Sikora
    </footer>
</body>
</html>
