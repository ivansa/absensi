<?php
ini_set('display_errors',FALSE);
include("../conn/conn.php");
$id_kls=$_POST['kelas'];
$nis=$_POST['nis'];
$bulan=$_POST['bulan'];
$nama_kelas=$_POST['nama_kelas'];
$tanggal_bulan=$_POST['tanggal_bulan'];

$select = "SELECT siswa.nis, siswa.absen, siswa.Nama_siswa, kelas.Nama_Kelas, absensi_siswa.tanggal FROM absensi_siswa, siswa, kelas where siswa.nis=absensi_siswa.no_siswa and kelas.id=absensi_siswa.kd_kelas and absensi_siswa.bulan='$tanggal_bulan' and absensi_siswa.in_out='out_auto' order by absensi_siswa.tanggal";

$export = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );

$fields = mysql_num_fields ( $export );

//for ( $i = 0; $i < $fields; $i++ )
//{
    $header .= "NIS" . "\t";
    $header .= "Absen" . "\t";
    $header .= "Nama" . "\t";
    $header .= "Kelas". "\t";
    $header .= "Tanggal". "\t";

//}

while( $row = mysql_fetch_row( $export ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
		else if ($value == "h"){
			$value = "Hadir";
		}
		else if ($value == "i"){
			$value = "Izin";
		}
		else if ($value == "a"){
			$value = "Alpa";
		}
		else if ($value == "s"){
			$value = "Sakit";
			}
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $data .= trim( $line ) . "\n";
}
$data = str_replace( "\r" , "" , $data );

if ( $data == "" ){
    $data = "\nTidak Ada Data (0) !\n";                        
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=OUT-Kelas(Perbulan)_".$tanggal_bulan.".xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
?>