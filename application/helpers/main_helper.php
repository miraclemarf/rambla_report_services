<?php
defined('BASEPATH') or die('Access Denied');

function setUppercase($var)
{
	$data_upper = strtoupper($var);

	return $data_upper;
}

function currency($amount)
{
	//$mount = number_format($amount);
	if ($amount < 0) {
		$mount = 0;
	} else {
		$mount = number_format($amount);
	}
	return $mount;
}

function formatRupiah($amount)
{
	return number_format($amount, 0, ',', '.');
}

function PopulateForm()
{
	$CI = &get_instance();
	$post = array();
	foreach (array_keys($_POST) as $key) {
		$post[$key] = $CI->input->post($key);
	}
	return $post;
}

function now($isFull = false)
{
	return date($isFull ? "Y-m-d H:i:s" : "Y-m-d");
}

function sql_datetime($date)
{ // yyyy-mm-dd
	$exp = date('Y-m-d H:i:s', strtotime($date));
	return $exp;
}

function indo_date($date)
{ // yyyy-mm-dd
	$exp = date('d-m-Y', strtotime($date));
	return $exp;
}

function indo_date2($date)
{ // yyyy-mm-dd
	$exp = date('d-M-y', strtotime($date));
	return $exp;
}

function indo_date3($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('d F Y', strtotime($date));
	return $exp;
}

function indo_date5($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('l,d F Y', strtotime($date));
	return $exp;
}

function indo_date6($date)
{ // yyyy-mm-dd
	$exp = date('m/d/Y - m/d/Y', strtotime($date));
	return $exp;
}

function jam($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('H:i', strtotime($date));
	return $exp;
}

function jam2($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('h:i A', strtotime($date));
	return $exp;
}

function jam3($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('H:i:s', strtotime($date));
	return $exp;
}


function jam4($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('Y-m-d H:i', strtotime($date));
	return $exp;
}

function menit($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('i', strtotime($date));
	return $exp;
}

function indo_date4($date)
{ // yyyy-mm-dd 2015-10-27 00:00:00.000
	$date_now = date('d F Y');
	$year_month_now = date('F Y');
	$days_now = date('d');

	$exp = date('d F Y', strtotime($date));
	$year_month = date('F Y', strtotime($date));
	$days = date('d', strtotime($date));

	$akum_days = $days - $days_now;

	if ($exp == $date_now) {
		$tampil_date = 'Today';
	} elseif ($year_month == $year_month_now) {
		if ($akum_days == 1) {
			$tampil_date = 'Tommorow';
		} elseif ($akum_days == -1) {
			$tampil_date = 'Yesterday';
		}
	} else {
		$tampil_date = $exp;
	}

	return $tampil_date;
}

function birthday($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('d F', strtotime($date));
	return $exp;
}

function date_bootstrap($date)
{ // yyyy-mm-dd
	$exp = date('d/m/Y', strtotime($date));
	return $exp;
}

function date_bootstrap2($date)
{ // yyyy-mm-dd
	$exp = date('m/d/Y', strtotime($date));
	return $exp;
}

function aktivasi($date)
{ // yyyy-mm-dd
	$exp = date('Ymd', strtotime($date));
	return $exp;
}

function ymdhis($date)
{ // yyyy-mm-dd
	$exp = date('YmdHis', strtotime($date));
	return $exp;
}

function hari($date)
{ // yyyy-mm-dd
	//$exp = date('d F Y , H:i:s',strtotime($date));
	$exp = date('l', strtotime($date));
	return $exp;
}

function tanggal($date)
{ // yyyy-mm-dd
	$exp = date('j', strtotime($date));
	return $exp;
}

function bulan($date)
{ // yyyy-mm-dd
	$exp = date('m', strtotime($date));
	return $exp;
}

function bln($date)
{ // yyyy-mm-dd
	$exp = date('F', strtotime($date));
	return $exp;
}

function tahun($date)
{ // yyyy-mm-dd
	$exp = date('Y', strtotime($date));
	return $exp;
}

function showmonth($monthNum)
{
	$monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
	return $monthName;
}

function kunci($var)
{ // yyyy-mm-dd
	$key = md5($var);
	return $key;
}

function password($var)
{ // yyyy-mm-dd
	$key = md5(md5(md5($var)));
	return $key;
}

function inggris_date($date)
{ // yyyy-mm-dd
	$exp = date('Y-m-d', strtotime($date));
	return $exp;
}

function inggris_date2($date)
{ // yyyy-mm-dd
	$exp = date('Y-m-d H:i:s', strtotime($date));
	return $exp;
}

function custom_date($date)
{
	$date = str_replace('/', '-', $date);
	$exp =  date('Y-m-d', strtotime($date));
	return $exp;
}

function custom_date_henry($date)
{
	$date = str_replace('/', '-', $date);
	$exp =  date('Y-m-d', strtotime($date . ' -1 days'));
	return $exp;
}

function force_download2($filename = '', $data = '')
{
	if (FALSE === strpos($filename, '.')) {
		$filename .= '.noextension';
	}
	force_download($filename, $data);
}


function ubahFormatTanggal($tanggal_awal_akhir)
{
	// Pisahkan tanggal awal dan akhir
	$pecah = explode('-', $tanggal_awal_akhir);
	$tanggal_awal = $pecah[0];
	$tanggal_akhir = $pecah[1];

	// Ubah format masing-masing tanggal
	$tanggal_awal = date("Y-m-d", strtotime($tanggal_awal));
	$tanggal_akhir = date("Y-m-d", strtotime($tanggal_akhir));

	// Gabungkan kembali dengan pemisah ~
	$tanggal_format_baru = $tanggal_awal . '~' . $tanggal_akhir;
	return $tanggal_format_baru;
}
