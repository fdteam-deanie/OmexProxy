<?php

use Shuchkin\SimpleXLSX;
require_once('SimpleXLSX.php');

$hpi = false;
$hds = false;
$mvpi = true;

$sub = false;

$x = SimpleXLSX::parse('mvpi_fem.xlsx');
$rows = $x->rows(0);

$data = [

];

$code = '';

/* HPI */
if ($hpi) {
	
	$code = '$hpi_fem = ['.PHP_EOL;
	$code .= PHP_EOL;	
	
	if ($sub) {
		
		$this_scale = null;
		$new = false;
		
		for ($a = 1; $a < sizeof($rows); $a++) {
		
			$ts = trim($rows[$a][1]);
			$new = false;
			
			if (!empty($ts)) {
				
				$new = true;
				$this_scale = $ts;
				
			}
			
			$this_sub = trim($rows[$a][2]);
			$this_low = trim($rows[$a][3]);
			$this_high = trim($rows[$a][4]);
			
			if ($new) {
				
				$code .= PHP_EOL;
				$code .= "\t],".PHP_EOL;
				$code .= "\t'".$this_scale."' => [".PHP_EOL;
				$code .= PHP_EOL;
			
			}
			
			$code .= "\t\t'".$this_sub."' => [".PHP_EOL;
			$code .= PHP_EOL;
			
			$code .= "\t\t\t'what' => '',".PHP_EOL;
			$code .= "\t\t\t'low' => '".$this_low."',".PHP_EOL;
			$code .= "\t\t\t'high' => '".$this_high."',".PHP_EOL;
			
			$code .= PHP_EOL;
			$code .= "\t\t],";
			$code .= PHP_EOL;
			
		}
		
	}
	else {
		
		for ($a = 2; $a < sizeof($rows); $a++) {
		
			$scale = trim($rows[$a][1]);
			$scale_f = trim(mb_substr($scale, 0, 1));
			$scale_res = trim(mb_substr($scale, 1, mb_strlen($scale)));
			$scale_res = mb_strtolower($scale_res);
			$scale = trim($scale_f.$scale_res);
			
			$s015 = trim($rows[$a][3]);
			$s1635 = trim($rows[$a][4]);
			$s3650 = trim($rows[$a][5]);
			$s5164 = trim($rows[$a][6]);
			$s6585 = trim($rows[$a][7]);
			$s86100 = trim($rows[$a][8]);
			
			$code .= "\t'".$scale."' => [".PHP_EOL;
			$code .= PHP_EOL;
			
			$code .= "\t\t'0-15' => '".$s015."',".PHP_EOL;
			$code .= "\t\t'16-35' => '".$s1635."',".PHP_EOL;
			$code .= "\t\t'36-50' => '".$s3650."',".PHP_EOL;
			$code .= "\t\t'51-64' => '".$s5164."',".PHP_EOL;
			$code .= "\t\t'65-85' => '".$s6585."',".PHP_EOL;
			$code .= "\t\t'86-100' => '".$s86100."',".PHP_EOL;
			
			$code .= PHP_EOL;
			$code .= "\t],";
			$code .= PHP_EOL;
			
			$data['\''.$scale.'\''] = [

				'\'0-15\'' => $s015,
				'\'16-35\'' => $s1635,
				'\'36-50\'' => $s3650,
				'\'51-64\'' => $s5164,
				'\'65-85\'' => $s6585,
				'\'86-100\'' => $s86100,
			
			];
		
		}
	
	}
	
	$code .= PHP_EOL;
	$code .= "];";
	
}
/* HDS */
if ($hds) {
	
	//print_r($rows);
	//exit;
	
	$code = '$hds_fem = ['.PHP_EOL;
	$code .= PHP_EOL;	
	
	if ($sub) {
		
		$this_scale = null;
		$new = false;
		
		for ($a = 2; $a < sizeof($rows); $a++) {
		
			$ts = trim($rows[$a][3]);
			$new = false;
			
			if (!empty($ts)) {
				
				$new = true;
				$this_scale = $ts;
				
			}
			
			$this_sub = trim($rows[$a][4]);
			$this_low = trim($rows[$a][5]);
			$this_low = trim(str_replace(PHP_EOL, '. ', $this_low));
			$this_high = trim($rows[$a][6]);
			$this_high = trim(str_replace(PHP_EOL, '. ', $this_high));
			
			if ($new) {
				
				$code .= PHP_EOL;
				$code .= "\t],".PHP_EOL;
				$code .= "\t'".$this_scale."' => [".PHP_EOL;
				$code .= PHP_EOL;
			
			}
			
			$code .= "\t\t'".$this_sub."' => [".PHP_EOL;
			$code .= PHP_EOL;
			
			$code .= "\t\t\t'what' => '',".PHP_EOL;
			$code .= "\t\t\t'low' => '".$this_low."',".PHP_EOL;
			$code .= "\t\t\t'high' => '".$this_high."',".PHP_EOL;
			
			$code .= PHP_EOL;
			$code .= "\t\t],";
			$code .= PHP_EOL;
			
		}
		
	}
	else {
		
		for ($a = 2; $a < sizeof($rows); $a++) {
		
			$scale = trim($rows[$a][0]);
			$scale_f = trim(mb_substr($scale, 0, 1));
			$scale_res = trim(mb_substr($scale, 1, mb_strlen($scale)));
			$scale_res = mb_strtolower($scale_res);
			//$scale = trim($scale_f.$scale_res);
			
			$s015 = trim($rows[$a][3]);
			$s015 = trim(str_replace(PHP_EOL, '. ', $s015));
			$s1635 = trim($rows[$a][4]);
			$s1635 = trim(str_replace(PHP_EOL, '. ', $s1635));
			$s3650 = trim($rows[$a][5]);
			$s3650 = trim(str_replace(PHP_EOL, '. ', $s3650));
			$s5164 = trim($rows[$a][6]);
			$s5164 = trim(str_replace(PHP_EOL, '. ', $s5164));
			$s6585 = trim($rows[$a][7]);
			$s6585 = trim(str_replace(PHP_EOL, '. ', $s6585));
			$s86100 = trim($rows[$a][8]);
			
			$code .= "\t'".$scale."' => [".PHP_EOL;
			$code .= PHP_EOL;
			
			$code .= "\t\t'0-9' => '".$s015."',".PHP_EOL;
			$code .= "\t\t'10-39' => '".$s1635."',".PHP_EOL;
			$code .= "\t\t'40-69' => '".$s3650."',".PHP_EOL;
			$code .= "\t\t'70-89' => '".$s5164."',".PHP_EOL;
			$code .= "\t\t'90-100' => '".$s6585."',".PHP_EOL;

			$code .= PHP_EOL;
			$code .= "\t],";
			$code .= PHP_EOL;
			
			$data['\''.$scale.'\''] = [

				'\'0-15\'' => $s015,
				'\'16-35\'' => $s1635,
				'\'36-50\'' => $s3650,
				'\'51-64\'' => $s5164,
				'\'65-85\'' => $s6585,
				'\'86-100\'' => $s86100,
			
			];
		
		}
	
	}
	
	$code .= PHP_EOL;
	$code .= "];";
	
}
/* MVPI */
if ($mvpi) {
	
	$code = '$mvpi_fem = ['.PHP_EOL;
	$code .= PHP_EOL;	
	
	if ($sub) {
		
		$this_scale = null;
		$new = false;
		
		for ($a = 2; $a < sizeof($rows); $a++) {
		
			$ts = trim($rows[$a][3]);
			$new = false;
			
			if (!empty($ts)) {
				
				$new = true;
				$this_scale = $ts;
				
			}
			
			$this_sub = trim($rows[$a][4]);
			$this_low = trim($rows[$a][5]);
			$this_low = trim(str_replace(PHP_EOL, '. ', $this_low));
			$this_high = trim($rows[$a][6]);
			$this_high = trim(str_replace(PHP_EOL, '. ', $this_high));
			
			if ($new) {
				
				$code .= PHP_EOL;
				$code .= "\t],".PHP_EOL;
				$code .= "\t'".$this_scale."' => [".PHP_EOL;
				$code .= PHP_EOL;
			
			}
			
			$code .= "\t\t'".$this_sub."' => [".PHP_EOL;
			$code .= PHP_EOL;
			
			$code .= "\t\t\t'what' => '',".PHP_EOL;
			$code .= "\t\t\t'low' => '".$this_low."',".PHP_EOL;
			$code .= "\t\t\t'high' => '".$this_high."',".PHP_EOL;
			
			$code .= PHP_EOL;
			$code .= "\t\t],";
			$code .= PHP_EOL;
			
		}
		
	}
	else {
		
		for ($a = 3; $a < sizeof($rows); $a++) {
		
			$scale = trim($rows[$a][0]);
			$scale_f = trim(mb_substr($scale, 0, 1));
			$scale_res = trim(mb_substr($scale, 1, mb_strlen($scale)));
			$scale_res = mb_strtolower($scale_res);
			//$scale = trim($scale_f.$scale_res);
			
			$s015 = trim($rows[$a][2]);
			$s015 = trim(str_replace(PHP_EOL, '. ', $s015));
			$s1635 = trim($rows[$a][3]);
			$s1635 = trim(str_replace(PHP_EOL, '. ', $s1635));
			$s3650 = trim($rows[$a][4]);
			$s3650 = trim(str_replace(PHP_EOL, '. ', $s3650));
			
			$s015 = explode('.', $s015);
			$s1635 = explode('.', $s1635);
			$s3650 = explode('.', $s3650);
			
			$s015_list = '';
			$s1635_list = '';
			$s3650_list = '';

			foreach ($s015 as $s0) {
				
				$s0 = trim($s0);
				if (empty($s0)) {
					continue;
				}
				
				$s015_list .= '<li style="color: #5981af;">'.$s0.'</li>';
				
			}
			foreach ($s1635 as $s0) {
				
				$s0 = trim($s0);
				if (empty($s0)) {
					continue;
				}
				
				$s1635_list .= '<li style="color: #5981af;">'.$s0.'</li>';
				
			}
			foreach ($s3650 as $s0) {
				
				$s0 = trim($s0);
				if (empty($s0)) {
					continue;
				}
				
				$s3650_list .= '<li style="color: #5981af;">'.$s0.'</li>';
				
			}
			
			$code .= "\t'".$scale."' => [".PHP_EOL;
			$code .= PHP_EOL;
			
			$code .= "\t\t'0-35' => '<ul style=\"color: #5981af;\">".$s015_list."</ul>',".PHP_EOL;
			$code .= "\t\t'36-64' => '<ul style=\"color: #5981af;\">".$s1635_list."</ul>',".PHP_EOL;
			$code .= "\t\t'65-100' => '<ul style=\"color: #5981af;\">".$s3650_list."</ul>',".PHP_EOL;

			$code .= PHP_EOL;
			$code .= "\t],";
			$code .= PHP_EOL;
			
			$data['\''.$scale.'\''] = [

				'\'0-15\'' => $s015,
				'\'16-35\'' => $s1635,
				'\'36-50\'' => $s3650,
				'\'51-64\'' => $s5164,
				'\'65-85\'' => $s6585,
				'\'86-100\'' => $s86100,
			
			];
		
		}
	
	}
	
	$code .= PHP_EOL;
	$code .= "];";
	
}

echo $code;
exit;
print_r($data);
exit;
$a = var_export($data, true);
echo $a;