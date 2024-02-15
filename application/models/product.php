<?php 

class Product extends CI_Model 
{

    public function show_all_product()
    {
        $query = "Select * from products";

        return $this->db->query($query)->result_array();
    }


    public function get_product_by_id($id)
    {
        return $this->db->query("Select * FROM products WHERE id = ?;", array($id))->row_array();
    }


    public function create_product($product)
    {
        $query = "Insert INTO products (name, price, inventory_count, created_at, updated_at, description)
                VALUES(?, ?, ?,NOW(), NOW(), ?)";
        $values = array(
            $product["product_name"],
            $product["price"],
            $product["quantity"],
            $product["description"]
        );

        return $this->db->query($query, $values);
    }

    public function update_product($product)
    {   
        // TODO: add update_at
        $query = "UPDATE products SET name = ?, description = ?, price = ?, inventory_count = ?, updated_at = NOW() WHERE id = ?";
        $values = array($product["product_name"], $product["description"], $product["price"], $product["quantity"], $product["product_id"]);
        $this->db->query($query, $values);    
    }

    public function delete_product($id)
    {
        return $this->db->query("DELETE FROM products WHERE id = ?", array($id));
    }

}

?>