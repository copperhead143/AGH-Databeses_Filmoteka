<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmoteka - Filtruj Filmy</title>
</head>
<body>
<?php
$host = 'localhost';
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
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
    exit;
}

$actor = isset($_GET['actor']) ? $_GET['actor'] : '';
$studio = isset($_GET['studio']) ? $_GET['studio'] : '';
$min_rating = isset($_GET['min_rating']) ? $_GET['min_rating'] : 0;
$max_rating = isset($_GET['max_rating']) ? $_GET['max_rating'] : 10;

$sql = "SELECT movies.movie_id, movies.movie_name, directors.first_name AS director_first_name, directors.last_name AS director_last_name, actors1.first_name AS actor1_first_name, actors1.last_name AS actor1_last_name, actors2.first_name AS actor2_first_name, actors2.last_name AS actor2_last_name, studio.studio_name, studio.studio_location, ratings.points, ratings.source 
FROM movies 
JOIN actors AS actors1 ON movies.actor1_id = actors1.actor_id 
JOIN actors AS actors2 ON movies.actor2_id = actors2.actor_id 
JOIN directors ON movies.director_id = directors.director_id 
JOIN studio ON movies.studio_id = studio.studio_id 
JOIN ratings ON movies.rating_id = ratings.rating_id 
WHERE (:actor1 = '' OR movies.actor1_id = :actor2 OR movies.actor2_id = :actor3 OR movies.actor1_id = '0') 
AND (:studio1 = '' OR movies.studio_id = :studio2 OR movies.studio_id = '0') 
AND ratings.points BETWEEN :min_rating AND :max_rating 
ORDER BY movies.movie_id ASC";



$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':actor1' => $actor,
    ':actor2' => $actor,
    ':actor3' => $actor,
    ':studio1' => $studio,
    ':studio2' => $studio,
    ':min_rating' => $min_rating,
    ':max_rating' => $max_rating,
]);



$results = $stmt->fetchAll();


?>

<h1>Filtruj Filmy</h1>
<a href="index.php">Wróć do strony głównej</a>
<form action="" method="GET">
    <label for="actor">Aktor:</label>
    <select name="actor" id="actor">
        <option value="">Wybierz aktora</option>
        <?php
        $stmt = $pdo->query("SELECT actor_id, CONCAT(first_name, ' ', last_name) AS full_name FROM actors");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['actor_id'] . "'>" . $row['full_name'] . "</option>";
        }
        ?>
    </select>
    <br><br>
    <label for="studio">Studio:</label>
    <select name="studio" id="studio">
        <option value="">Wybierz studio</option>
        <?php
        $stmt = $pdo->query("SELECT studio_id, studio_name FROM studio");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['studio_id'] . "'>" . $row['studio_name'] . "</option>";
        }
        ?>
    </select>
    <br><br>
    <label for="min_rating">Minimalna Ocena:</label>
    <input type="number" name="min_rating" id="min_rating" step="0.1" value="0">
    <br><br>
    <label for="max_rating">Maksymalna Ocena:</label>
    <input type="number" name="max_rating" id="max_rating" step="0.1" value="10">
    <br><br>
    <input type="submit" value="Filtruj">
</form>

<h1>Wyniki Filtracji</h1>
<table border='1'>
    <tr>
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
    </tr>

<?php
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
?>
</table>
</body>
</html>
