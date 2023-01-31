<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['password'])) {
   header('Location: login.php');
}

if ($_POST) {
   $file = '../images/' . ($_FILES['image']['name']);
   $imageType = pathinfo($file, PATHINFO_EXTENSION);
   // print_r($_FILES);
   // die();
   if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
      echo "<script>alert('Image must be png,jpg,jpeg');</script>";
   } else {
      $title = $_POST['title'];
      $content = $_POST['content'];
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $file);
      $stmt = $pdo->prepare("INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id)");
      $result = $stmt->execute([':title' => $title, ':content' => $content, ':image' => $image, ':author_id' => $_SESSION['user_id']]);

      if ($result) {
         echo "<script>alert('Successful');window.location.href='index.php';</script>";
      }
   }
}
?>

<?php include 'header.php' ?>

<!-- Main content -->
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <form action="create_blog.php" method="post" enctype="multipart/form-data">
                     <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title" value="" required>
                     </div>
                     <div class="form-group">
                        <label for="">Content</label>
                        <textarea class="form-control" name="content" value=""></textarea>
                     </div>
                     <div class="form-group">
                        <label for="">Image</label>
                        <input type="file" name="image" />
                     </div>
                     <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Create" />
                        <a href="index.php" class="btn btn-dark">Back</a>
                     </div>
                  </form>
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
         </div>
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include './footer.php' ?>