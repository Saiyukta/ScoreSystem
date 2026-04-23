<?php
include 'config.php';

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $score = intval($_POST['score']);

    // File upload variables
    $fileName = $_FILES['screenshot']['name'];
    $tmpName  = $_FILES['screenshot']['tmp_name'];
    $fileSize = $_FILES['screenshot']['size'];
    $error    = $_FILES['screenshot']['error'];

    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validation
    if (empty($name) || empty($score)) {
        $message = "Name and score are required.";
    } elseif ($error !== 0) {
        $message = "Error uploading file.";
    } elseif (!in_array($fileExt, $allowedTypes)) {
        $message = "Only JPG, JPEG, PNG files allowed.";
    } elseif ($fileSize > 2 * 1024 * 1024) {
        $message = "File size must be less than 2MB.";
    } else {

        // Create unique filename
        $newFileName = uniqid("score_", true) . "." . $fileExt;
        $uploadPath = "uploads/" . $newFileName;

        if (move_uploaded_file($tmpName, $uploadPath)) {

            // Insert into DB
            $stmt = $conn->prepare("INSERT INTO warriors (date, name, score, screenshot) VALUES (NOW(), ?, ?, ?)");
            $stmt->bind_param("sis", $name, $score, $newFileName);

            if ($stmt->execute()) {
                $message = "Score submitted successfully!";
                $success = true;
            } else {
                $message = "Database error.";
            }

        } else {
            $message = "Failed to upload image.";
        }
    }
}
?>

<!doctype html>
<html>
<head>
  <title>Add Score</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-md">

  <!-- Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-semibold">Submit Score</h1>
    <p class="text-gray-400 text-sm">Upload a verified high score</p>
  </div>

  <!-- Card -->
  <div class="bg-gray-800 rounded-xl shadow-lg p-6">

    <!-- Message -->
    <?php if ($message): ?>
      <div class="<?= $success ? 'text-green-400' : 'text-red-400'; ?> mb-4 text-sm font-medium">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data" class="space-y-4">

      <!-- Name -->
      <div>
        <label class="block text-sm text-gray-400 mb-1">Player Name</label>
        <input
          type="text"
          name="name"
          class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-500"
          required
        >
      </div>

      <!-- Score -->
      <div>
        <label class="block text-sm text-gray-400 mb-1">Score</label>
        <input
          type="number"
          name="score"
          class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-500"
          required
        >
      </div>

      <!-- Screenshot -->
      <div>
        <label class="block text-sm text-gray-400 mb-1">Screenshot</label>
        <input
          type="file"
          name="screenshot"
          accept="image/*"
          class="w-full text-sm text-gray-300"
          required
        >
        <p class="text-xs text-gray-500 mt-1">
          JPG or PNG, max 2MB
        </p>
      </div>

      <!-- Button -->
      <button
        class="w-full bg-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition">
        Submit Score
      </button>

    </form>

  </div>

  <!-- Footer Action -->
  <div class="mt-4 text-sm text-gray-400">
    <a href="index.php" class="hover:text-gray-200 transition">
      ← Back to leaderboard
    </a>
  </div>

</div>

</body>
</html>