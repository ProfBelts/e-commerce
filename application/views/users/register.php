<body>
    <nav> 
        <h3>V88 Merchandise</h3>
        <a href="<?= ('/') ?>">Log-in</a>
    </nav>

    <main>
    <h2>Register</h2>

    <form method="POST" action="<?= base_url("users/process_registration") ?>">
        <label for="email">Email:</label>
        <input type="text" name="email" placeholder="Email" />

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" placeholder="Enter your First Name..." required/>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" placeholder="Enter your Last name..." required/>
            
        <label for="password">Password:</label>
        <input type="password" name="password" />

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" />

        <input type="submit" value="Register" />
    </form>

    <a href="<?= base_url('/') ?>">Already have an account? Log-in</a>
</main>

</body>