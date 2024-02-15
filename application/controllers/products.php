<?php 

class Products extends CI_Controller
{
    public function new_product()
    {
        $current_user = $this->session->userdata("user");

        if($current_user["is_admin"] == 1) 
        {
            $this->load->view("templates/products");
            $this->load->view("products/new-product");
        } 
        else{
            redirect("/");
        }
    }

    public function show($id)
    {   
        $this->load->model("product");
        $this->load->model("review");
        
        $product = $this->product->get_product_by_id($id);
        $user = $this->session->userdata("user");
        $reviews = $this->review->show_reviews($id);
        
        $review_id = $this->session->userdata("review_id");

    

        if (!$this->session->userdata("reply")) {
            $this->session->set_userdata("reply", FALSE);
        }

        $view_data = array(
            "product" => $product,
            "user_id" => $user["user_id"],
            "reviews" => $reviews,
            "reply" => $this->session->userdata("reply")
        );

        $this->load->view("templates/show");
        $this->load->view("products/show", $view_data);
    }

    public function process_new_product()
    {
        $current_user = $this->session->userdata("user");

        if($current_user)

       $this->load->model("product");
       $this->product->create_product($this->input->post());
       redirect("products/new_product");
    }


    public function edit($id)
    {
        $this->load->model("product");

        $product = $this->product->get_product_by_id($id);
        
        $current_user = $this->session->userdata("user");

        if($current_user["is_admin"] == 1)
        {
            $view_data = array(
                "product" => $product
            );   

            $this->load->view("templates/products");
            $this->load->view("products/edit-product", $view_data);

        } else{
            redirect("/");
        }

    }

    public function update()
    {
        $this->load->model("product");
        
       $this->product->update_product($this->input->post());
       
       redirect("dashboard/admin");

    
    }

    public function delete($id)
    {
        $current_user = $this->session->userdata("user");

        if($current_user["is_admin"] == 1)
        {
            $this->load->model("product");
            
            $product = $this->product->get_product_by_id($id);

            $view_data = array(
                "product" => $product
            );

            $this->load->view("products/delete-product", $view_data);
        }
        else{
            redirect("/");
        }
    }

    public function process_deletion()
    {   
        $this->load->model("product");
        $id = $this->input->post("product_id");

        $this->product->delete_product($id);

        redirect("dashboard/admin");

    }

}

?>






