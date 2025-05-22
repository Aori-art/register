<?php
require_once 'database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookTitle = $_POST['bookTitle'] ?? '';
    $bookISBN = $_POST['bookISBN'] ?? '';
    $bookYear = $_POST['bookYear'] ?? '';
    $bookQuantity = $_POST['bookQuantity'] ?? '';

    if ($bookTitle && $bookISBN && $bookYear && $bookQuantity) {
        try {
            $db = new database();
            $con = $db->opencon();

            $stmt = $con->prepare("INSERT INTO books (book_title, book_isbn, book_pubyear, quantity_avail) VALUES (?, ?, ?, ?)");
            $stmt->execute([$bookTitle, $bookISBN, $bookYear, $bookQuantity]);

            $message = '<div class="alert alert-success">Book added successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">Please fill in all required fields.</div>';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <title>Books</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Library Management System (Admin)</a>
    <a class="btn btn-outline-light ms-auto" href="add_authors.html">Add Authors</a>
    <a class="btn btn-outline-light ms-2" href="add_genres.html">Add Genres</a>
    <a class="btn btn-outline-light ms-2 active" href="add_books.php">Add Books</a>
    <div class="dropdown ms-2">
      <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="profile.html"><i class="bi bi-person-circle me-2"></i> See Profile Information</a></li>
        <li><button class="dropdown-item" onclick="updatePersonalInfo()"><i class="bi bi-pencil-square me-2"></i> Update Personal Information</button></li>
        <li><button class="dropdown-item" onclick="updatePassword()"><i class="bi bi-key me-2"></i> Update Password</button></li>
        <li><button class="dropdown-item text-danger" onclick="logout()"><i class="bi bi-box-arrow-right me-2"></i> Logout</button></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">
  <?= $message ?>
  <h4 class="mt-5">Add New Book</h4>
  <form method="POST" action="add_books.php">
    <div class="mb-3">
      <label for="bookTitle" class="form-label">Book Title</label>
      <input type="text" class="form-control" id="bookTitle" name="bookTitle" required>
    </div>
    <div class="mb-3">
      <label for="bookISBN" class="form-label">ISBN</label>
      <input type="text" class="form-control" id="bookISBN" name="bookISBN" required>
    </div>
    <div class="mb-3">
      <label for="bookYear" class="form-label">Publication Year</label>
      <input type="number" class="form-control" id="bookYear" name="bookYear" required>
    </div>
    <div class="mb-3">
      <label for="bookGenres" class="form-label">Genres</label>
      <select class="form-select" id="bookGenres" multiple>
        <option value="Fiction">Fiction</option>
        <option value="Non-Fiction">Non-Fiction</option>
        <option value="Science">Science</option>
        <option value="History">History</option>
        <option value="Biography">Biography</option>
        <option value="Fantasy">Fantasy</option>
        <option value="Mystery">Mystery</option>
      </select>
      <small class="form-text text-muted">Genres not yet saved in DB. (This can be implemented later.)</small>
    </div>
    <div class="mb-3">
      <label for="bookQuantity" class="form-label">Quantity Available</label>
      <input type="number" class="form-control" id="bookQuantity" name="bookQuantity" required>
    </div>

    <button type="submit" class="btn btn-primary">Add Book</button>
  </form>
</div>

<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
