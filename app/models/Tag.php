<?php
class Tag extends Model {
    public function getTags() {
        $this->db->query('SELECT * FROM tags ORDER BY name ASC');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getTagsWithCount() {
        $this->db->query('SELECT t.*, COUNT(pt.post_id) as post_count 
                          FROM tags t 
                          LEFT JOIN post_tags pt ON t.id = pt.tag_id 
                          GROUP BY t.id 
                          ORDER BY t.name ASC');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getTagById($id) {
        $this->db->query('SELECT * FROM tags WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    public function addTag($data) {
        $this->db->query('INSERT INTO tags (name, slug) VALUES(:name, :slug)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateTag($data) {
        $this->db->query('UPDATE tags SET name = :name, slug = :slug WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteTag($id) {
        $this->db->query('DELETE FROM tags WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
