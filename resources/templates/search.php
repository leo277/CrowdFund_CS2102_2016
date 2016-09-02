    <!-- Search Section -->
   <!-- Portfolio Grid Section -->
    <section id="search_bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Search bar</h2>
                    
                </div>
            </div>

           <table>
			<tr>
			<td> 
				<form method="post">
					Name: <input type="text" name="name" placeholder="project title">
					Description: <input type="text" name="description" placeholder="description">
					Pledged Amount: <input type="number" name="amount" placeholder="pledged amount">
					Raised Amount: <input type="number" name="raised" placeholder="raised amount">
					<input type="submit" name="formSubmit" value="Search">
				</form>
					</td> 
				</tr>
			</table>

        </div>
    </section>


<?php 	
	require_once(INCLUDES_PATH . "/dbconn.php");

if(isset($_POST['formSubmit'])) 
{
	$fields = array('name', 'description', 'amount', 'raised');
	$conditions = array();
	$query = '';
    // loop through the defined fields
	foreach($fields as $value){

	// if the field is set and not empty
		if($_POST[$value] != '') {
			//echo "hello";
			$query = "SELECT * FROM project ";
			// create a new condition while escaping the value inputed by the user (SQL Injection)
			if(is_numeric($_POST[$value])){
				//echo "is number";
				$conditions[] = "$value = " . pg_escape_string($_POST[$value]) . " ";
			}else{
				$searchTerm = strtolower($_POST[$value]);
				$conditions[] = "lower($value) LIKE '%" . pg_escape_string($searchTerm) . "%'";
			}
		}
	}

	// if there are conditions defined
	if(count($conditions) > 0) {
		// append the conditions
		$query .= " WHERE " . implode (' AND ', $conditions); // you can change to 'OR', but I suggest to apply the filters cumulative
		$query .= " ORDER BY project_id ASC ";
	}

	//echo "<b>SQL: </b>".$query."<br><br>";
	$result = pg_query($query) or die('Please enter at least one search term');

	$num_rows = pg_num_rows($result);
	print("<p><strong>$num_rows </strong> result for '$searchTerm'</p>");

	while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$projectID = $row['project_id'];
		$name = $row['name'];
		$description = $row['description'];
		$amount = $row['amount'];
		$raised = $row['raised'];
		$endDate = $row['end_date'];
		
		print('<table border="0" cellpadding="10" cellspacing="2" style="border:#000000 solid 1px; background-color:#ffffff;">');
		print('<tr valign="top">');
		print('<td class="header">Project ID</td>');
		print('<td class="header">Project Name</td>');
		print('<td class="header">Description</td>');
		print('<td class="header">Total Amount</td>');
		print('<td class="header">Amount Raised</td>');
		print('<td class="header">Closing Date</td>');
		print('</tr>');

		print('<tr valign="top">');				
		print("<td>$projectID</td>");
		print("<td>$name</td>");
		print("<td>$description</td>");
		print("<td>$amount</td>");
		print("<td>$raised</td>");
		print("<td> $endDate</td>");
		print('</tr><br>');
	} 
	print('</table>');
	pg_free_result($result);
}
pg_close($dbconn);
?>