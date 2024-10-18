<?php

class Products
{
    public $database;
    private $resultsPerPage = 50;
    private $numberOfResults;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getProductDetails($productId)
    {
        $sql = 'SELECT * FROM products WHERE id =?';
        $stmt = $this->database->getDb()->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        return $stmt->get_result();
    }

    private function getNumberOfPages()
    {
        $sql = "SELECT * FROM products";
        $result = $this->database->getDb()->query($sql);
        $numberOfResults = $result->num_rows;
        $this->numberOfResults = $numberOfResults;
        return ceil($numberOfResults / $this->resultsPerPage);
    }

    private function getPageNumber()
    {
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        return $page;
    }

    private function getFirstProduct()
    {
        $page = $this->getPageNumber();
        return ($page - 1) * $this->resultsPerPage;
    }

    public function displayPageNavigation()
    {
        $page = $this->getPageNumber();
        $numberOfPages = $this->getNumberOfPages();
        $firstProduct = $this->getFirstProduct();

        echo '<div class="container-fluid page-navigator center">';

        if ($page >= 2) {
            echo '<a class="btn btn-info rounded-0 nav-btn" href="index.php?page= ' . ($page - 1) . ' " role="button">Previous</a>';
        } else {
            echo '<a class="btn btn-info disabled rounded-0 nav-btn" href="index.php?page= ' . ($page - 1) . ' " role="button">Previous</a>';
        }

        $prev = false;

        for ($i = $page; $i <= $page + 2; ++$i) {
            if ($page >= 3 && $prev === false) {
                echo '<a class="btn btn-light rounded-0 custom" href="index.php?page= ' . ($page - 2) . ' " role="button">' . ($page - 2) . '</a>';
                echo '<a class="btn btn-light rounded-0 custom" href="index.php?page= ' . ($page - 1) . ' " role="button">' . ($page - 1) . '</a>';
                $prev = true;
            }
            if ($page == 2 && $prev === false) {
                echo '<a class="btn btn-light rounded-0 custom" href="index.php?page= ' . ($page - 1) . ' " role="button">' . ($page - 1) . '</a>';
                $prev = true;
            }
            if ($i == $page) {
                echo '<a class="btn btn-danger rounded-0 custom"  role="button">' . $i . '</a>';
            } else {
                echo '<a class="btn btn-light rounded-0 custom" href="index.php?page= ' . ($i) . ' " role="button">' . $i . '</a>';
            }
            if ($page == $numberOfPages - 2) {
                echo '<a class="btn btn-light rounded-0 custom" href="index.php?page= ' . ($i + 1) . ' " role="button">' . ($i + 1) . '</a>';
                echo '<a class="btn btn-light rounded-0 custom" href="index.php?page= ' . ($i + 2) . ' " role="button">' . ($i + 2) . '</a>';
                break;
            }
            if ($page == $numberOfPages - 1) {
                echo '<a class="btn btn-light rounded-0 custom" href="index.php?page= ' . ($i + 1) . ' " role="button">' . ($i + 1) . '</a>';
                break;
            }
            if ($page == $numberOfPages) {
                break;
            }
        }

        if ($page < $numberOfPages) {
            echo '<a class="btn btn-info rounded-0 nav-btn" href="index.php?page= ' . ($page + 1) . ' " role="button">Next</a>';
        } else {
            echo '<a class="btn btn-info disabled rounded-0 nav-btn" href="index.php?page= ' . ($page + 1) . ' " role="button">Next</a>';
        }

        echo '<input id="page" type="number" min="1" max="' . $numberOfPages .
            '"placeholder="' . $page . "/" . $numberOfPages . '" value="" required> ';

        echo '<button onClick="navigate(' . $numberOfPages . ');"> Go </button> ';
        echo ($firstProduct + 1) . ' - ' . ($firstProduct + 50) . ' / ' . $this->numberOfResults;

        echo '</div>';
    }

    public function getProductsOnPage()
    {
        $firstProduct = $this->getFirstProduct();
        $query = "SELECT id, label FROM products LIMIT " . $this->resultsPerPage . " OFFSET " . $firstProduct;
        $result = $this->database->getDb()->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function saveFavoriteProduct($productDetails)
    {
        $sql = "INSERT INTO `favorites` (id, user_id, product_id) VALUES (?, ?, ?)";
        $stmt = $this->database->getDb()->prepare($sql);
        $id = null;
        $stmt->bind_param('iii', $id, $productDetails['userId'], $productDetails['id']);
        $stmt->execute();
    }

    public function deleteFavoriteProduct($productDetails)
    {
        $sql = "DELETE FROM `favorites` WHERE user_id = ? AND product_id = ?";
        $stmt = $this->database->getDb()->prepare($sql);
        $stmt->bind_param('ii', $productDetails['userId'], $productDetails['id']);
        $stmt->execute();
    }

    public function isFavorite($userId, $productId): int
    {
        $sql = "SELECT * FROM `favorites` WHERE user_id = ? AND product_id = ?";
        $stmt = $this->database->getDb()->prepare($sql);
        $stmt->bind_param('ii', $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
    }
}