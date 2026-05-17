<?php
// Alexis JSON.php

$errors = [];
$jsonOutput = '';
$formData = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'street_address' => '',
    'city' => '',
    'state' => '',
    'zip_code' => '',
];

/**
 * @param string $value Text to escape.
 * @return string Escaped text.
 */
function clean_output($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Reads and trims a submitted form value.
 *
 * @param string $fieldName Name of the form field.
 * @return string Trimmed submitted value or an empty string.
 */
function get_post_value($fieldName)
{
    return isset($_POST[$fieldName]) ? trim($_POST[$fieldName]) : '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $fieldName => $defaultValue) {
        $formData[$fieldName] = get_post_value($fieldName);
    }

    $fieldLabels = [
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email address',
        'phone' => 'Phone number',
        'street_address' => 'Street address',
        'city' => 'City',
        'state' => 'State',
        'zip_code' => 'ZIP code',
    ];

    foreach ($fieldLabels as $fieldName => $label) {
        if ($formData[$fieldName] === '') {
            $errors[] = $label . ' is required.';
        }
    }

    if ($formData['email'] !== '' && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email address must be in a valid format.';
    }

    if ($formData['zip_code'] !== '' && !preg_match('/^\d{5}(-\d{4})?$/', $formData['zip_code'])) {
        $errors[] = 'ZIP code must use the 12345 or 12345-6789 format.';
    }

    if (empty($errors)) {
        $jsonOutput = json_encode($formData, JSON_PRETTY_PRINT);

        if ($jsonOutput === false) {
            $errors[] = 'The submitted data could not be converted to JSON.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alexis JSON Form</title>
    <style>
        :root {
            color-scheme: light;
            --background: #f3f6f8;
            --panel: #ffffff;
            --text: #1e2932;
            --muted: #5e6b75;
            --accent: #0f7a6c;
            --accent-dark: #09584d;
            --error-bg: #fff1f1;
            --error-border: #c83f49;
            --json-bg: #172126;
            --json-text: #e8f3f1;
            --border: #ccd7dd;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--background);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
        }

        main {
            width: min(920px, calc(100% - 32px));
            margin: 32px auto;
        }

        .page-title {
            margin: 0 0 18px;
            font-size: 2rem;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 12px 28px rgba(29, 45, 56, 0.08);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
        }

        input {
            width: 100%;
            min-height: 44px;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 12px;
            color: var(--text);
            font-size: 1rem;
        }

        input:focus {
            border-color: var(--accent);
            outline: 3px solid rgba(15, 122, 108, 0.18);
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 22px;
        }

        button,
        .reset-link {
            min-height: 44px;
            border-radius: 6px;
            padding: 10px 18px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            border: 0;
            background: var(--accent);
            color: #ffffff;
        }

        button:hover {
            background: var(--accent-dark);
        }

        .reset-link {
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--text);
        }

        .message {
            margin-top: 24px;
            border-radius: 8px;
            padding: 18px;
        }

        .error {
            border: 1px solid var(--error-border);
            background: var(--error-bg);
        }

        .error h2,
        .result h2 {
            margin: 0 0 10px;
            font-size: 1.25rem;
        }

        .error ul {
            margin: 0;
            padding-left: 22px;
        }

        .result {
            border: 1px solid var(--accent);
            background: #eefaf7;
        }

        pre {
            overflow-x: auto;
            margin: 0;
            border-radius: 8px;
            background: var(--json-bg);
            color: var(--json-text);
            padding: 18px;
            font-size: 1rem;
            white-space: pre-wrap;
        }

        .hint {
            margin: 0 0 22px;
            color: var(--muted);
        }

        @media (max-width: 700px) {
            main {
                width: min(100% - 20px, 920px);
                margin: 20px auto;
            }

            .panel {
                padding: 18px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <main>
        <h1 class="page-title">Alexis JSON Form</h1>

        <section class="panel" aria-labelledby="form-heading">
            <h2 id="form-heading">Enter Your Information</h2>
            <p class="hint">All eight fields are required. Submitted data will be displayed in JSON format.</p>

            <form method="post" action="<?php echo clean_output($_SERVER['PHP_SELF']); ?>">
                <div class="form-grid">
                    <div>
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo clean_output($formData['first_name']); ?>" required>
                    </div>

                    <div>
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo clean_output($formData['last_name']); ?>" required>
                    </div>

                    <div>
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo clean_output($formData['email']); ?>" required>
                    </div>

                    <div>
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo clean_output($formData['phone']); ?>" required>
                    </div>

                    <div class="full-width">
                        <label for="street_address">Street Address</label>
                        <input type="text" id="street_address" name="street_address" value="<?php echo clean_output($formData['street_address']); ?>" required>
                    </div>

                    <div>
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?php echo clean_output($formData['city']); ?>" required>
                    </div>

                    <div>
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="<?php echo clean_output($formData['state']); ?>" maxlength="2" required>
                    </div>

                    <div>
                        <label for="zip_code">ZIP Code</label>
                        <input type="text" id="zip_code" name="zip_code" value="<?php echo clean_output($formData['zip_code']); ?>" required>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit">Submit Form</button>
                    <a class="reset-link" href="<?php echo clean_output($_SERVER['PHP_SELF']); ?>">Clear Form</a>
                </div>
            </form>

            <?php if (!empty($errors)) : ?>
                <div class="message error" role="alert">
                    <h2>Error Display</h2>
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?php echo clean_output($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($jsonOutput !== '') : ?>
                <div class="message result" role="status">
                    <h2>JSON Output Display</h2>
                    <pre><?php echo clean_output($jsonOutput); ?></pre>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>