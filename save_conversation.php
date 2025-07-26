<?php

error_log("Starting save_conversation.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "error" => "Method not allowed",
        "method_required" => "POST"
    ]);
    exit;
}

// Read input
$input = file_get_contents('php://input');

// Log input size
error_log("Input size: " . strlen($input));

// Check if input is available
if ($input === false) {
    error_log("Failed to read input");
    echo json_encode([
        "error" => "Failed to read input"
    ]);
    exit;
}

// Decode JSON
$conversation = json_decode($input, true);

if (!isset($conversation['session_id'])) {
    error_log("Missing required key 'session_id'");
    echo json_encode([
        "error" => "Missing required key 'session_id'"
    ]);
    exit;
}

// Database connection
try {
    $servername = "localhost";
    $username = "emelda_ma";
    $dbname = "emeldama_bot";
    $password = "UgWZjbrg72MTDvzS7WUq";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query
    $stmt = $conn->prepare("INSERT INTO conversations (session_id, role, content) VALUES (?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $session_id = $conversation['session_id'] ?? 'default_session';
    $role = isset($conversation['role']) ? $conversation['role'] : null;
    $content = isset($conversation['content']) ? $conversation['content'] : null;

    if (!$stmt->bind_param("sss", $session_id, $role, $content)) {
        throw new Exception("Binding parameters failed: (" . $conn->errno . ") " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: (" . $conn->errno . ") " . $conn->error);
    }

    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    if ($affected_rows > 0) {
        echo json_encode([
            "success" => true,
            "rows_affected" => $affected_rows
        ]);
    } else {
        throw new Exception("No rows affected");
    }

} catch (Exception $e) {
    error_log("Database operation failed: " . $e->getMessage());
    echo json_encode([
        "error" => "Database operation failed: " . $e->getMessage()
    ]);
}

// Close database connection
$conn->close();
?>
