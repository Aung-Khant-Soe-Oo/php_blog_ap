<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['password'])) {
   header('Location: login.php');
}
if ($_POST) {
   $id = $_POST['id'];
   $title = $_POST['title'];
   $content = $_POST['content'];
   if ($_FILES['image']['name'] != null) {
      $file = '../images/' . ($_FILES['image']['name']);
      $imageType = pathinfo($file, PATHINFO_EXTENSION);
      // print_r($_FILES);
      // die();
      if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
         echo "<script>alert('Image must be png,jpg,jpeg');</script>";
      } else {

         $image = $_FILES['image']['name'];
         move_uploaded_file($_FILES['image']['tmp_name'], $file);
         $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
         $result = $stmt->execute();

         if ($result) {
            echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
         }
      }
   } else {
      $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
      $result = $stmt->execute();

      if ($result) {
         echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
      }
   }
}
$stmt = $pdo->prepare("SELECT * from posts WHERE id=" . $_GET['id']);
$stmt->execute();

$result = $stmt->fetch();
// print("<pre>");
// print_r($result);

?>

<?php include 'header.php' ?>

<!-- Main content -->
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <form action="" method="post" enctype="multipart/form-data">
                     <div class="form-group">
                        <input type="hidden" name="id" value="<?= $result['id'] ?>" />
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title" value="<?= $result['title'] ?>" required>
                     </div>
                     <div class="form-group">
                        <label for="">Content</label>
                        <textarea class="form-control" name="content"><?= $result['content'] ?></textarea>
                     </div>
                     <div class="form-group">
                        <label for="">Image</label><br />
                        <img src="../images/<?= $result['image'] ?>" width="150" height="150" alt="" />
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