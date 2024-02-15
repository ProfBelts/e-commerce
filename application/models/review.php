<?php 

class Review extends CI_Model
{
    public function insert_review($post)
    {   
        $query = "INSERT INTO reviews (content, users_id, product_id, created_at, updated_at)
                  VALUES (?, ?, ?, NOW(), NOW())";
        $values = array(
            $post["user_review"],
            $post["users_id"],
            $post["product_id"]
        );
    
        return $this->db->query($query, $values);
    }
    
    public function insert_reply($post)
    {
        $user = $this->session->userdata("user");

        $query = "INSERT INTO replies (content, review_id, created_at, updated_at, user_id)
                    VALUES (?, ?, NOW(), NOW(), ?)";

        
        $values = array(
            $post["reply_content"],
            $post["comment_id"],
            $user["user_id"]
        );
    
        // Insert the reply into the replies table
        $this->db->query($query, $values);
    
        // Get the ID of the newly inserted reply
        $reply_id = $this->db->insert_id();
    
        // Update the corresponding review in the reviews table with the reply_id
        $this->db->set('reply_id', $reply_id);
        $this->db->where('id', $post['comment_id']);
        $this->db->update('reviews');
    
        return $reply_id;
    }
    
    public function show_reviews($product_id)
    {
        $query = "SELECT reviews.id as review_id, 
        reviews.content as content, 
        CONCAT(users.first_name, ' ', users.last_name) as name, 
        reviews.created_at as comment_time
        FROM reviews 
        INNER JOIN users ON reviews.users_id = users.id
        WHERE product_id = ?";
        
        $reviews = $this->db->query($query, array($product_id))->result_array();
    
        return $reviews;
    }
    
    

    public function show_replies($review_id)
    {
        $query = "SELECT replies.id as reply_id, 
                  replies.review_id as review_id, 
                  CONCAT(users.first_name, ' ', users.last_name) as name, 
                  replies.content as content, 
                  replies.created_at as date
                  FROM replies 
                  INNER JOIN reviews ON replies.review_id = reviews.id
                  INNER JOIN users ON replies.user_id = users.id
                  WHERE reviews.id = ?";
                  
        return $this->db->query($query, array($review_id))->result_array();
    }
    



    public function validate_review()
    {
    $this->load->library("form_validation");

    $config = array(
        array(
            "field" => "user_review",
            "label" => "User Review",
            "rules" => "required|trim|max_length[250]",
            "errors" => array(
                "required" => "Review cannot be blank.",
                "trim" => "Review cannot be blank.",
                "max_length" => "Review cannot exceed 500 characters."
            )
        )
    );

    $this->form_validation->set_rules($config);

    if ($this->form_validation->run() == FALSE) {
        return array(validation_errors());
    } 


    }

    public function validate_reply()
    {
        $this->load->library("form_validation");

    $config = array(
        array(
            "field" => "reply_content",
            "label" => "Reply Content",
            "rules" => "required|trim|max_length[250]",
            "errors" => array(
                "required" => "Reply cannot be blank.",
                "trim" => "Reply cannot be blank.",
                "max_length" => "Reply cannot exceed 500 characters."
            )
        )
    );

    $this->form_validation->set_rules($config);

    if ($this->form_validation->run() == FALSE) {
        return array(validation_errors());
    }
    }




}

?>