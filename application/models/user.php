<?php 

class User extends CI_Model
{
    // IThis function is used to create users.
    public function create_user($user)
    {
    $password = $user["password"];

    $salt = bin2hex(openssl_random_pseudo_bytes(22));
    $combined_password = $password . $salt;
    $encrypted_password = md5($combined_password);

    $query = "INSERT INTO users (email_address, first_name, last_name, password, salt, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

    $values = array(
        $user["email"],
        $user["first_name"],
        $user["last_name"],
        $encrypted_password,
        $salt,
        0
    );
    // Count existing users
    $existing_users_result = $this->db->query("SELECT COUNT(*) as user_count FROM users");
    $existing_users_row = $existing_users_result->row();
    $existing_users_count = $existing_users_row->user_count;

    // Check if there are any existing users
    if ($existing_users_count == 0) 
    {
        $values[5] = 1; // Set is_admin to 1 for the first user
    }

    // Execute the query
    return $this->db->query($query, $values);
    }

    // This function is used to get the user info by email. 
    public function get_user_by_email($email)
    {
        $query = "SELECT * FROM users WHERE email_address = ?";
        
        return $this->db->query($query, array($email))->result_array()[0];
    }

    public function get_user_by_id($id)
    {
        $query = "SELECT * from users where ID = ?";

        return $this->db->query($query, array($id))->result_array();
    }


    // This function is used to check the credentials of the input of the user.
    // Password came from the form
    public function match_login($user, $password)
    {
        $encrypted_password = md5($password . $user['salt']);
    
        // Verify the input password against the hashed password
        if ($user["password"] == $encrypted_password) {
            return "Success"; // Passwords match
        } else {
            return "Invalid Credentials"; // Passwords don't match
        }
    }
   
    public function edit_profile($post, $id)
    {
        $this->db->set('email_address', $post["email"]);
        $this->db->set('first_name', $post["first_name"]);
        $this->db->set('last_name', $post["last_name"]);
        $this->db->where('ID', $id);
        $this->db->update('users');

        $updated_profile = $this->get_user_by_id($id);

        return $updated_profile["email_address"];

    }
    

    public function change_password($post)
    {
    $password = $post["new_password"];
    $salt = bin2hex(openssl_random_pseudo_bytes(22));
    $combined_password = $password . $salt;
    $encrypted_password = md5($combined_password);

    $this->db->set('password', $encrypted_password);
    $this->db->set("salt", $salt);
    $this->db->where('email_address', $post["user_email"]);
    $success = $this->db->update('users');

    return $success;
    }


    public function validate_old_password()
    {
        $this->load->library("form_validation");

        $config = array(
            array(
                "field" => "old_password",
                "label" => "Old Password",
                "rules" => "required",
                "errors" => array(
                    "required" => "Please input your old password."
                )
            )
                );

        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE) 
        {
            return array(validation_errors());
        }

    }
    

    public function validate_new_password($post)
    {

        $old_password = $post["old_password"];

        $this->load->library("form_validation");

        $config = $this->new_password_config($old_password);

        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE)
        {
            return array(validation_errors());
        } 
        
    }

 
    
    // This function handles the validation of registration.
    public function validate_registration()
    {
        $this->load->library("form_validation");
        $config = $this->registration_config();

        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE)
        {   
            $this->session->set_userdata("errors", array(validation_errors()));
            return array(validation_errors());

        } 
    }


    public function validate_login()
    {
        $this->load->library("form_validation");
        $config = $this->login_config();

        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE)
        {
            return array(validation_errors());
        } else {
            return "Success";
        }

    }


    public function validate_edit_profile($post)
    {

        $this->load->library("form_validation");
        $user = $this->session->userdata('user');
        $current_email = $user["email"];
        

        $config = $this->edit_profile_config();

        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE)
        {
            return array(validation_errors());
        }
        elseif ($post["email"] == $current_email ) 
        {
            // Store message in session
            $query_result = $this->edit_profile($post, $user["user_id"]);
            
            $updated_profile = $this->get_user_by_email($query_result);
            
            $user["first_name"] = $post["first_name"];

            $user["last_name"] = $post["last_name"];

            $this->session->set_userdata("user", $user);

            return "Same Email";
        } elseif ($post["email"] !== $current_email) {
            $this->edit_profile($post, $user["user_id"]);


            $user["email"] = $post["email"];
            $user["first_name"] = $post["first_name"];
            $user["last_name"] = $post["last_name"];

            $this->session->set_userdata("user", $user);

            return "New Email";
        }
    }
    
    public function registration_config()
    {
        return array(
            array(
                "field" => "email",
                "label" => "Email", 
                "rules" => "required|valid_email|is_unique[users.email_address]",
                "errors" => array(
                    'required' => "You must provide a %s",
                    "valid_email" => "Email must be valid!",
                    "is_unique" => "Email is already taken."
                )
                ),
            array(
                "field" => "first_name", 
                "label" => "First Name", 
                "rules" => "required|regex_match[/^[a-zA-Z\s]+$/]",
                'errors' => array(
                    "required" => "You must provide a %s",
                    "regex_match" => "Name should not contain non-alphabetical characters."
                )
                ),
            array(
                "field" => "last_name",
                "label" => "Last Name",
                "rules" => "required|regex_match[/^[a-zA-Z\s]+$/]",
                'errors' => array(
                    "required" => "You must provide a %s",
                    "regex_match" => "Name should not contain non-alphabetical characters."
                )
                ),
            array(
                "field" => "password",
                "label" => "Password",
                "rules" => 'required|min_length[8]',
                'errors' => array(
                    "required" => "You must provide a %s",
                    "min_length" => "Password must be at least 8 characters long."
                )
                ), 
            array(
                "field" => "confirm_password",
                "label" => "Confirm Password",
                "rules" => "required|matches[password]", 
                "errors" => array(
                    "required" => "Please confirm your password.", 
                    "matches" => "Password does not match."
                )
            )
        );
    }

    public function login_config()
    {
        return array(
            array(
                "field" => "email",
                "label" => "Email", 
                "rules" => "required|valid_email",
                "errors" => array(
                    "required" => "Please provide an email.",
                    "valid_email" => "Email invalid!"
                )
            ),
            array(
                "field" => "password",
                "label" => "Password",
                "rules" => "required",
                "errors" => array(
                    "required" => "Please input your password."
                )
            )
        );
    }
    
    
    public function edit_profile_config()
    {
    return array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => "required|valid_email",
            'errors' => [
                'required' => 'You must provide a %s.',
                'valid_email' => 'Email must be valid!' 
            ]
        ),
        array(
            "field" => "first_name", 
            "label" => "First Name", 
            "rules" => "required|regex_match[/^[a-zA-Z\s]+$/]",
            'errors' => array(
                "required" => "You must provide a %s",
                "regex_match" => "Name should not contain non-alphabetical characters."
            )
        ),
        array(
            "field" => "last_name",
            "label" => "Last Name",
            "rules" => "required|regex_match[/^[a-zA-Z\s]+$/]",
            'errors' => array(
                "required" => "You must provide a %s",
                "regex_match" => "Name should not contain non-alphabetical characters."
            )
        )
    );
    }

    public function new_password_config()
{
    return array(
        array(
            "field" => "new_password",
            "label" => "New Password",
            "rules" => "required|min_length[8]",
            'errors' => array(
                "required" => "You must provide a %s",
                "min_length" => "Password must be at least 8 characters long."
                
            )
        ),
        array(
            "field" => "confirm_password",
            "label" => "Confirm Password",
            "rules" => "required|matches[new_password]",
            "errors" => array(
                "required" => "You must provide a %s",
                "matches" => "Password does not match."
            )
        )
    );
}

public function check_new_password($post)
{
    $user = $this->session->userdata("user");  
    $user_info = $this->get_user_by_email($user["email"]);

   $result =  $this->match_login($user_info, $post["new_password"]);

    if($result == "Success")
    {
        return "Same password";
    } 

}



}


?>