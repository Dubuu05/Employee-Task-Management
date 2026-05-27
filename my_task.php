<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);
?>

<!DOCTYPE html>
<html>

<head>

    <title>My Tasks</title>

    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet"
    href="css/style.css">

</head>

<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php"; ?>

<div class="body">

<?php include "inc/nav.php"; ?>

<section class="section-1">


    <h2 class="title-2">
        My Tasks
    </h2>


    <?php if (isset($_GET['success'])) { ?>

    <div class="success">

        <?= stripslashes($_GET['success']); ?>

    </div>

    <?php } ?>

   

    <?php if ($tasks != 0) { ?>

    <div class="table-container">

        <table class="main-table">

            <tr>

                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Action</th>

            </tr>

            <?php $i = 0; ?>

            <?php foreach ($tasks as $task) { ?>

            <tr>

            

                <td>

                    <?= ++$i ?>

                </td>

          

                <td>

                    <?= htmlspecialchars($task['title']) ?>

                </td>

       

                <td>

                    <?= htmlspecialchars($task['description']) ?>

                </td>

     

                <td>

                <?php

                if ($task['priority'] == 'High') {

                    echo "<span class='priority-high'>High</span>";

                }
                elseif ($task['priority'] == 'Medium') {

                    echo "<span class='priority-medium'>Medium</span>";

                }
                else {

                    echo "<span class='priority-low'>Low</span>";
                }

                ?>

                </td>

          

                <td>

                <?php

                if (strtolower($task['status']) == 'completed') {

                    echo "<span class='status-completed'>Completed</span>";

                }
                elseif (strtolower($task['status']) == 'pending') {

                    echo "<span class='status-pending'>Pending</span>";

                }
                else {

                    echo "<span class='status-progress'>In Progress</span>";
                }

                ?>

                </td>

      

                <td>

                    <?= htmlspecialchars($task['due_date']) ?>

                </td>

                <!-- ACTION -->

                <td>

                    <a href="edit-task-employee.php?id=<?= $task['id'] ?>"
                    class="edit-btn">

                        <i class="fa fa-pencil"></i>
                        Edit

                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

    <?php } else { ?>

   

    <div class="empty-state">

        <i class="fa fa-tasks"></i>

        <h3>No Tasks Found</h3>

        <p>
            You currently have no assigned tasks.
        </p>

    </div>

    <?php } ?>

</section>

</div>

<script type="text/javascript">

var active = document.querySelector("#navlist li:nth-child(2)");

if(active){
    active.classList.add("active");
}

</script>

</body>
</html>

<?php

} else {

    $em = "First login";

    header("Location: login.php?error=$em");

    exit();
}
?>