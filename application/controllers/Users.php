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
                    "user_id" => $user_info["id"],
                    "first_name" => $user_info["first_name"],
                    "last_name" => $user_info["last_name"]
                );
                $this->session->set_userdata("user", $user_data);
                if($user_info["is_admin"] == 1) {
                    redirect("dashboard/admin");
                } else {
                    echo "User";
                }
                

            } else {
               var_dump($credentials);
            }

            // var_dump($user_info);
          



        //     if($credentials == "Success")
        //     {
        //         $this->session->set_userdata(array("user_id" => $user_info["id"], "first_name" => $user_info["first_name"], "last_name" => $user_info["last_name"], "is_admin" => $user_info["is_admin"]));
        //         if($user_info["is_admin"] == 1) {
        //             redirect("dashboard/admin");
        //         } else {
        //             redirect("dashboard");
        //         }
        //     }
           
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

}
?>

