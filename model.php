<?php

/**
 * Список пользователей
 */
function get_users($conn)
{
    $statement = $conn->query('SELECT * FROM `users`');
    $users = array();
    while ($row = $statement->fetch()) {
        $users[$row['id']] = $row['name'];
    }
    return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn)
{
    $data = $conn->query(""
        ." SELECT"
            ." strftime('%Y-%m', transactions.trdate) AS month,"
            ." SUM(CASE WHEN ua_to.user_id = $user_id THEN transactions.amount ELSE 0 END) - "
            ." SUM(CASE WHEN ua_from.user_id = $user_id THEN transactions.amount ELSE 0 END) AS monthly_balance,"
            ." COUNT(transactions.id) AS transaction_count"
        ." FROM transactions"
            ." JOIN user_accounts ua_from ON transactions.account_from = ua_from.id"
            ." JOIN user_accounts ua_to ON transactions.account_to = ua_to.id"
        ." WHERE"
            ." ua_from.user_id = $user_id OR ua_to.user_id = $user_id"
        ." GROUP BY month"
        ." ORDER BY month;"
    );
    $transactions = array();
    while ($row = $data->fetch()) {
        $transactions[] = $row;
    }
    return $transactions;
}
