<?php
class Comment extends Model {
    public function getComments() {
        $this->db->query('SELECT *,
                        comments.id as commentId,
                        posts.title as postTitle,
                        comments.created_at as commentCreated,
                        comments.status as commentStatus
                        FROM comments
                        INNER JOIN posts
                        ON comments.post_id = posts.id
                        ORDER BY comments.created_at DESC
                        ');

        $results = $this->db->resultSet();

        return $results;
    }

    public function getCommentById($id) {
        $this->db->query('SELECT * FROM comments WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE comments SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteComment($id) {
        $this->db->query('DELETE FROM comments WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
