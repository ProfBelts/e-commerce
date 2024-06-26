<?php 

class Users extends CI_Controller{
    
    // This function is triggered to display the sign-in page. 
    public function index()
    {
        $view_data = array(
            "errors" => $this->session->flashdata("errors")
        );

        $this->load->view("templates/header");
        $this->load->view("users/login", $view_data);
    }

    // 
    public function register()
    {
        $view_data = array(
            "errors" => $this->session->flashdata("errors")
        );


        $this->load->view("templates/header");
        $this->load->view("users/register", $view_data);
    }


    public function edit_profile()
    {   
        $this->load->model("user");
        $user = $this->session->userdata("user");
        $email = $user["email"];

        $user_info = $this->user->get_user_by_email($email);
    
        $view_data = array(
            "user" => $user_info
        );
        $this->load->view("users/profile", $view_data);
    }

    // This function is used to handle login

    public function process_login()
    {
        $this->load->model("user");
        
        $result = $this->user->validate_login();

        if($result !== "Success")
        {   
            $this->session->set_flashdata("errors", $result);
            redirect("/");
            // var_dump($result);
        } else 
        {
            $email = $this->input->post("email");
            $user_info = $this->user->get_user_by_email($email);

           $credentials = $this->user->match_login($user_info, $this->input->post("password"));


            if($credentials == "Success")
            {
                $user_data = array(
                    "is_admin" => $user_info["is_admin"],
                    "email" => $user_info["email_address"],
                    "user_id" => $user_info["id"],
                    "first_name" => $user_info["first_name"],
                    "last_name" => $user_info["last_name"]
                );
                $this->session->set_userdata("user", $user_data);
                if($user_info["is_admin"] == 1) {
                    redirect("dashboard/admin");
                } else {
                    redirect("dashboard");
                }
                

            } else {
               $this->session->set_flashdata("errors", $credentials);
               redirect("/");
            }    
        }
    }



    // This function is triggered to handle the registration.
    public function process_registration()
    {
        $this->load->model("user");
        // $post = $this->input->post();

        $result = $this->user->validate_registration();

        if($result != null) 
        {
            $this->session->set_flashdata("errors", $result);
            redirect(base_url("users/register"));
        } 
        else 
        {
            $form_data = $this->input->post();
            $this->user->create_user($form_data);

            redirect("/");

        }
}


    public function process_edit_profile()
    {

        $this->load->model("user");
        $user = $this->session->userdata("user");


        $result = $this->user->validate_edit_profile($this->input->post());

        if($result == "Same Email") 
        {
            redirect(base_url("users/edit_profile"));

        } elseif ($result == "New Email") {
            redirect(base_url("users/edit_profile"));
        } else {
           var_dump($result);
        }

    }


    public function process_edit_password()
    {
        
        $this->load->model("user");
        // Get the user info via email 
        $email = $this->input->post("user_email");
        $user_info = $this->user->get_user_by_email($email);
       
        $result = $this->user->validate_old_password();

        if($result !== null) 
        {
            var_dump($result);
        } else 
        {   
            // If the credentials matched proceed to the validation of new password.
            $credentials = $this->user->match_login($user_info, $this->input->post("old_password"));

            if($credentials !== "Success")
            {
                echo "password not matched";
            } else 
            {
                // If new password does not pass validation throw error. 
                $new_password = $this->user->validate_new_password($this->input->post());

                if($new_password !== null)
                {
                    var_dump($new_password);

                // If the password pass validation, check if the password is different from the old password.
                } else 
                {
                    $credentials = $this->user->check_new_password($this->input->post());

                    if($credentials == "Same password")
                    {
                        echo "enter new password";
                    } else {
                        $this->user->change_password($this->input->post());
                        redirect(base_url("users/edit_profile"));
                    }
                }
            }
        }


      

        // $result = $this->user->validate_password_change($this->input->post());

        // if($result !== null)
        // {
        //     var_dump($result);
        // } 
        // else {
        //    $user = $this->session->userdata("user");


        // }
        
    

    }




}
?>