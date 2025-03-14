
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Pagination Example</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $host = 'localhost';
                $db   = 'ca-institute';
                $user = 'root';
                $pass = '';
                $charset = 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                try {
                    $pdo = new PDO($dsn, $user, $pass, $options);
                } catch (\PDOException $e) {
                    throw new \PDOException($e->getMessage(), (int)$e->getCode());
                }

                // Pagination logic
                $limit = 4; // Number of records per page
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $start = ($page > 1) ? ($page * $limit) - $limit : 0;

                // Fetch total number of records
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM posts");
                $total = $stmt->fetch()['total'];
                $pages = ceil($total / $limit);

                // Fetch records for the current page
                $stmt = $pdo->prepare("SELECT * FROM posts LIMIT :start, :limit");
                $stmt->bindValue(':start', $start, PDO::PARAM_INT);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll();

                foreach ($results as $row) {               
                ?>
                <tr>
                    <td><?php echo $row['post_id']; ?></td>
                    <td><?php echo $row['post_title'] ?></td>
                    <td><?php echo $row['post_content'] ?></td>
                    </tr>
                <?php }  ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination">
            <?php  

                if ($pages > 1) {
                    echo "<li class='page-item'><a class='page-link' href='?page=1'>First</a></li>";
                    for ($i = 1; $i <= $pages; $i++) {
                        $active = ($page == $i) ? 'active' : '';
                        echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
                    }
                    echo "<li class='page-item'><a class='page-link' href='?page=$pages'>Last</a></li>";
                }
            ?>
            </ul>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>