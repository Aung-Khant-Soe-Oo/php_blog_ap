<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['password'])) {
   header('Location: login.php');
}
?>

<?php include 'header.php' ?>

<!-- Main content -->
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Blog List</h3>
               </div>

               <?php
               if ($_GET['pageno']) {
                  $pageno = $_GET['pageno'];
               } else {
                  $pageno = 1;
               }
               $numOfrecs = 1;
               $offset = ($pageno - 1) * $numOfrecs;
               if ($_POST['search']) {
                  $search_key = $_POST['search'];
                  $stmt = $pdo->prepare("SELECT * from posts WHERE title LIKE '%$search_key%' ORDER BY id DESC");
                  $stmt->execute();
                  $raw_result = $stmt->fetchAll();
                  $total_pages = ceil(count($raw_result) / $numOfrecs);
                  // echo $total_pages;
                  // die();
                  $stmt = $pdo->prepare("SELECT * from posts WHERE title LIKE '%$search_key%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
               } else {
                  $stmt = $pdo->prepare("SELECT * from posts ORDER BY id DESC");
                  $stmt->execute();
                  $raw_result = $stmt->fetchAll();
                  $total_pages = ceil(count($raw_result) / $numOfrecs);
                  // echo $total_pages;
                  // die();
                  $stmt = $pdo->prepare("SELECT * from posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
               }
               ?>
               <!-- /.card-header -->
               <div class="card-body">
                  <div>
                     <a href="create_blog.php" type="button" class="btn btn-success">New Blog Post</a>
                  </div>
                  <br />
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th style="width: 10px">#</th>
                           <th>Title</th>
                           <th>Content</th>
                           <th style="width: 40px">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        if ($result) {
                           $i = 1;
                           foreach ($result as $res) { ?>
                              <tr>
                                 <td><?= $i ?></td>
                                 <td><?= $res['title'] ?></td>
                                 <td><?= substr($res['content'], 0, 50) . '...' ?></td>
                                 <td>
                                    <div class="btn-group">
                                       <a href="edit.php?id=<?= $res['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                                       <a href="delete.php?id=<?= $res['id'] ?>" onclick="return confirm('Are your sure?')" type="button" class="btn btn-danger">Delete</a>
                                    </div>
                                 </td>
                              </tr>
                        <?php
                              $i++;
                           }
                        }
                        ?>
                     </tbody>
                  </table>
                  <br />

                  <!-- pagination -->

                  <nav aria-label="Page navigation example" style="float:right">
                     <ul class="pagination">
                        <li class="page-item <?= $pageno <= 1 ? 'disabled' : '' ?>"> <a href="?pageno=1" class="page-link">First</a> </li>
                        <li class="page-item <?= $pageno <= 1 ? 'disabled' : '' ?>"> <a href="<?= $pageno > 1 ? '?pageno=' . ($pageno - 1) : '#' ?>" class="page-link">Previous</a> </li>
                        <li class="page-item"> <a href="?pageno=<?= $pageno ?>" class="page-link">1</a> </li>
                        <li class="page-item <?= $pageno >= $total_pages ? 'disabled' : '' ?>"> <a href="<?= $pageno < $total_pages ? '?pageno=' . ($pageno + 1) : '#' ?>" class="page-link">Next </a> </li>
                        <li class="page-item <?= $pageno == $total_pages ? 'disabled' : '' ?>"> <a href="?pageno=<?= $total_pages ?>" class="page-link">Last</a> </li>
                     </ul>

                     <!-- pagination -->
                  </nav>
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