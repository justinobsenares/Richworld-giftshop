<?php
session_start();
$userid = $_SESSION["userId"];
include "dbc.inc.php";

if(isset($_POST["finishSubmit"])){

    if(isset($_POST["qnumber"])){
        $qnumber = $_POST["qnumber"];
        if(isset($_POST["qanswer"])){
            $qanswer = $_POST["qanswer"];
                $sql5 ="SELECT * from account where acc_id = '$userid'";
                $result = mysqli_query($conn,$sql5);
                $verify=0;
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    $verify = $row["verify"];
                }
                if($verify == 1){
    
                        $file = $_FILES["profileImg"];
                        
                        $filename = $_FILES["profileImg"]["name"];
                        $fileTmpname = $_FILES["profileImg"]["tmp_name"];
                        $fileSize = $_FILES["profileImg"]["size"];
                        $fileError = $_FILES["profileImg"]["error"];
                        $fileType = $_FILES["profileImg"]["type"];

                        $fileExt = explode('.',$filename);
                        $fileActExt = strtolower(end($fileExt));
                        $allowed = array('jpg','jpeg','png',"svg");

                        if(in_array($fileActExt,$allowed)){
                            if($fileError === 0){
                                if($fileSize < 10000000){
                                    //$newname = uniqid('',true).".".$fileActExt;
                                    //$fileDestination = '../img/account-profile/'.$newname;
                                    $userId = $_SESSION["userId"];
                                    $newname = "user".$userId.".".$fileActExt;
                                    $fileDestination = '../img/account-profile/'.$newname;
                                    move_uploaded_file($fileTmpname,$fileDestination);
                                    // echo $qnumber;
                                    // echo $qanswer;
                                    // echo $tanswer;
                                    $sql = "UPDATE account SET user_status ='1',prof_path ='$newname',q_number='$qnumber',q_answer='$qanswer' where acc_id ='$userId'";
                                    mysqli_query($conn,$sql);
                                    $sql3 = "INSERT INTO account_complete (acc_id,age,birthday,civilstatus,caddress,gender) values ('$userId','none','none','none','none','none')";
                                    mysqli_query($conn,$sql3);   
                                    $tanswer = $_SESSION["acc_type"];
                                    if($tanswer == "user"){
                                       
                                        echo '<script>alert("Successfully Finish Account")</script>';
                                        header("location:../index.php");
                                        exit();
                                    }
                                    else if($tanswer == "admin" || $tanswer == "hrmanager" || $tanswer == "department" || $tanswer == "hrrecruiter"){
                                    
                                        echo '<script>alert("Successfully Finish Account")</script>';
                                        header("location:../dashboard.php");
                                        exit();
                                    }
                                    //echo 'upload success';
                                    
                                }else{
                                    echo '<script>alert("file too big")</script>';
                                    header("location:../compProf.inc.php?error");
                            exit();
                                }
                            }else{
                                echo '<script>alert("there was an error")</script>';
                            header("location:../compProf.inc.php?error");
                            exit();
                            }
                        }else{
                            echo '<script>alert("file not allowed")</script>';
                            header("location:../compProf.inc.php?error");
                            exit();
                            exit();
                        }
                }else{
                    header("location:../compProf.inc.php?error=emailnotverified");
                    exit();
                }
            }else{
                header("location:../compProf.inc.php?error=input-answer");
                exit();
            }
    }else{
        header("location:../compProf.inc.php?error=select-question");
        exit();
    }
    
}