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


    // This function is used to check the credentials of the input of the user.
    
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
   

    // This function handles the validation of registration.
    public function validate_registration()
    {
        $this->load->library("form_validation");
        $config = $this->registration_config();

        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE)
        {   

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
        $config = $this->edit_profile_config();

        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE)
        {
            return array(validation_errors());
        } else {

            $email = $post["email"];
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
        $this->load->model("user");
        
        return array(
            array(
                "field" => "email",
                "label" => "Email",
                "rules" => "required|valid_email|callback_check_email",
                "errors" => array(
                    'required' => "You must provide a %s",
                    "valid_email" => "Email must be valid!",
                    "check_email" => "Email already exists."
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
            )
        );
    }
    
    public function check_email($email)
    {
        // Get the current user's email
        $user = $this->session->userdata("user");
        $current_email = $user['email'];
    
        // If the provided email is the same as the current email, return true (validation passes)
        if ($email == $current_email) {
            return true;
        }
    
        // Check if the email already exists in the database
        $existing_user = $this->user->get_user_by_email($email);
    
        if ($existing_user) {
            // Email already exists
            return false;
        } else {
            // Email does not exist
            return true;
        }
    }
    

}


?>