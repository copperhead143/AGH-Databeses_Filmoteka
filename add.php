<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodawanie do Filmoteki</title>
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
<nav>
    <ul>
        <li><a href="index.php">Powrót do strony głównej</a></li>
    </ul>
</nav>
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
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];

    if ($type == 'actor') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $sql = "INSERT INTO actors (first_name, last_name) VALUES (:first_name, :last_name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['first_name' => $first_name, 'last_name' => $last_name]);
        echo "Aktor dodany pomyślnie.";
    } elseif ($type == 'director') {
        $first_name = $_POST['director_first_name'];
        $last_name = $_POST['director_last_name'];
        $sql = "INSERT INTO directors (first_name, last_name) VALUES (:first_name, :last_name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['first_name' => $first_name, 'last_name' => $last_name]);
        echo "Reżyser dodany pomyślnie.";
    } elseif ($type == 'studio') {
        $studio_name = $_POST['studio_name'];
        $studio_location = $_POST['studio_location'];
        $sql = "INSERT INTO studio (studio_name, studio_location) VALUES (:studio_name, :studio_location)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['studio_name' => $studio_name, 'studio_location' => $studio_location]);
        echo "Studio dodane pomyślnie.";
    } elseif ($type == 'rating') {
        $points = $_POST['points'];
        $source = $_POST['source'];
        $sql = "INSERT INTO ratings (points, source) VALUES (:points, :source)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['points' => $points, 'source' => $source]);
        echo "Ocena dodana pomyślnie.";
    } elseif ($type == 'movie') {
        $movie_name = $_POST['movie_name'];
        $director_id = $_POST['director_id'];
        $actor1_id = $_POST['actor1_id'];
        $actor2_id = $_POST['actor2_id'];
        $studio_id = $_POST['studio_id'];
        $rating_id = $_POST['rating_id'];
        $sql = "INSERT INTO movies (movie_name, director_id, actor1_id, actor2_id, studio_id, rating_id) VALUES (:movie_name, :director_id, :actor1_id, :actor2_id, :studio_id, :rating_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'movie_name' => $movie_name,
            'director_id' => $director_id,
            'actor1_id' => $actor1_id,
            'actor2_id' => $actor2_id,
            'studio_id' => $studio_id,
            'rating_id' => $rating_id
        ]);
        echo "Film dodany pomyślnie.";
    }
}
?>

<h1>Dodaj Nowy Element</h1>
<form action="" method="POST">
    <label for="type">Typ elementu:</label>
    <select name="type" id="type">
        <option value="actor">Aktor</option>
        <option value="director">Reżyser</option>
        <option value="studio">Studio</option>
        <option value="rating">Ocena</option>
        <option value="movie">Film</option>
    </select>
    <br><br>

    <!-- Aktor -->
    <div id="actor-form" class="form-section">
        <label for="first_name">Imię:</label>
        <input type="text" name="first_name" id="first_name">
        <br><br>
        <label for="last_name">Nazwisko:</label>
        <input type="text" name="last_name" id="last_name">
    </div>

    <!-- Reżyser -->
    <div id="director-form" class="form-section">
        <label for="director_first_name">Imię:</label>
        <input type="text" name="director_first_name" id="director_first_name">
        <br><br>
        <label for="director_last_name">Nazwisko:</label>
        <input type="text" name="director_last_name" id="director_last_name">
    </div>

    <!-- Studio -->
    <div id="studio-form" class="form-section">
        <label for="studio_name">Nazwa Studia:</label>
        <input type="text" name="studio_name" id="studio_name">
        <br><br>
        <label for="studio_location">Lokalizacja Studia:</label>
        <input type="text" name="studio_location" id="studio_location">
    </div>

    <!-- Ocena -->
    <div id="rating-form" class="form-section">
        <label for="points">Ocena (punkty):</label>
        <input type="number" step="0.1" name="points" id="points">
        <br><br>
        <label for="source">Źródło:</label>
        <input type="text" name="source" id="source">
    </div>

    <!-- Film -->
    <div id="movie-form" class="form-section">
        <label for="movie_name">Nazwa Filmu:</label>
        <input type="text" name="movie_name" id="movie_name">
        <br><br>
        <label for="director_id">Reżyser:</label>
        <select name="director_id" id="director_id">
            <?php
            $stmt = $pdo->query("SELECT director_id, CONCAT(first_name, ' ', last_name) AS full_name FROM directors");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['director_id'] . "'>" . $row['full_name'] . "</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="actor1_id">Aktor 1:</label>
        <select name="actor1_id" id="actor1_id">
            <?php
            $stmt = $pdo->query("SELECT actor_id, CONCAT(first_name, ' ', last_name) AS full_name FROM actors");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['actor_id'] . "'>" . $row['full_name'] . "</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="actor2_id">Aktor 2:</label>
        <select name="actor2_id" id="actor2_id">
            <?php
            $stmt = $pdo->query("SELECT actor_id, CONCAT(first_name, ' ', last_name) AS full_name FROM actors");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['actor_id'] . "'>" . $row['full_name'] . "</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="studio_id">Studio:</label>
        <select name="studio_id" id="studio_id">
            <?php
            $stmt = $pdo->query("SELECT studio_id, studio_name FROM studio");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['studio_id'] . "'>" . $row['studio_name'] . "</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="rating_id">Ocena:</label>
        <select name="rating_id" id="rating_id">
            <?php
            $stmt = $pdo->query("SELECT rating_id, points FROM ratings");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['rating_id'] . "'>" . $row['points'] . "</option>";
            }
            ?>
        </select>
    </div>

    <br><br>
    <input type="submit" value="Dodaj">
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSections = document.querySelectorAll('.form-section');
    const typeSelect = document.getElementById('type');

    function showFormSection() {
        formSections.forEach(section => section.style.display = 'none');
        const selectedType = typeSelect.value;
        document.getElementById(`${selectedType}-form`).style.display = 'block';
    }

    typeSelect.addEventListener('change', showFormSection);
    showFormSection();
});
</script>

<footer>
    <p>&copy; 2024 Filmoteka. Wszelkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
