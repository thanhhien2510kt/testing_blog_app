<?php
class Category extends Model {
    public function getCategories() {
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getCategoriesWithCount() {
        $this->db->query('SELECT c.*, COUNT(pc.post_id) as post_count 
                          FROM categories c 
                          LEFT JOIN post_categories pc ON c.id = pc.category_id 
                          GROUP BY c.id 
                          ORDER BY c.name ASC');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    public function addCategory($data) {
        $this->db->query('INSERT INTO categories (name, slug, parent_id) VALUES(:name, :slug, :parent_id)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':parent_id', $data['parent_id']);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateCategory($data) {
        $this->db->query('UPDATE categories SET name = :name, slug = :slug, parent_id = :parent_id WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':parent_id', $data['parent_id']);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCategory($id) {
        $this->db->query('DELETE FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
