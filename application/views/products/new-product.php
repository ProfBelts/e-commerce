<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Product</title>
    <link rel="stylesheet" href="products.css" />
</head>
<body>
    <nav> 
        <ul> 
            <li>V88 Merchandise</li>
            <li><a href = "#">Dashboard</a></li>
            <li><a href = "#">Profile</a></li>
        </ul>
        <a class = "log_out" href = "<?=('/') ?>">Log-out</a>
    </nav>

    <main>
        <h1>Add a new Product</h1>
        <a class="new_page" href = "<?= base_url('dashboard/admin') ?>"> Return to Dashboard</a>

        <form method = "POST" action = "<?=('process_new_product') ?>">
            <label for = "product_name">Name</label>
            <input type = "text" name = "product_name" />

            <label for = "description">Description</label>
            <input type = "text" name = "description" />

            <label for = "product_name">Price</label>
            <input type = "text" name = "price" />

            <label for = "product_name">Inventory Count</label>
            <input class="spinner" type="number" name="quantity" value="1" min="1" max="1000">

            <input type = "submit" value = "Create" />
        </form>
    </main>



</body>
</html>