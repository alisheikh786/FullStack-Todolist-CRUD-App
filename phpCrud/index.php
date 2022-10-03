<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<body>
    <?php 
// connecting the data base
$server = "localhost";
$username = "root";
$password = "";
$dbname = "notedata";
$conn = mysqli_connect($server, $username, $password, $dbname);

//dellete an item from database
if(isset($_GET["delete"])){
    $sno = $_GET["delete"];
    $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
    mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD']=='POST') {
if (!$conn) {
    die("Connect was not established".mysqli_connect_error());
}
if(isset($_POST["hidden"])){
//entering edited data in database
$sno = $_POST["hidden"];
$title = $_POST["editTitle"];
$description = $_POST["editDescription"];

$sql2 = "UPDATE `notes` SET `title` = '$title' , `note` = '$description' WHERE `sno` = $sno;";

mysqli_query($conn, $sql2); 
}
else {
    $title = $_POST["title"];
$description = $_POST["description"];
$sql = "INSERT INTO `notes` (`title`, `note`, `time`) VALUES ('$title', '$description', current_timestamp())";
mysqli_query($conn, $sql);    
}
}
?>
    <div class="container">
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Please Enter Your Note :)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/phpCrud/index.php" method="post">
                            <div class="mb-3 ">
                                <input type="hidden" id="hidden" name="hidden">
                                <label for="editTitle" class="form-label fs-2">Title</label>
                                <input type="text" class="form-control" id="editTitle" name="editTitle"
                                    aria-describedby="titleHelp">
                                <div id="titleHelp" class="form-text">Your Key To Successful Move, Lets Get Listed.
                                </div>
                            </div>
                            <div class=" my-4">
                                <label for="editDescription" class="fs-2">Note</label>
                                <textarea class="form-control" placeholder="Leave a comment here" style="height: 100px"
                                    id="editDescription" name="editDescription"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">ADD Note</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <h1 class="text-center my-4">Note App</h1>
        <form action="/phpCrud/index.php" method="post">
            <div class="mb-3 ">
                <label for="title" class="form-label fs-2">Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="titleHelp">
                <div id="titleHelp" class="form-text">Your Key To Successful Move, Lets Get Listed.</div>
            </div>
            <div class=" my-4">
                <label for="description" class="fs-2">Note</label>
                <textarea class="form-control" placeholder="Leave a comment here" style="height: 100px" id="description"
                    name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">UPDATE NOTE</button>
        </form>
        <div class="container my-5">
            <table class="table table-striped" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">S.no</th>
                        <th scope="col">Title</th>
                        <th scope="col">Note</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT * FROM `notes`";
                    $result = mysqli_query($conn, $sql);
                    $numRow = mysqli_num_rows($result);
                    if($numRow>0){
                        $number = 1;
                        while ($row=mysqli_fetch_assoc($result)) {
                            echo "  <tr>
                            <th scope='row'>".$number."</th>
                            <td data-sno='".$row["sno"]."'>".$row["title"]."</td>
                            <td>".$row["note"]."</td>
                            <td>                <!-- Button trigger modal -->
                                <button type='button' class='btn btn-primary btn-sm btnEdit' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>
                                    Edit
                                  </button> <button class='btn btn-sm btn-primary btnDell' >Delete</button> </td>
                            </tr>";
                            $number += 1;
                            
                        }
                    }else {
                        echo ' <div class="alert alert-danger d-flex align-items-center justify-content-center py-4 my-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-diamond-fill" viewBox="0 0 16 16">
                            <path d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098L9.05.435zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                          </svg>
                        <div>
                        No Note is Found! 
                        </div>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });

        const edits = document.getElementsByClassName("btnEdit");
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                let title = e.target.parentNode.parentNode.getElementsByTagName("td")[0].innerText;
                let description = e.target.parentNode.parentNode.getElementsByTagName("td")[1].innerText;
                let sno = e.target.parentNode.parentNode.getElementsByTagName("td")[0].getAttribute("data-sno");
                console.log(sno)
                document.getElementById("editTitle").value = title;
                document.getElementById("editDescription").value = description;
                document.getElementById("hidden").value = sno;

            })


        })
        const delletes = document.getElementsByClassName("btnDell");
        Array.from(delletes).forEach((e) => {
            e.addEventListener("click", (element) => {
                let sno = element.target.parentNode.parentNode.getElementsByTagName("td")[0].getAttribute("data-sno");
                if (confirm("Are You Really Want To Delete This Note?")) {
                    window.location = `/phpCrud/index.php?delete=${sno}`;
                }
            })
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
        </script>
</body>

</html>