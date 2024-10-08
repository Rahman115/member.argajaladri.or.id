<?php

include 'config.php';
include 'database.php';
include 'menus.php';
session_start();
if (!isset($_SESSION['username_member'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM anggota WHERE no_induk='{$_SESSION['username_member']}'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($result);
$nama = $row['nama'];
$write = $row['no_induk'];

// $menu = new menus();



$ns = new menus();
$table = new resultset('generation');
$artikel = new resultset('artikel');
$tb_angkatan = $table->toArray();
$tb_artikel = $artikel->toArray();

$art = array();
$read = false;
$edit = false;
$add = false;


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // this switch statamen of GET
    $follow = $_GET['follow'];
    switch ($_GET['action']) {

        case 'add':
            if ($follow == 'new-artikel') {
                $add = true;
            }
            break;

    }
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'read') {
            $follow = $_GET['follow'];
            $read = true;
            $dt = $artikel->toWhere(array('link' => $follow));
            if ($dt) {
                $art = $dt[0];
            }
        }
        if ($_GET['action'] == 'edit') {
            $follow = $_GET['follow'];
            $edit = true;
            $dt = $artikel->toWhere(array('link' => $follow));
            if ($dt) {
                $art = $dt[0];
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // var_dump($_POST['submit']);
    if (isset($_POST['submit']) && !empty($_POST['submit'])) {
        switch ($_POST['submit']) {
            case 'Simpan':
                $data = array();
                $data['id_artikel'] = null;
                $data['id_kategori'] = '2';


                if (isset($_POST['i_judul_artikel']) && !empty($_POST['i_judul_artikel'])) {
                    $rm_tags = strip_tags($_POST['i_judul_artikel']);
                    $data['judul'] = preg_replace('/[^\p{L}\p{N}\s]/u', '', $rm_tags);
                }

                $str = strtolower($data['judul']);
                $option = trim($str);
                $link = str_replace(' ', '_', $option);
                // $ID = null;
                $data['link'] = $link;
                $data['penulis'] = $_POST['i_edit'];
                $data['isi'] = $_POST['i_deskripsi'];
                $data['tanggal'] = $_POST['i_tanggal'];
                $data['waktu'] = $_POST['i_waktu'];

                $kd_key = $_POST['kode_keys'];
                $data['images'] = $kd_key . ";" . $_POST['i_keys'];
                $msg = $artikel->toInsert($data);
                // var_dump($msg);
                if ($msg == "success") {
                    $message = "Data Anda Berhasil Disimpan";
                    header("Refresh: 5");
                    header("Location: index.php");
                }

                break;
            case 'Edit':
                $str = strtolower($_POST['i_judul_artikel']);
                $option = trim($str);
                $link = str_replace(' ', '_', $option);
                $ID = $_POST['i_id'];

                $data = array();
                $data['judul'] = $_POST['i_judul_artikel'];
                $data['link'] = $link;
                $data['penulis'] = $_POST['i_edit'];
                $data['isi'] = $_POST['i_deskripsi'];
                $data['tanggal'] = $_POST['i_tanggal'];
                $data['waktu'] = $_POST['i_waktu'];
                $kd_key = $_POST['kode_keys'];
                $data['images'] = $kd_key . ";" . $_POST['i_keys'];

                $msg = $artikel->toUpdate($data, array('id_artikel' => $ID));

                if ($msg == "success") {
                    $message = "Data Anda Berhasil Disimpan";
                    header("Refresh: 5");
                    header("Location: index.php");
                }
                break;
        }
    }


}

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Angkatan Argajaladri</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <script type="text/javascript" src="https://rahman115.github.io/editor/ckeditor.js"></script>
    <!-- <script type="text/javascript" src="https://cdn.ckbox.io/CKBox/2.0.0/ckbox.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
</head>

<body>
    <?php
    echo $ns->nav($nama, 'dashboard');
    echo $ns->start_container('dashboard');
    ?>
    <!-- FORM ADD ARTIKEL -->
    <?php if ($add == true) { ?>
        <!-- Content -->
        <div id="content">
            <!-- Box -->
            <div class="box">
                <!-- Box Head -->
                <div class="box-head">
                    <h2 class="left">
                        <?php echo $art['judul']; ?>
                    </h2>
                </div>
                <form action="artikel.php" method="post">
                    <input type="hidden" name="i_edit" value="<?php echo $write; ?>" />
                    <div class="form">
                        <p> <span class="req">max 100 symbols</span>
                            <label>Article Title <span>(Required Field)</span></label>
                            <input type="text" class="field size1" name="i_judul_artikel" placeholder="Artcle Title"
                                autofocus />
                        </p>
                        <p> <span class="req"></span>
                            <label>Content <span>(Required Field)</span></label>
                        <div id="editor">
                            <textarea class='ckeditor' id="ckeditor" rows="40" cols="30"
                                name="i_deskripsi"><?php echo $art['isi']; ?></textarea>
                        </div>
                        </p>
                        <p> <span class="req">Date</span>
                            <label>Tanggal <span>(Required Field)</span></label>
                            <input type="date" class="field size1" name="i_tanggal" />
                        </p>
                        <p> <span class="req">dd:mm:dd</span>
                            <label>Tanggal <span>(Required Field)</span></label>
                            <input type="text" class="field size1" name="i_waktu"
                                placeholder="input time (hour:minute:second)" />
                        </p>
                        <p> <span class="req"><a
                                    href="https://github.com/Rahman115/member.argajaladri.or.id?tab=readme-ov-file#cara-memasukkan-link-google-drive-ke-form-artikel"
                                    target="_blank">Key google drive</a> | <a href="#">Key Youtube</a> </span>


                            <label><input type="radio" name="kode_keys" value="Images"> Drive Image OR <input type="radio"
                                    name="kode_keys" value="Video"> Youtube<span>(Required Field)</span></label>
                            <input type="link" class="field size1" name="i_keys" placeholder="Insert Kode Keys" />
                        </p>
                    </div>
                    <!-- End Form -->
                    <!-- Form Buttons -->
                    <div class="buttons">
                        <input type="submit" class="button" name="submit" value="Simpan" />
                    </div>
                    <!-- End Form Buttons -->
                </form>

            </div>
        </div>
    <?php } ?>
    <!-- END OF FORM ADD ARTIKEL -->
    <?php
    if ($edit == true) {
        ?>
        <!-- Content -->
        <div id="content">
            <!-- Box -->
            <div class="box">
                <!-- Box Head -->
                <div class="box-head">
                    <h2 class="left">
                        <?php echo $art['judul']; ?>
                    </h2>
                </div>
                <form action="artikel.php" method="post">
                    <div class="form">
                        <p> <span class="req">max 100 symbols</span>
                            <label>Article Title <span>(Required Field)</span></label>
                            <input type="text" class="field size1" name="i_judul_artikel"
                                value="<?php echo $art['judul']; ?>" />
                            <input type="hidden" name="i_edit" value="<?php echo $write; ?>" />
                            <input type="hidden" name="i_id" value="<?php echo $art['id_artikel']; ?>" />
                        </p>
                        <p> <span class="req"></span>
                            <label>Penulis <span>(Don't Edit)</span></label>
                            <input type="text" class="field size1" value="<?php echo $nama; ?>" />
                        </p>
                        <p> <span class="req">max 100 symbols</span>
                            <label>Content <span>(Required Field)</span></label>
                        <div id="editor">
                            <textarea class='ckeditor' id="ckeditor" rows="40" cols="30"
                                name="i_deskripsi"><?php echo $art['isi']; ?></textarea>
                        </div>
                        </p>
                        <p> <span class="req">Date</span>
                            <label>Tanggal <span>(Required Field)</span></label>
                            <input type="date" class="field size1" name="i_tanggal"
                                value="<?php echo $art['tanggal']; ?>" />
                        </p>
                        <p> <span class="req">dd:mm:dd</span>
                            <label>Tanggal <span>(Required Field)</span></label>
                            <input type="text" class="field size1" name="i_waktu" value="<?php echo $art['waktu']; ?>" />
                        </p>
                        <p> <span class="req"><a
                                    href="https://github.com/Rahman115/member.argajaladri.or.id?tab=readme-ov-file#cara-memasukkan-link-google-drive-ke-form-artikel"
                                    target="_blank">Key google drive</a> | <a href="#">Key Youtube</a> </span>
                            <?php

                            $ex = $art['images'];
                            $exp = explode(';', $ex);
                            $radio = $exp[0];
                            $imgs = $exp[1];
                            ?>

                            <label>
                                <input type="radio" name="kode_keys" value="Images" <?php if ($radio == "Images")
                                    echo "checked"; ?> /> Drive Image OR
                                <input type="radio" name="kode_keys" value="Video" <?php if ($radio == "Video")
                                    echo "checked"; ?> /> Youtube
                                <span>(Required Field)</span>
                            </label>
                            <input type="text" class="field size1" name="i_keys" value="<?php echo $imgs; ?>" />
                        </p>
                    </div>
                    <!-- End Form -->
                    <!-- Form Buttons -->
                    <div class="buttons">
                        <input type="submit" class="button" name="submit" value="Edit" />
                    </div>
                    <!-- End Form Buttons -->
                </form>

            </div>
        </div>
    <?php } ?>
    <?php
    if ($read == true) {
        ?>
        <!-- Content -->
        <div id="content">
            <!-- Box -->
            <div class="box">
                <!-- Box Head -->
                <div class="box-head">
                    <h2 class="left">
                        <?php echo $art['judul']; ?>
                    </h2>
                    <div class="right"><a href="https://member.argajaladri.or.id/artikel.php" class="button">Kembali</a>
                    </div>
                </div>
                <p>
                    <a href="/artikel.php?action=edit&follow=<?php echo $art['link']; ?>" class="ico edit">
                        <?php echo $art['judul']; ?>
                    </a>
                </p>
                <p>
                    <?php echo $art['penulis']; ?>
                </p>
                <p>
                    <?php echo $art['isi']; ?>
                </p>
                <p>
                    <?php echo $art['tanggal']; ?>
                </p>
                <p>
                    <?php echo $art['waktu']; ?>
                </p>
                <p>
                    <?php echo $art['images']; ?>
                </p>
            </div>
        </div>
    <?php } ?>
    <?php
    echo $ns->end_container();
    echo $ns->footers();
    ?>
    <script>
        $(document).ready(function () {
            $('#tabel-data').DataTable();
        });
        CKEDITOR.replace('editor1');
        // CKEDITOR.editorConfig = function (config) {
        //     config.language = 'es';
        //     config.uiColor = '#F7B42C';
        //     config.height = 300;
        //     config.toolbarCanCollapse = true;

        //     config.toolbarGroups = [
        //         { name: 'document', groups: ['mode', 'document', 'doctools'] },
        //         { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
        //         { name: 'forms', groups: ['forms'] },
        //         { name: 'clipboard', groups: ['clipboard', 'undo'] },
        //         { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
        //         { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'] },
        //         { name: 'links', groups: ['links'] },
        //         { name: 'insert', groups: ['insert'] },
        //         { name: 'styles', groups: ['styles'] },
        //         { name: 'colors', groups: ['colors'] },
        //         { name: 'tools', groups: ['tools'] },
        //         { name: 'others', groups: ['others'] },
        //         { name: 'about', groups: ['about'] }
        //     ];

        //     config.removeButtons = 'Copy,Paste,Anchor,Strike,Subscript,Superscript,Cut';
        // };
    </script>
</body>

</html>