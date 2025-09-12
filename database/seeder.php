<?php
require_once 'dbrequire.php';

// Create default admin user
function createDefaultAdmin()
{
    global $mysql;

    // Check if admin already exists
    $stmt = $mysql->prepare("SELECT id FROM user WHERE email = 'admin@laptopstore.com' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Create admin user
        $name = 'Admin';
        $email = 'admin@laptopstore.com';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $role = 'admin';

        $stmt = $mysql->prepare("INSERT INTO user (name, email, password, confirm_password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $name, $email, $password, $password, $role);

        if ($stmt->execute()) {
            echo "Default admin user created successfully!\n";
            echo "Email: admin@laptopstore.com\n";
            echo "Password: admin123\n";
        } else {
            echo "Failed to create admin user: " . $mysql->error . "\n";
        }
    } else {
        echo "Admin user already exists!\n";
    }
}

// Create default customer user
function createDefaultCustomer()
{
    global $mysql;

    // Check if customer already exists
    $stmt = $mysql->prepare("SELECT id FROM user WHERE email = 'customer@laptopstore.com' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Create customer user
        $name = 'Customer';
        $email = 'customer@laptopstore.com';
        $password = password_hash('customer123', PASSWORD_DEFAULT);
        $role = 'customer';

        $stmt = $mysql->prepare("INSERT INTO user (name, email, password, confirm_password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $name, $email, $password, $password, $role);

        if ($stmt->execute()) {
            echo "Default customer user created successfully!\n";
            echo "Email: customer@laptopstore.com\n";
            echo "Password: customer123\n";
        } else {
            echo "Failed to create customer user: " . $mysql->error . "\n";
        }
    } else {
        echo "Customer user already exists!\n";
    }
}

// Run seeder
if (php_sapi_name() === 'cli') {
    echo "Running database seeder...\n";
    createDefaultAdmin();
    createDefaultCustomer();
    echo "Seeder completed!\n";
} else {
    // Web interface
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Database Seeder</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Database Seeder</h3>
                        </div>
                        <div class="card-body">
                            <pre><?php
                                    echo "Running database seeder...\n";
                                    createDefaultAdmin();
                                    createDefaultCustomer();
                                    echo "Seeder completed!\n";
                                    ?></pre>
                            <a href="../login.php" class="btn btn-primary">Go to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
}
?>