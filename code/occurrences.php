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
	
	'associatedSequences',
	
	'typeStatus'
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
			
			
			
				//  basis of record
				
/*
1087425	
1	! Complete spm as sample
8	"Museum voucher, E-voucher"
130	"Museum voucher, leg"
1	"Museum voucher, Leg. P. Stoev"
3	"Museum voucher, Leg. P. Stoev, 
1	"Museum voucher, Leg. S. Lazarov
1	"Museum voucher, Leg.Simaiakis"
623	"Museum voucher, whole specimen 
508	"Museum voucher, Whole specimen"
157	"Museum voucher, Whole speciment
589	"Museum voucher,"
251	A: pinned
39	A: pinned, reared
1	A: unpinned
1	abdoman tissue
27	Adult
9	B
1	BC ZSM HYM 07721
1	BC ZSM HYM 07732
1	BC ZSM HYM 07735
1	BC ZSM HYM 07736
1	BC ZSM HYM 07778
1	BC ZSM HYM 09501
1	BC ZSM HYM 09519
1	BC ZSM HYM 09546
1	BC ZSM HYM 09587
1	DNA Voucher
460	DNA/tissue voucher only
34756	DNA/Tissue Vouchered Only
2	DNAtissue vouchered only
701	dried whole insect
562	Dry mounting specimen
155	e-voucher
80	e-voucher only
290	e-vouchered
33	E-vouchered only
118	E-vouchered only (dna/tissue+pho
67	E-Vouchered(DNA/Tissue+Photo)
5337	E-Vouchered:DNA/Tissue+Photo
51	e-vouchers
3	East Arm
4	ethanol
11	GP Gillette Museum of Arthropod 
86	in alcohol (ethanol, 96%)
1	insect leg in 95% ETOH
8	JBS Stonefly Collection
79	L
1	L: fragment
1	L: head capsule only
2	L: part
8	L: remnant
801	Leg
35	MC collection
140	Morphology
99	museum vocher
44506	museum voucher
1982	Museum voucher,
66	Museum voucher, Dried Insect
133	Museum voucher, dry
266	Museum voucher, E-voucher
65	Museum voucher, E-vouchered only
104	Museum voucher, E-vouchered with
464	Museum voucher, leg
1	Museum voucher, Leg.Simaiakis
506	Museum voucher, museum voucher: 
25	Museum voucher, Pinned specimen
5795	Museum voucher, Specimens in eth
18	Museum voucher, whole dried inse
50	Museum voucher, Whole insect bod
2434	Museum voucher, Whole specimen
3914	Museum voucher, whole specimen i
320	Museum voucher, Whole speciment
123	Museum voucher, wholw specimen
19	Museum voucher: type series
845	Museum voucher: whole specimen i
2126	Museum Vouchered
123	Museum Vouchered (type series)
99	Museum Vouchered (type)
77	Museum Vouchered:Type
618	Museum Vouchered:Type Series
281	No Specimen
1	Only the head left
2	Only the tail left
6	P
2	Parasites
3	paratype
22	Photos
13	Pinned Specimen
3	posterior tissue
197	private collection
189	RBCM
9	Soldier
430	Specimen in 95% Ethanol
50	Specimen in ethanol
20	Tissue
16	To be vouchered (holdup/private)
3523	To be Vouchered:Holdup/Private
12	Topotype
1	Type:Allotype
4	Type:Holotype
2	Type:Syntype
4	TypeSeries:Paratype
20	TypeSeries:unknown
1271	unvouchered
110	Voucher type : tissue
5	Voucher type:  morphological
661	Voucher type: morphological
107	voucher: registered collection
129	Vouchered
795	Vouchered (registered collection
39	vouchered/registered collection
15	vouchered:  registered collectio
275	Vouchered: Registered Collection
1579860	Vouchered:Registered Collection
1	whole body
86	Whole organism
2841	whole specimen
2	Whole specimen in 95% ethanol
333	whole specimen in ethanol
1	whole specimen in ETOH
210	Whole Voucher
*/				
				
				if (isset($obj->basisOfRecord))
				{
				
					//echo $obj->basisOfRecord . "\n";
				
					// types
					if (preg_match('/Type:Holotype/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'holotype';
					}
					if (preg_match('/Type:Allotype/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'allotype';
					}
					if (preg_match('/Type:Syntype/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'syntype';
					}
					if (preg_match('/Topotype/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'topotype';
					}
					if (preg_match('/paratype/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'paratype';
					}
					if (preg_match('/type series/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'type';
					}
					if (preg_match('/Vouchered:Type/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'type';
					}
					if (preg_match('/\(type\)/i', $obj->basisOfRecord))
					{
						$obj->typeStatus = 'type';
					}
					
				
					// convert basis of record to standard term
					$basisOfRecord = '';
					
					$matched = false;
					
					// Museum voucher
					if (!$matched)
					{
						if (preg_match('/museum vo[u]?cher/i', $obj->basisOfRecord))
						{
							$basisOfRecord = 'PreservedSpecimen';
							$matched = true;
						}
					}
					
					// Registered
					if (!$matched)
					{
						if (preg_match('/registered/i', $obj->basisOfRecord))
						{
							$basisOfRecord = 'PreservedSpecimen';
							$matched = true;
						}
					}

					// E-voucher (what is that?)
					if (!$matched)
					{
						if (preg_match('/e-voucher/i', $obj->basisOfRecord))
						{
							$basisOfRecord = 'MaterialSample';
							$matched = true;
						}
					}
					// DNA/tissue
					if (!$matched)
					{
						if (preg_match('/DNA[\s|\/]?tissue/i', $obj->basisOfRecord))
						{
							$basisOfRecord = 'MaterialSample';
							$matched = true;
						}
					}
					
					if ($basisOfRecord != '')
					{
						$obj->basisOfRecord = $basisOfRecord;
					}
					
				
				}
				
				
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
				
				/*
					if (isset($obj->typeStatus))
					{
						echo join("\t", $row_to_export) . "\n";
						exit();
					}
				*/
				
			
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
