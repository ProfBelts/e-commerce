<?php 

class Reviews extends CI_Controller
{
    public function process_review($id)
    {
        $this->load->model("review");
        
        $result = $this->review->validate_review();


        $post = $this->input->post();

        if($result != null) 
        {
            var_dump($result);
        } else {
            $this->review->insert_review($this->input->post());
            redirect("products/show/". $id);
        }

    }

    public function reply_button()
{
    $comment_id = $this->input->post("comment_id");

    // Set reply session data to TRUE
    $this->session->set_userdata("reply_comment_id", $comment_id);
    $this->session->set_userdata("reply", TRUE);
    
    redirect("products/show/" . $this->input->post("product_id"));
}


    public function submit_reply()
    {
        $back_btn = $this->input->post("back");
        $reply = $this->input->post("reply");

        if($back_btn) 
        {
            $this->session->set_userdata("reply", FALSE);

            redirect("products/show/" . $this->input->post("product_id"));
        }

        if($reply)
        {
            
            $this->load->model("review");

            $result = $this->review->validate_reply();

            if($result != null) 
            {
                var_dump($result);
            } else {
                
                $this->session->set_userdata("review_id", $this->input->post("comment_id"));
                $this->review->insert_reply($this->input->post());
                
                $this->session->set_userdata("reply", FALSE);
                redirect("products/show/" . $this->input->post("product_id"));
            }
            
            
            
        }

    }


}

?>