<?php

// Parse public API dump files and export as simplified Darwin Core 

ini_set("auto_detect_line_endings", true); // vital because some files have Windows ending

$filenames=array(
'iBOL_phase_0.50_COI.tsv'
);

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
	
	'associatedSequences'
);


if (0)
{
	// generate meta
	
	$n = count($keys_to_export);
	for ($i = 0; $i < $n; $i++)
	{
		echo '<field index="' . $i . '" term="http://rs.tdwg.org/dwc/terms/' . $keys_to_export[$i] . '"/>' . "\n";
	}



	exit();
}



$data_dir = dirname(dirname(__FILE__)) . '/data'; 

// header row
echo join("\t", $keys_to_export) . "\n";

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
			
				// make URL
				$obj->occurrenceID = 'http://bins.boldsystems.org/index.php/Public_RecordView?processid=' . $obj->occurrenceID;
			
				//print_r($obj);
			
				$row_to_export = array();
			
				foreach ($keys_to_export as $k)
				{
					if (isset($obj->{$k}))
					{
						$row_to_export[] = $obj->{$k};
					}
					else
					{
						$row_to_export[] = '';
					}
				}
			
				echo join("\t", $row_to_export) . "\n";
			
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
