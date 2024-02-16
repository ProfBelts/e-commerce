<?php 

class Users extends CI_Controller{
    
    // This function is triggered to display the sign-in page. 
    public function index()
    {
        $this->load->view("templates/header");
        $this->load->view("users/login");
    }

    // 
    public function register()
    {
        $this->load->view("templates/header");
        $this->load->view("users/register");
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
            var_dump($result);
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
                    "last_name" => $user_info["last_name"],
                    "old_password" => $user_info["password"]
                );
                $this->session->set_userdata("user", $user_data);
                if($user_info["is_admin"] == 1) {
                    redirect("dashboard/admin");
                } else {
                    redirect("dashboard");
                }
                

            } else {
               var_dump($credentials);
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
            var_dump($result);
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
            $this->user->edit_profile($this->input->post(), $user["user_id"]);
            
            
            redirect(base_url("users/edit_profile"));

        } elseif ($result == "New Email") {

            echo "New Email";
        } else {
           var_dump($result);
        }

    }


}
?>