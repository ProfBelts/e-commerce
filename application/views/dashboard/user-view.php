<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
  
</head>
<body>
    <nav> 
        <ul> 
            <li>V88 Merchandise</li>
            <li><a href = "<?= base_url("dashboard") ?>">Dashboard</a></li>
            <li><a href = "#">Profile</a></li>
        </ul>
        <a class = "log_out" href = "<?= ('logout') ?>">Log-out</a>
    </nav>

    <main>
        <h1>All Products</h1>
    
        <table>
            <thead>
                <td>ID</td>
                <td>Name</td>
                <td>Price</td>
                <td>Inventory Count</td>
                <td>Quantity Sold</td>
            </thead>

            <tbody>
                <?php foreach($products as $product) { ?>
                        <tr>
                        <td>  <?= $product["id"] ?> </td>
                        <td><a href = "<?= base_url('products/show/' . $product['id']) ?>"><?= $product["name"] ?> </a> </td>
                        <td><?= $product["price"] ?> </td>
                        <td><?= $product["inventory_count"] ?> </td>
                        <td> <?= $product["quantity_sold"] ?> </td>
                    
                        </tr>
              <?php  } ?>
                
            </tbody>

        </table>
    </main>

</body>
</html>