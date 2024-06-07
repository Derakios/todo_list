<?php
include 'config/database.php';
?>

<?php
session_start();
$sql = "SELECT * FROM task";
$result = mysqli_query($mysqli, $sql);
$allTask = mysqli_fetch_all($result, MYSQLI_ASSOC);
$count = mysqli_num_rows($result);

/*$countTable = "SELECT COUNT(*) FROM task";
$resultCount = mysqli_query($mysqli, $countTable);
$count = $resultCount->num_rows;*/

$task = '';
$taskErr = '';


if (isset($_POST['submit'])) {
    if (empty($_POST['task'])) {
        echo $taskErr = 'Wrong task';
    } else {

        $task = filter_var($_POST['task'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    //add to database
    if (empty($taskErr)) {
        $sql = "INSERT INTO task (content) VALUES ('$task');";
        if (mysqli_query($mysqli, $sql)) {
            header('Location: index.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
        }
    }
}

if (!empty($_GET['id']) && $_GET['act'] == 'delete') {
    $deleteQuery = "DELETE FROM task WHERE task_id = " . $_GET['id'];
    print_r($_GET['id']);
    if (mysqli_query($mysqli, $deleteQuery)) {
        header('Location: index.php');
    } else {
        echo "Error: " . $deleteQuery . "<br>" . mysqli_error($mysqli);
    }
} else if (!empty($_GET['id']) && $_GET['act'] == 'update') {
    $updateQuery = "UPDATE task SET status = 1 WHERE task_id = " . $_GET['id'];
    if (mysqli_query($mysqli, $updateQuery)) {
        header('Location: index.php');
    } else {
        echo "Error: " . $updateQuery . "<br>" . mysqli_error($mysqli);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Todo-list</title>
</head>

<body>
    <main>
        <section class="vh-100" style="background-color: #eee;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-lg-9 col-xl-7">
                        <div class="card rounded-3">
                            <div class="card-body p-4">

                                <h1 class="text-center my-3 pb-3">To Do List</h1>
                                <h2>Task left : <?php echo $count ?> </h2>
                                <br>
                                <form class="formAdd" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" style="width: 50%; text-align: center;">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" id="task" name="task" class="form-control <?php echo !$taskErr ?: 'is-invalid'; ?>" value="<?php echo $task ?>" />
                                        <label class="form-label" for="task">Enter a task here</label>
                                        <div class="invalid-feedback">
                                            <?php echo $taskErr ?> Wrong task
                                        </div>
                                    </div>
                                    <input type="submit" name="submit" value="Add" class="btn btn-primary">
                                </form>

                                <table class="table mb-4">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">Task</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Start of task</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allTask as $item) : ?>
                                            <tr>
                                                <th scope="row"><?php echo $item['task_id']; ?></th>
                                                <td><?php echo $item['content']; ?></td>
                                                <td><?php if ($item['status'] == 0) {
                                                        echo 'In progress';
                                                    } else {
                                                        echo 'Done';
                                                    } ?></td>
                                                <td><?php echo $item['creation']; ?></td>
                                                <td>
                                                    <form style="width: 100%;" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                                        <a href="index.php?id=<?php echo $item['task_id'] ?>&act=delete" name="delete" id="delete" class="btn btn-danger">Delete</a>
                                                        <a href="index.php?id=<?php echo $item['task_id'] ?>&act=update" name="update" id="update" class="btn btn-success ms-1">Finished</a>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <p style="text-align: center;">
                                    <?php
                                    echo "Actual date & hour : " . date("d/m/Y H:i:s");
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; Killian Jousseaume - <?php echo date("Y"); ?></p>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>