<body>
    <nav> 
        <h3>V88 Merchandise</h3>
        <a href = "<?= ("users/register") ?>">Register</a>
    </nav>

    <main>
        <h2>Log-In</h2>

        <form method = "POST" action = <?= ("users/process_login") ?>> 
            <label for = "email">Email:</label>
            <input type = "text" name = "email" placeholder="Email" />
            <label for = "password">Password:</label>
            <input type = "password" name = "password" />
            <input type = 'submit' value = "Submit" />
        </form>

        <a class = "account"  href = "<?= ("users/register") ?>">Don't have an account? Register</a>
    </main>
</body>