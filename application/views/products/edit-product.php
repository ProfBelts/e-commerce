<body>
    <nav> 
        <ul> 
            <li>V88 Merchandise</li>
            <li><a href = "#">Dashboard</a></li>
            <li><a href = "#">Profile</a></li>
        </ul>
        <a class = "log_out" href = "#">Log-out</a>
    </nav>

    <main>
        <h1>Edit Product #<?= $product["id"] ?></h1>
        <a class="new_page" href = "#">Return to Dashboard</a>

        <form method = "POST" action = "<?= base_url("products/update") ?>">
            <input type ="hidden" name = "product_id" value = "<?= $product["id"] ?>" />

            <label for = "product_name">Name</label>
            <input type = "text" name = "product_name" value = "<?= $product["name"] ?>" />

            <label for = "description">Description</label>
            <input type = "text" name = "description" value="<?= $product["description"] ?>" />

            <label for = "product_name">Price</label>
            <input type = "text" name = "price" value="<?= $product["price"] ?>" />

            <label for = "product_name">Inventory Count</label>
            <input class="spinner" type="number" name="quantity" value="<?= $product["inventory_count"] ?>" min="1" max="1000">

            <input type = "submit" value = "Update" />
        </form>

    </main>
</body>
