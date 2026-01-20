<?php
class Post extends Model {
    public function getPosts($limit = 5, $page = 1, $onlyPublished = true) {
        $offset = ($page - 1) * $limit;
        
        $sql = 'SELECT *,
                        posts.id as postId,
                        users.id as userId,
                        posts.created_at as postCreated,
                        users.created_at as userCreated,
                        categories.name as categoryName
                        FROM posts
                        INNER JOIN users ON posts.author_id = users.id
                        LEFT JOIN post_categories ON posts.id = post_categories.post_id
                        LEFT JOIN categories ON post_categories.category_id = categories.id';
        
        if($onlyPublished){
            $sql .= ' WHERE posts.status = "published"';
        }

        $sql .= ' GROUP BY posts.id
                  ORDER BY posts.created_at DESC
                  LIMIT :limit OFFSET :offset';

        $this->db->query($sql);
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        $results = $this->db->resultSet();

        return $results;
    }

    public function getPostCount($onlyPublished = true) {
        if($onlyPublished){
             $this->db->query('SELECT id FROM posts WHERE status = "published"');
        } else {
             $this->db->query('SELECT id FROM posts');
        }
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getPostById($id) {
        $this->db->query('SELECT posts.*, post_categories.category_id as categoryId
                          FROM posts
                          LEFT JOIN post_categories ON posts.id = post_categories.post_id
                          WHERE posts.id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    public function getCategories() {
        $this->db->query('SELECT * FROM categories');
        $results = $this->db->resultSet();
        return $results;
    }

    // ... (searchPosts and getPostsByCategory skipped for brevity if not changing, but I must return contiguous block or just targeting specific functions)
    // Actually I should just target the specific functions since they are separated.
    // But replace_file_content requires a single contiguous block or I use MultiReplace.
    // I will use MultiReplace for this since the functions are far apart.
    // WAIT, I can just do two replace_file_content calls or one big one if I include the middle functions.
    // The middle functions are lines 54-99.
    // It is better to use `multi_replace_file_content`.
    

    public function searchPosts($term) {
        $term = "%$term%";
        $this->db->query('SELECT *,
                        posts.id as postId,
                        users.id as userId,
                        posts.created_at as postCreated,
                        users.created_at as userCreated
                        FROM posts
                        INNER JOIN users
                        ON posts.author_id = users.id
                        WHERE (posts.title LIKE :term OR posts.content LIKE :term)
                        AND posts.status = "published"
                        ORDER BY posts.created_at DESC
                        ');
        $this->db->bind(':term', $term);
        
        $results = $this->db->resultSet();
        return $results;
    }

    public function getPostsByCategory($slug) {
        $this->db->query('SELECT *,
                        posts.id as postId,
                        users.id as userId,
                        posts.created_at as postCreated,
                        users.created_at as userCreated,
                        categories.name as categoryName
                        FROM posts
                        INNER JOIN users ON posts.author_id = users.id
                        INNER JOIN post_categories ON posts.id = post_categories.post_id
                        INNER JOIN categories ON post_categories.category_id = categories.id
                        WHERE categories.slug = :slug
                        AND posts.status = "published"
                        ORDER BY posts.created_at DESC
                        ');
        $this->db->bind(':slug', $slug);
        
        $results = $this->db->resultSet();
        return $results;
    }

    public function addPost($data){
        $this->db->query('INSERT INTO posts (title, slug, content, thumbnail, status, author_id) VALUES(:title, :slug, :content, :thumbnail, :status, :author_id)');
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':thumbnail', $data['thumbnail']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':author_id', $data['user_id']);

        // Execute
        if($this->db->execute()){
            $postId = $this->db->lastInsertId();
             // Add Categories
             if(!empty($data['category_id'])){
                 $this->db->query('INSERT INTO post_categories (post_id, category_id) VALUES(:post_id, :category_id)');
                 $this->db->bind(':post_id', $postId);
                 $this->db->bind(':category_id', $data['category_id']);
                 $this->db->execute();
             }
            return true;
        } else {
            return false;
        }
    }

    public function updatePost($data){
        $this->db->query('UPDATE posts SET title = :title, slug = :slug, content = :content, thumbnail = :thumbnail, status = :status WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':thumbnail', $data['thumbnail']);
        $this->db->bind(':status', $data['status']);

        // Execute
        if($this->db->execute()){
             // Update Categories
             if(!empty($data['category_id'])){
                 // Remove existing category relation
                 $this->db->query('DELETE FROM post_categories WHERE post_id = :id');
                 $this->db->bind(':id', $data['id']);
                 $this->db->execute();

                 // Add new category relation
                 $this->db->query('INSERT INTO post_categories (post_id, category_id) VALUES(:post_id, :category_id)');
                 $this->db->bind(':post_id', $data['id']);
                 $this->db->bind(':category_id', $data['category_id']);
                 $this->db->execute();
             }
            return true;
        } else {
            return false;
        }
    }

    public function deletePost($id){
        $this->db->query('DELETE FROM posts WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}
