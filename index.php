<?php
session_start();

// Initialize data in session if not set
if (!isset($_SESSION['records'])) {
    $_SESSION['records'] = [];
    for ($i = 1; $i <= 120; $i++) {
        $_SESSION['records'][] = [
            'id' => $i,
            'name' => "Name $i",
            'email' => "user$i@example.com",
            'phone' => "1234567890",
            'gender' => ($i % 2 == 0) ? 'Male' : 'Female',
        ];
    }
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $newId = count($_SESSION['records']) + 1;
    $_SESSION['records'][] = [
        'id' => $newId,
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'gender' => $_POST['gender'],
    ];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
    foreach ($_SESSION['records'] as &$record) {
        if ($record['id'] == $_POST['id']) {
            $record['name'] = $_POST['name'];
            $record['email'] = $_POST['email'];
            $record['phone'] = $_POST['phone'];
            $record['gender'] = $_POST['gender'];
            break;
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $idToDelete = (int) $_GET['delete'];
    $_SESSION['records'] = array_filter($_SESSION['records'], function ($item) use ($idToDelete) {
        return $item['id'] !== $idToDelete;
    });
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Pagination
$recordsPerPage = 10;
$totalRecords = count($_SESSION['records']);
$totalPages = ceil($totalRecords / $recordsPerPage);
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $recordsPerPage;
$displayRecords = array_slice($_SESSION['records'], $startIndex, $recordsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Responsive CRUD Table</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1 class="text-2xl font-bold mb-4">Responsive CRUD Table</h1>

    <!-- Add/Edit Form -->
    <form id="recordForm" action="" method="POST" class="mb-6 space-y-2">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="">
        <div>
            <label class="block text-sm">Name:</label>
            <input type="text" name="name" class="border p-2 w-full" required>
            <span class="text-red-500 text-sm error-message"></span>
        </div>
        <div>
            <label class="block text-sm">Email:</label>
            <input type="email" name="email" class="border p-2 w-full" required>
            <span class="text-red-500 text-sm error-message"></span>
        </div>
        <div>
            <label class="block text-sm">Phone:</label>
            <input type="number" name="phone" class="border p-2 w-full" required>
            <span class="text-red-500 text-sm error-message"></span>
        </div>
        <div>
            <label class="block text-sm">Gender:</label>
            <select name="gender" class="border p-2 w-full" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <span class="text-red-500 text-sm error-message"></span>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-2 py-1 sticky left-0 bg-white">#</th>
                    <th class="border px-2 py-1">Name</th>
                    <th class="border px-2 py-1">Email</th>
                    <th class="border px-2 py-1">Phone</th>
                    <th class="border px-2 py-1">Gender</th>
                    <th class="border px-2 py-1">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($displayRecords as $record): ?>
                    <tr>
                        <td class="border px-2 py-1 sticky left-0 bg-white"><?= $record['id'] ?></td>
                        <td class="border px-2 py-1"><?= htmlspecialchars($record['name']) ?></td>
                        <td class="border px-2 py-1"><?= htmlspecialchars($record['email']) ?></td>
                        <td class="border px-2 py-1"><?= htmlspecialchars($record['phone']) ?></td>
                        <td class="border px-2 py-1"><?= htmlspecialchars($record['gender']) ?></td>
                        <td class="border px-2 py-1 space-x-2">
                            <button class="edit-btn text-blue-500 hover:underline" data-id="<?= $record['id'] ?>">Edit</button>
                            <a href="?delete=<?= $record['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="px-3 py-1 border <?= ($i == $currentPage) ? 'bg-blue-500 text-white' : 'bg-white text-blue-500' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
