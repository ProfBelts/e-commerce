<?php 

class Dashboard extends CI_Controller
{
    public function index()
    {
        echo "Dashboard";
    }


    public function admin()
    {
        $this->load->model("product");
        $current_user = $this->session->userdata("user");    
    
        if($current_user["is_admin"] == 1) {
            $view_data = array(
                "products" => $this->product->show_all_product()
            );
    
            $this->load->view("templates/dashboard");
            $this->load->view("dashboard/admin-view", $view_data);
        } 
        else {
            redirect("/");
        }

        
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect("/");
    }


    

}

?>