<?php
include_once('db.php');
include_once('model.php');

$conn = get_connect();
$user_id = isset($_GET['user']) ? (int)$_GET['user'] : null;

if ($user_id) {
    // Получаем данные о транзакциях
    $transactions = get_user_transactions_balances($user_id, $conn);

    // Возвращаем данные в формате JSON
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'user_id' => $user_id,
        'transactions' => $transactions
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'user_id не был передан'
    ]);
}
