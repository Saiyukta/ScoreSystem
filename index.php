<?php
include 'config.php';
$result = $conn->query("SELECT * FROM warriors ORDER BY score DESC");
?>

<!doctype html>
<html>
<head>
  <title>Leaderboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen p-8">

<div class="max-w-6xl mx-auto">

  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-semibold">Guitar Wars Leaderboard</h1>
    <p class="text-gray-400 text-sm">Verified high scores</p>
  </div>

  <!-- Table -->
  <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">

    <table class="w-full text-left">

      <!-- Head -->
      <thead class="bg-gray-700 text-gray-300 text-sm uppercase">
        <tr>
          <th class="p-4 w-16">#</th>
          <th class="p-4">Player</th>
          <th class="p-4">Score</th>
          <th class="p-4">Date</th>
          <th class="p-4">Proof</th>
        </tr>
      </thead>

      <!-- Body -->
      <tbody>
      <?php
      $rank = 1;

      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):

          $rowStyle = "";
          if ($rank <= 3) {
            $rowStyle = "bg-blue-950";
          }
      ?>

        <tr class="border-t border-gray-500 <?= $rowStyle ?> hover:bg-gray-700 transition">

          <td class="p-4 font-medium text-gray-400">
            <?= $rank++ ?>
          </td>

          <td class="p-4 font-semibold">
            <?= htmlspecialchars($row['name']); ?>
          </td>

          <td class="p-4 font-mono">
            <?= $row['score']; ?>
          </td>

          <td class="p-4 text-sm text-gray-400">
            <?= date("d M Y", strtotime($row['date'])); ?>
          </td>

          <td class="p-4">
            <?php if (!empty($row['screenshot'])): ?>
              <img 
                src="uploads/<?= $row['screenshot']; ?>"
                class="w-24 h-14 object-cover rounded border border-gray-600"
              >
            <?php else: ?>
              <span class="text-gray-500 text-sm">Unverified!</span>
            <?php endif; ?>
          </td>

        </tr>

      <?php
        endwhile;
      else:
      ?>

        <tr>
          <td colspan="5" class="p-6 text-center text-gray-500">
            No scores available
          </td>
        </tr>

      <?php endif; ?>
      </tbody>

    </table>

  </div>

  <!-- Action -->
  <div class="mt-6">
    <a href="addscore.php"
       class="inline-block bg-gray-700 px-5 py-2 rounded-lg text-sm hover:bg-gray-600 transition">
      Submit Score
    </a>
  </div>

</div>

</body>
</html>