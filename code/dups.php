<?php

// Dump ids

ini_set("auto_detect_line_endings", true); // vital because some files have Windows ending

$bold_to_dc = array(

	'processid' => 'occurrenceID',
	'sampleid' => 'otherCatalogNumbers',
	'museumid' => 'catalogNumber',
	'fieldid' => 'recordNumber',
	'bin_guid' => 'taxonID',
	'vouchertype' => 'basisOfRecord',
	'inst_reg' => 'institutionCode',
	
	
	'phylum_reg'=>'phylum',
	'class_reg'=>'class',
	'order_reg'=>'order',
	'family_reg'=>'family',
	'genus_reg'=>'genus',
	'species_reg'=>'scientificName',
	'taxonomist_reg'=>'identifiedBy',

	'collectors'=>'recordedBy',
	'collectiondate'=>'eventDate',
	'lifestage'=>'lifestage',
	'lat'=>'decimalLatitude',
	'lon'=>'decimalLongitude',
	
	'site'=>'locality',
	'province_reg'=>'stateProvince',
	'country_reg'=>'country',
	
	'accession' => 'associatedSequences'
);

$keys_to_export = array(
	'occurrenceID',
	'otherCatalogNumbers',
	'catalogNumber',
	'recordNumber',

	'basisOfRecord',
	'institutionCode',
	
	'taxonID',	

	'phylum',
	'class',
	'order',
	'family',
	'genus',
	'scientificName',
	'identifiedBy',

	'recordedBy',
	'eventDate',
	'lifestage',
	'decimalLatitude',
	'decimalLongitude',

	'locality',
	'stateProvince',
	'country',
	
	'associatedSequences',
	
	'typeStatus'
);



$data_dir = dirname(dirname(__FILE__)) . '/data'; 

// process all files
$filenames = array();
$list = scandir($data_dir);
foreach ($list as $filename)
{
	if (preg_match('/\.tsv$/', $filename))
	{
		$filenames[] = $filename;
	}
}

// process one file
//$filenames=array('iBOL_phase_0.50_COI.tsv');


foreach ($filenames as $filename)
{
	$keys = array();
	
	$row_count = 0;
	
	$filename = $data_dir . '/' . $filename;
	
	$file = @fopen($filename, "r") or die("couldn't open $filename");
	
	$file_handle = fopen($filename, "r");
	while (!feof($file_handle)) 
	{
		$line = trim(fgets($file_handle));
		
		$row = explode("\t", $line);
		
		if ($row_count == 0)
		{
			$keys = $row;
		}
		else
		{
			//print_r($row);
			
			$obj = new stdclass;
			
			$n = count($row);
			for ($i = 0; $i < $n; $i++)
			{
				if ($row[$i] != '')
				{
					if (isset($bold_to_dc[$keys[$i]]))
					{
						$obj->{$bold_to_dc[$keys[$i]]} = $row[$i];
					}
				}
			}
			
			if ($n > 1)
			{
			
				// clean
				$obj->occurrenceID = str_replace('.COI-5P', '', $obj->occurrenceID);
				
				echo $obj->occurrenceID . "\n";
			

			
			}			

			
			
		}
		
		$row_count++;
		
		/*
		if ($row_count > 10) 
		{
			break;
		}
		*/
	
	}
	
	
}

?>
