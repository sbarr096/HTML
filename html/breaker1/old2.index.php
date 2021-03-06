<?php
//including FusionCharts PHP Wrapper
include("fusioncharts/fusioncharts.php"); 
$hostdb   = "localhost"; // MySQl host
$userdb   = "root"; // MySQL username
$passdb   = "root"; // MySQL password
$namedb   = "breaker1"; // MySQL database name

//Establish connection with the database
$dbhandle = new mysqli($hostdb, $userdb, $passdb, $namedb);

//Validating DB Connection
if ($dbhandle->connect_error) {
    exit("There was an error with your connection: " . $dbhandle->connect_error);
}

?>

<html>
   <head>
      <title>Power Consumption Charts</title>
      <!-- FusionCharts Core Package File -->
      <script src="fusioncharts/js/fusioncharts.js"></script> 
      <script type="text/javascript" src="fusioncharts/js/elegant.js"></script>
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
      
  </head>
  
<?php

//SQL Query for the Parent chart.
$strQuery = "SELECT Year, Watts FROM yearly_kWh";

//Execute the query, or else return the error message.
$result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

//If the query returns a valid response, preparing the JSON array.
if ($result) {
    //`$arrData` holds the Chart Options and Data.
    $arrData = array(
        "chart" => array(
            "caption" => "Breaker1 Year Consumption",
            "xAxisName"=> "Year",
            "yAxisName"=> "KiLo Watt Hours",
            "paletteColors"=> "#008ee4",
            "yAxisMaxValue"=> "2000",
            "baseFont"=> "Open Sans",
            "theme" => "elegant"


            
        )
    );
    
    //Create an array for Parent Chart.
    $arrData["data"] = array();
    
    // Push data in array.
    while ($row = mysqli_fetch_array($result)) {
        array_push($arrData["data"], array(
            "label" => $row["Year"],
            "value" => $row["Watts"],
			//Create link for yearly drilldown as "2011"
            "link" => "newchart-json-" . $row["Year"]
        ));
        
    }
    



    //Data for Linked Chart will start from here, SQL query from quarterly table 
    $year = 2011;
    $strQuarterly = "SELECT  Quarter, Watts, Year FROM quarterly_kWh WHERE 1";
    $resultQuarterly = $dbhandle->query($strQuarterly) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
    
    //If the query returns a valid response, preparing the JSON array.
        if ($resultQuarterly) {
        $arrData["linkeddata"] = array(); //"linkeddata" is responsible for feeding data and chart options to child charts.
		$arrDataMonth[2011]["linkeddata"] = array();
		$arrDataMonth[2012]["linkeddata"] = array();
		$arrDataMonth[2013]["linkeddata"] = array();
		$arrDataMonth[2014]["linkeddata"] = array();
		$arrDataMonth[2015]["linkeddata"] = array();
		$arrDataMonth[2016]["linkeddata"] = array();
        $i = 0;		
        if ($resultQuarterly) {
            while ($row = mysqli_fetch_array($resultQuarterly)) {
				//Collect the Year for which Quarterly drilldown will be created
                $year = $row['Year'];
				
				//Create the monthly drilldown data				
				$arrMonthHeader[$year][$row["Quarter"]] = array();
				$arrMonthData[$year][$row["Quarter"]] = array();
				
				// Retrieve monthly data based on year and quarter
				$strMonthly = "SELECT  * FROM monthly_kWh WHERE `Year` = '".$year."' and `Quarter` = '".$row["Quarter"]."' Order by FIELD( `Month`, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' )"  ;						
				$resultMonthly = $dbhandle->query($strMonthly) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
				
				//Loop through monthly results 
				 while ($rowMonthly = mysqli_fetch_assoc($resultMonthly)) {
						//Create the monthly data for each quarter
						if($rowMonthly['Quarter'] == $row["Quarter"])
						{
							array_push($arrMonthData[$year][$row["Quarter"]], array(
									"label" => $rowMonthly["Month"],
									"value" => $rowMonthly["Watts"]
								));
						}
					}
					//Create the data for monthly drilldown
					$arrMonthHeader[$year][$row["Quarter"]] = array(
										//Create the unique link id's provided from quarterly data as "2011Q1"
										"id" => $year . $row['Quarter'],
										//Create the data for the monthly charts for each quarter
										"linkedchart" => array(
											"chart" => array(
												//Create dynamic caption based on the year and quarter
												"caption" => "Breaker 1 Monthly Watts for ".$row["Quarter"]." of $year",
												"xAxisName"=> "Month",
												"yAxisName"=> "KiLo Watt Hours",
												"paletteColors"=> "#f5555C",
												"baseFont"=> "Open Sans",
												"theme" => "elegant"
											),
										"data" => $arrMonthData[$year][$row["Quarter"]]	
										)					
									);
					 //Finally push the data created into linkeddata node. Now our linked data for monthly drilldown for each quarter is ready
					 array_push($arrDataMonth[$year]["linkeddata"],$arrMonthHeader[$year][$row["Quarter"]]);


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Data for Linked Chart will start from here, SQL query from month table 
    $quarter = Q1;
    $strMonthly = "SELECT  Month, Watts, Year, Quarter FROM monthly_kWh WHERE 1";
    $resultMonthly = $dbhandle->query($strMonthly) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
    
    //If the query returns a valid response, preparing the JSON array.
        if ($resultMonthly) {
        $arrData["linkeddata"] = array(); //"linkeddata" is responsible for feeding data and chart options to child charts.
		$arrDataWeek[Q1]["linkeddata"] = array();
		$arrDataWeek[Q2]["linkeddata"] = array();
		$arrDataWeek[Q3]["linkeddata"] = array();
		$arrDataWeek[Q4]["linkeddata"] = array();
	
        $j = 0;	


        if ($resultMonthly) {
            while ($row = mysqli_fetch_array($resultMonthly)) {
				//Collect the Quarter for which Monthly drilldown will be created
                $quarter = $row['Quarter'];
				
				//Create the Weekly drilldown data				
				$arrWeekHeader[$quarter][$row["Month"]] = array();
				$arrWeekData[$quarter][$row["Month"]] = array();
				
				// Retrieve weekly data based on quarter and month
				$strWeekly = "SELECT  * FROM weekly_kWh WHERE `Quarter` = '".$quarter."' and `Month` = '".$row["Month"]."' Order by FIELD( `Week`, '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48' )"  ;						
				$resultWeekly = $dbhandle->query($strWeekly) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
				
				//Loop through weekly results 
				 while ($rowWeekly = mysqli_fetch_assoc($resultWeekly)) {
						//Create the weekly data for each month
						if($rowWeekly['Month'] == $row["Month"])
						{
							array_push($arrWeekData[$quarter][$row["Month"]], array(
									"label" => $rowWeekly["Week"],
									"value" => $rowWeekly["Watts"]
								));
						}
					}
					//Create the data for Weekly drilldown
					$arrWeekHeader[$quarter][$row["Month"]] = array(
										//Create the unique link id's provided from monthly data as "2011M1"
										"id1" => $quarter . $row['Month'],
										//Create the data for the weekly charts for each monthly
										"linkedchart" => array(
											"chart" => array(
												//Create dynamic caption based on the quarter and monthly
												"caption" => "Breaker 1 Weekly Watts for ".$row["Month"]." of $quarter",
												"xAxisName"=> "Week",
												"yAxisName"=> "KiLo Watt Hours",
												"paletteColors"=> "#f5555C",
												"baseFont"=> "Open Sans",
												"theme" => "elegant"
											),
										"data" => $arrWeekData[$quarter][$row["Month"]]	
										)					
									);
					 //Finally push the data created into linkeddata node. Now our linked data for weekly drilldown for each month is ready
					 array_push($arrDataWeek[$quarter]["linkeddata"],$arrWeekHeader[$quarter][$row["Month"]]);
	

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





				
				//Create the linkeddata for quarterly drilldown	
				//If the linnkeddata for a quarter of a year is ready and the year matches 
				 if (isset($arrData["linkeddata"][$i-1]) && $arrData["linkeddata"][$i-1]["id"] == $year) {					
					if($row["Quarter"] == 'Q4'){
						array_push($arrData["linkeddata"][$i-1]["linkedchart"]["data"], array(
							"label" => $row["Quarter"],
							"value" => $row["Watts"],
							//Create the link for quarterly drilldown as "newchart-json-2011Q1"
							"link" => "newchart-json-" . $year . $row["Quarter"]
						));	
					//If we've reached the last quarter, append the data created for monthly drilldown
					 $arrData["linkeddata"][$i-1]["linkedchart"] = array_merge($arrData["linkeddata"][$i-1]["linkedchart"],$arrDataMonth[$year]);
					}					
					else{
						array_push($arrData["linkeddata"][$i-1]["linkedchart"]["data"], array(
							"label" => $row["Quarter"],
							"value" => $row["Watts"],
							//Create the link for quarterly drilldown as "newchart-json-2011Q1"
							"link" => "newchart-json-" . $year . $row["Quarter"]
						));
					
					}
                }
				//Inititate the linked data for quarterly drilldown
				else {
					
                    array_push($arrData["linkeddata"], array(
                        "id" => "$year",
                        "linkedchart" => array(
                            "chart" => array(
								//Create dynamic caption based on the year
                                "caption" => "Breaker 1 Quarterly Watts - for $year",
                                "xAxisName"=> "Quarter",
                                "yAxisName"=> "KiLo Watt Hours",
                                "paletteColors"=> "#6baa01",
                                "baseFont"=> "Open Sans",
                                "theme" => "elegant"
                            ),
                            "data" => array(
                                array(
                                    "label" => $row["Quarter"],
                                    "value" => $row["Watts"],
									//Create the link for quarterly drilldown as "newchart-json-2011Q1"
									"link" => "newchart-json-" . $year . $row["Quarter"]
                                )
                            )
                        )
						
                    ));

                    $i++;
                }
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




				
				//Create the linkeddata for monthly drilldown	
				//If the linnkeddata for a month of a quarter is ready and the month matches 
				 if (isset($arrData["linkeddata"][$j-1]) && $arrData["linkeddata"][$j-1]["id1"] == $quarter) {					
					if($row["Month"] == 'Dec'){
						array_push($arrData["linkeddata"][$j-1]["linkedchart"]["data"], array(
							"label" => $row["Month"],
							"value" => $row["Watts"],
							//Create the link for quarterly drilldown as "newchart-json-2011Q1"
							"link" => "newchart-json-" . $year . $row["Month"]
						));	
					//If we've reached the last month, append the data created for weekly drilldown
					 $arrData["linkeddata"][$j-1]["linkedchart"] = array_merge($arrData["linkeddata"][$j-1]["linkedchart"],$arrDataWeek[$quarter]);
					}					
					else{
						array_push($arrData["linkeddata"][$j-1]["linkedchart"]["data"], array(
							"label" => $row["Month"],
							"value" => $row["Watts"],
							//Create the link for quarterly drilldown as "newchart-json-2011Q1"
							"link" => "newchart-json-" . $quarter . $row["Month"]
						));
					
					}
                }
				//Inititate the linked data for quarterly drilldown
				else {
					
                    array_push($arrData["linkeddata"], array(
                        "id1" => "$quarter",
                        "linkedchart" => array(
                            "chart" => array(
								//Create dynamic caption based on the year
                                "caption" => "Breaker 1 Monthly Watts - for $year",
                                "xAxisName"=> "Month",
                                "yAxisName"=> "KiLo Watt Hours",
                                "paletteColors"=> "#6baa01",
                                "baseFont"=> "Open Sans",
                                "theme" => "elegant"
                            ),
                            "data" => array(
                                array(
                                    "label" => $row["Month"],
                                    "value" => $row["Watts"],
									//Create the link for quarterly drilldown as "newchart-json-2011Q1"
									"link" => "newchart-json-" . $quarter . $row["Month"]
                                )
                            )
                        )
						
                    ));

                    $j++;
                }
		


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				
				
            }
			 
        }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
       //Convert the array created into JSON as our chart would recieve the dat ain JSON
		$jsonEncodedData = json_encode($arrData);
        
        $columnChart = new FusionCharts("column2d", "myFirstChart" , "300%", "500", "linked-chart", "json", "$jsonEncodedData"); 
        
        $columnChart->render();    //Render Method
             
        $dbhandle->close(); //Closing DB Connection
     
    }
}
?> 

     <style>
	.button{
	margin-left: 24.5%;
}
	
     </style>
     <body>
     <!-- DOM element for Chart -->
     <?php echo "<script type=\"text/javascript\" >
			   FusionCharts.ready(function () {
			FusionCharts('myFirstChart').configureLink({     
			overlayButton: {            
			message: 'Back',
			padding: '13',
			fontSize: '16',
			fontColor: '#F7F3E7',
			bold: '0',
			bgColor: '#22252A',           
			borderColor: '#D5555C'         }     });
			 });
			</script>" 
?>
         <div style="width:300px;" ><center><div id="linked-chart">Awesome Chart on its way!</div></center></div>
         
	<a href = "../dashboard.php"><button class = "button">Dashboard</button></a>

      </body>
</html>