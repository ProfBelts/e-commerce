<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel = "stylesheet" href = "<?= base_url("public/css/edit-profile.css"); ?>" />
</head>
<body>
    <nav>
        <ul> 
            <li>V88 Merchandise</li>
            <?php 
            $user = $this->session->userdata("user"); 
            if($user["is_admin"] == 1) { ?> 
                <li><a href = "<?= base_url('dashboard/admin') ?>">Dashboard</a></li>
        <?php } else { ?>
            <li><a href = "<?= base_url('dashboard') ?>">Dashboard</a></li>
        <?php }   ?>
            
                <li><a href = "<?= base_url("users/edit_profile") ?>">Profile</a></li>
        </ul>
        <a class = "log_out" href = "#">Log-out</a>
    </nav>

    <main>

            <section class="information">
                <h2>Edit Profile</h2>
            <form method = "POST" action = "<?= base_url("users/process_edit_profile") ?>"> 
                <label for = "email">Email:</label>
                <input type = "text" name = "email" value = "<?=$user["email"]?>"/>
                <label for = "first_name">First Name:</label>
                <input type = "text" name = "first_name" value = "<?= $user['first_name'] ?>" />
                <label for = "last_name">Last Name:</label>
                <input type = "text" name = "last_name" value = "<?= $user["last_name"] ?>"/>

                <input type = "submit" value = "Save" />
            </form>
            </section>

        
          <section>
            <h2>Edit Password</h2>
        <form>
            
            <label for = "old_password">Old Password:</label>
            <input type = "password" name = "old_password" />

            <label for = "new_password">New Password:</label>
            <input type = "password" name = "new_password" />

            <label for = "confirm_password">Confirm Password:</label>
            <input type = "password" name = "confirm_password" />


            <input type = 'submit' value = "Save" />
        </form>
          </section>
            
           
    </main>

</body>
</html>