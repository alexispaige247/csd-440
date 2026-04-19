<?php
$customers = [
    [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'age' => 30,
        'phone' => '123-456-7890',
    ],
    [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'age' => 25,
        'phone' => '987-654-3210',
    ],
    [
        'first_name' => 'Alice',
        'last_name' => 'Johnson',
        'age' => 28,
        'phone' => '555-123-4567',
    ],
    [
        'first_name' => 'Bob',
        'last_name' => 'Brown',
        'age' => 35,
        'phone' => '444-987-6543',
    ],
    [
        'first_name'=> 'Claire',
        'last_name' => 'Williams',
        'age' => 32,
        'phone' => '333-222-1111',
    ],
    [
        'first_name'=> 'Luke',
        'last_name'=> 'Bennet',
        'age' => 22,
        'phone' => '555-555-5555',
    ],
    [
        'first_name' => 'Emily',
        'last_name' => 'Davis',
        'age' => 64,
        'phone' => '666-777-8888',
    ],
    [
        'first_name' => 'Michael',
        'last_name' => 'Wilson',
        'age' => 46,
        'phone' => '777-888-9999',
    ],
    [
        'first_name' => 'Sarah',
        'last_name' => 'Miller',
        'age' => 78,
        'phone' => '888-999-0000',
    ],
    [
        'first_name' => 'David',
        'last_name' => 'Garcia',
        'age' => 41,
        'phone' => '999-000-1111',
    ],
];

function displayCustomers( string $title, array $records): void 
{
    echo "<h2>{$title}</h2>";

    if (empty($records)) {
        echo "<p>No matching customers found.</p>";
        return;
    }

    echo "<table border='1' cellpadding='8' cellspacing='0'>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Phone</th>
            </tr>";
    
    foreach ($records as $customer) {
        echo "<tr>
                <td>{$customer['first_name']}</td>
                <td>{$customer['last_name']}</td>
                <td>{$customer['age']}</td>
                <td>{$customer['phone']}</td>
            </tr>";
    }

    echo "</table>";
}

$customersNamedMaria = array_filter(
    $customers,
    fn(array $customer): bool => $customer['first_name'] === 'Maria'
    );

$customersOlderThan40 = array_filter(
    $customers,
    fn(array $customer): bool => $customer['age'] > 40
    );

$customersWithPhoneStartingWith555 = array_filter(
    $customers,
    fn(array $customer): bool => str_starts_with($customer['phone'], '555')
    );

$customerWithLastNameStartingWithB = array_filter(
    $customers,
    fn(array $customer): bool => str_starts_with($customer['last_name'], 'B')
    ); 

$customersWithAgeBetween25And35 = array_filter(
    $customers,
    fn(array $customer): bool => $customer['age'] >= 25 && $customer['age'] <= 35
    );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Filters</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Customer Records</h1>
    <p>Below are filtered lists of customers based on different criteria.</p>

    <?php
        displayCustomers('Customers Named Maria', $customersNamedMaria);
        displayCustomers('Customers Older Than 40', $customersOlderThan40);
        displayCustomers('Customers with Phone Starting with 555', $customersWithPhoneStartingWith555);
        displayCustomers('Customers with Last Name Starting with B', $customerWithLastNameStartingWithB);
        displayCustomers('Customers with Age Between 25 and 35', $customersWithAgeBetween25And35);
    ?>
</body>
</html>