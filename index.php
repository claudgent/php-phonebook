<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>PHP Phonebook</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
    <?php
    // Connects to Database

    $link = mysqli_connect("localhost", "root", "idithop2", "phonebook");

    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $self = htmlspecialchars($_SERVER['PHP_SELF']);
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];
    $phone = $_GET['phone'];
    $email = $_GET['email'];
    $id = $_GET['id'];

    $first_name = test_input($first_name);
    $last_name = test_input($last_name);
    $phone = test_input($phone);
    $email =test_input($email);
    $id = test_input($id);

    if (isset($_GET["submit"])) {
        // process the form contents...
    }

    // Determines what action is being submitted

    $mode = $_GET["mode"];
    
    

    // Spawn form for adding contacts

    switch ($mode) {
        case 'add':
            # code...
            echo "
        <!-- Modal -->
            <form action={$self} method=GET class='form-group'>
                <div class='modal fade' id='exampleModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='exampleModalLabel'>Add Contact</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <table>
                                <tr><td></td><td><input type='text' name='first_name' class='form-control' placeholder='First Name'/></td></tr>
                                <tr><td></td><td><input type='text' name='last_name' class='form-control' placeholder='Last Name' /></td></tr>
                                <tr><td></td><td><input type='tel' name='phone' class='form-control' placeholder='Phone Number' pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}' /></td></tr>
                                <tr><td></td><td><input type='email' class='form-control' name='email' placeholder='Email Address'/></td></tr>
                                <input type=hidden name=mode value=added>
                            </table>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                <button type='submit' class='btn btn-primary'>Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>";
            break;

        case 'added':
            # code...
            mysqli_query ($link,"INSERT INTO address (first_name, last_name, phone, email) VALUES ('$first_name', '$last_name', '$phone', '$email')");
            break;

        case 'edit':
            # code...
            echo "
            <!-- Modal -->
            <form action={$self} method=GET class='form-group'>
                <div class='modal fade' id='exampleModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='exampleModalLabel'>Edit Contact</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <table>
                                    <tr><td align='right'></td><td><input type='text' value={$first_name} name='first_name' class='form-control' /></td></tr>
                                    <tr><td align='right'></td><td><input type='text' value={$last_name} name='last_name' class='form-control' /></td></tr>
                                    <tr><td align='right'></td><td><input type='tel' value={$phone} name='phone' class='form-control' /></td></tr> 
                                    <tr><td align='right'></td><td><input type='email' value={$email} name='email' class='form-control' /></td></tr>
                                    <input type=hidden name=mode value=edited>
                                    <input type=hidden name='id' value={$id}>
                                </table>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                <button type='submit' class='btn btn-primary'>Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        ";
            break;

        case 'edited':
            # code...
            mysqli_query ($link,"UPDATE address SET first_name = '$first_name', last_name = '$last_name', phone = '$phone', email = '$email' WHERE id = $id");
            echo "Data Updated!<p>";
            break;

        case 'remove':
            # code...
            mysqli_query ($link, "DELETE FROM address where id=$id");
            echo "Entry has been removed <p>";
            break;

        default:
            # code...
            break;
    }

    // Queries and displays contacts table

    $data = mysqli_query($link, "SELECT * FROM address ORDER BY first_name ASC") or die(mysqli_connect_error());

    echo "<div class='container '>
        <table class='table table-striped'>
            <tr>
                <th scope='col'>First Name</th>
                <th scope='col'>Last Name</th>
                <th scope='col'>Phone</th>
                <th scope='col'>Email</th>
                <th scope='col' colspan=2>Action</th>
            </tr>
            ";

    while($info = mysqli_fetch_array( $data ))
    {
        echo "
            <tr>
                <td> {$info['first_name']} </td>
                <td> {$info['last_name']} </td>
                <td> {$info['phone']} </td>
                <td> <a href=mailto: {$info['email']}> {$info['email']} </a></td>
                <td><a href={$self}?id={$info['id']}&first_name={$info['first_name']}&last_name={$info['last_name']}&phone={$info['phone']}&email={$info['email']}&mode=edit>Edit</a></td>
                <td><a href={$self}?id={$info['id']}&mode=remove>Remove</a></td>
            </tr>";
    }
    echo "<td colspan=6 align=right><a href=${self}?mode=add class='btn btn-primary'>Add Contact</a></td></table></div>";

    ?>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(window).on('load',function(){
            $('#exampleModal').modal('show');
        });
    </script>

</body>
</html>