<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        form{
            width: 300px;
        }
        form input{
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<?php

    function loadRegistrations($filename){
        $jsondata = file_get_contents($filename);
        $arr_data = json_decode($jsondata, true);
        return $arr_data;
    }
    function severDataJSON($filename, $name, $email, $phone){
        {
            try{
                $contect = array(
                        'name'=> $name,
                        'email'=> $email,
                        'phone' => $phone
                );
                $arr_data =loadRegistrations($filename);
                arr_push($arr_data, $contect);
                $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);
                file_put_contents($filename, $jsondata);
                echo "Du lieu thanh cong!";
            }catch (Exception $e){
                echo 'Loi: ' . $e->getMessage() . "\n";
            }
        }
    }
    $nameErr = null;
    $emailErr = null;
    $phoneErr = null;
    $name = null;
    $email = null;
    $phone = null;

    if($_SERVER["REQUEST_METHOD"]=='POST'){
        $name =$_POST["name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        if(empty($name)){
            $nameErr = "khong duoc de trong";
            $has_error = true;
        }
        if(empty($email)){
            $emailErr = "khong duoc de trong";
            $has_error = true;
        }else{
            if(!filter_var($email,FILTER_VALIDATE_EMAI)){
                $emailErr = "Dinh dang email sai (xxx@xxx.xxx.xxx)!";
            }
        }
        if(empty($phone)){
            $phoneErr = "so dien thoai khong duoc de trong";
            $has_error = true;
        }
        if($has_error == false){
            saveDataJSON("data.json", $name, $email, $phone);
            $name =null;
            $email = null;
            $phone = null;
        }
    }

?>
<h2>Registration Form</h2>
<form method="post">
    <fieldset>
        <legend>Details</legend>
        Name: <input type="text" name="name" value="<?php echo $name; ?>">
        <span class="error">* <?php echo $nameErr; ?></span>
        <br><br>
        E-mail: <input type="text" name="email" value="<?php echo $email; ?>">
        <span class="error">* <?php echo $emailErr; ?></span>
        <br><br>
        Phone: <input type="text" name="phone" value="<?php echo $phone; ?>">
        <span class="error">*<?php echo $phoneErr; ?></span>
        <br><br>
        <input type="submit" name="submit" value="Register">
        <p><span class="error">* required field.</span></p>
    </fieldset>
</form>

<?php
$registrations = loadRegistrations('data.json');
?>
<h2>Registration list</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
    </tr>
    <?php foreach ($registrations as $registration): ?>
        <tr>
            <td><?= $registration['name']; ?></td>
            <td><?= $registration['email']; ?></td>
            <td><?= $registration['phone']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>