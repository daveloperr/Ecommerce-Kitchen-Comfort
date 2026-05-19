<?php
if(isset($_POST['addbtn']))
{
	$prodname = $_POST['prodname'];
	$category = $_POST['category'];
	$price = $_POST['price'];
	$qty = $_POST['qty'];
	$desc = $_POST['desc'];
	$image = $_POST['image'];

	require 'dbhandler.inc.php';

	if(empty($prodname) || empty($category) || empty($price) || empty($qty) || empty($desc) || empty($image))
	{
		header("location: ../products.php?error=required");
	}
	else
	{
		$sql = "INSERT INTO products1 (product_name,category,price,quantity,description,image) VALUES (?,?,?,?,?,?)";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$sql))
		{
			header("location: ../products.php?error=sqlerror");
		}
		else
		{
			mysqli_stmt_bind_param($stmt,"ssdiss",$prodname,$category,$price,$qty,$desc,$image);
			mysqli_stmt_execute($stmt);
			header("location: ../products.php?addproduct=success");
		}

	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}
?>