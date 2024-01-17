<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
</head>
<body>
    <center>
    <form action="http://localhost/WP2_Muhamad-Al-Fadhil-Satria/public/matakuliah/cetak" method="POST">
        <table>
            <tr>
                <th colspan="3">
                    Form Input Data Matakuliah
                </th>
            </tr>
            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>
            <tr>
                <th>Kode MTK</th>
                <th>:</th>
                <td>
                    <input type="text" name="kode" id="kode" >
                </td>
                <td><span>* <?= $validation->getError('kode');?></span></td>
            </tr>
            <tr>
                <th>Nama MTK</th>
                <th>:</th>
                <td>
                    <input type="text" name="nama" id="nama" >
                </td>
                <td><span>* <?= $validation->getError('nama');?></span></td>
            </tr>
            <tr>
                <th>SKS</th>
                <th>:</th>
                <td>
                    <select name="sks" id="sks" required>
                        <option value="">Pilih SKS</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="Submit">
                </td>
            </tr>
        </table>    
    
     
    </form>
    </center>
</body>
</html>