document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('userForm');
    const userSelect = document.getElementById('user');
    const transactionsTable = document.getElementById('transactionsTable');
    const userNameSpan = document.getElementById('userName');
    const dataTable = document.getElementById("data");

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const user_id = userSelect.value;

        // AJAX-запрос
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `data.php?user=${user_id}`, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    userNameSpan.textContent = userSelect.options[userSelect.selectedIndex].text;
                    dataTable.style.display = "block";

                    transactionsTable.innerHTML = '<tr><th>Mounth</th><th>Amount</th><th>Count</th></tr>';

                    response.transactions.forEach(transaction => {
                        const date = new Date(transaction.month);
                        const monthName = date.toLocaleString('default', { month: 'long' });
                        const fUpperCaseMonthName = monthName.charAt(0).toUpperCase() + monthName.slice(1);

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${fUpperCaseMonthName}</td>
                            <td>${transaction.monthly_balance}</td>
                            <td>${transaction.transaction_count}</td>
                        `;
                        transactionsTable.appendChild(row);
                    });
                } else {
                    alert('Ошибка: ' + response.message);
                }
            } else {
                alert('Ошибка при выполнении запроса');
            }
        };

        xhr.onerror = function () {
            alert('Ошибка сети');
        };

        xhr.send();
    });
});