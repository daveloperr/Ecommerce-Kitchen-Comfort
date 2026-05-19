<?php
if(isset($_POST['addbtn']))
{
    require 'dbhandler.inc.php';

    $prodname = $_POST['prodname'];
    $categoryName = strtolower($_POST['category']); // Convert category name to lowercase
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $desc = $_POST['desc'];

    // Mapping of lowercase category names to IDs
    $categoryMap = array(
        'appliances' => 1,
        'bakeware' => 2,
        'cookware' => 3,
        'tableware' => 4,
        'utensils' => 5
    );

    // Check if the lowercase category name exists in the mapping
    if(!isset($categoryMap[$categoryName])) {
        header("location: ../admin/admin_products.php?error=invalidcategory");
        exit();
    }

    $categoryId = $categoryMap[$categoryName]; // Get the category ID from the mapping

    if(empty($prodname) || empty($price) || empty($qty) || empty($desc))
    {
        header("location: ../admin/admin_products.php?error=required");
        exit();
    }

    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileError = $_FILES['image']['error'];
        $fileType = $_FILES['image']['type'];

        // Perform file upload logic
        if ($fileSize === 0 || $fileError !== 0) {
            header("location: ../admin/admin_products.php?error=uploadfileerror");
            exit();
        }

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            header('location: ../admin/admin_products.php?error=filetypenotaccepted');
            exit();
        }

        $newFileName = uniqid('', true) . '.' . $extension;
        $fileDirectory = "../images/" . $newFileName;

        if (move_uploaded_file($fileTmpName, $fileDirectory)) {

            $sql = "INSERT INTO products1 (product_name, category_id, category, price, quantity, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql))
            {
                header("location: ../admin/admin_products.php?error=sqlerror");
                exit();
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "sdsdiss", $prodname, $categoryId, $categoryName, $price, $qty, $desc, $newFileName);
                mysqli_stmt_execute($stmt);
                header("location: ../admin/admin_products.php?addproduct=success");
                exit();
            }
        } else {
            header('location: ../admin/admin_products.php?error=uploaderror');
            exit();
        }
    } else {
        header("location: ../admin/admin_products.php?error=noimageuploaded");
        exit();
    }
}
?>
