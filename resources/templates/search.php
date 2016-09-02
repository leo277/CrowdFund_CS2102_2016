<!-- Search Section -->
<section id="search_bar">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<h3>Search bar</h3>
			</div>
		</div>
	</div>

	<!-- Search form template -->
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<form class="form-inline" method="post">
					<div class="form-group">
						<input type="text" class="form-control" name="name" placeholder="project title">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="description" placeholder="description">
					</div>
					<div class="form-group">
						<input type="number" class="form-control" name="amount" placeholder="pledged amount">
					</div>
					<div class="form-group">
						<input type="number" class="form-control" name="raised" placeholder="raised amount">
					</div>
					<button type="submit" name="formSubmit" value="Search" class="btn btn-primary">Search</button>
				</form>
			</div>
		</div>
	</div>
</section>


<?php 
/************************
 *establish db connection
 ************************/	
require_once(INCLUDES_PATH . "/dbconn.php");

/**********************************************************
 *check if form is submitted, if yes, start SQL query
 **********************************************************/	
if(isset($_POST['formSubmit'])) 
{
	$fields = array('name', 'description', 'amount', 'raised');
	$conditions = array();
	$query = '';
	
	/*********************************
	 *get all search keywords, if any
	 *********************************/	
	foreach($fields as $value){

		if($_POST[$value] != '') {

			$query = "SELECT * FROM project ";
			//doing case insensitive search on strings and strict comparison on numbers
			if(is_numeric($_POST[$value])){
				$conditions[] = "$value = " . pg_escape_string($_POST[$value]) . " ";
			}else{
				$searchTerm = strtolower($_POST[$value]);
				$conditions[] = "lower($value) LIKE '%" . pg_escape_string($searchTerm) . "%'";
			}
		}
	}

	/**************************************************
	 *append search query if search keywords are found 
	 **************************************************/
	if(count($conditions) > 0) {
		$query .= " WHERE " . implode (' AND ', $conditions); // you can change to 'OR', but I suggest to apply the filters cumulative
		$query .= " ORDER BY project_id ASC ";
	}

	/**************************************************
	 *display SQL query (for testing) 
	 **************************************************/
	echo '<div class="container">'; 
		echo '<div class="row">'; 
			echo '<div class="col-lg-12 text-center">';
				echo "<b>SQL: </b>".$query."<br><br>"; 
			echo "</div>"; 
		echo "</div>"; 
	echo "</div>"; 

	/**************************************************
	 *SQL query validate
	 **************************************************/
	$result = pg_query($query) or die('Please enter at least one search term');


	/**************************************************
	 *get SQL query result count
	 **************************************************/
	$num_rows = pg_num_rows($result);
	echo '<div class="container">'; 
		echo '<div class="row">'; 
			echo '<div class="col-lg-12 text-center">';
				echo "<p><strong>$num_rows </strong> results for '$searchTerm'</p>";
			echo "</div>"; 
		echo "</div>"; 
	echo "</div>"; 

	/**************************************************
	 *display SQL query result (html formatting)
	 **************************************************/
	echo '<div class="container">'; 
		echo '<div class="row">'; 
		while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$projectID = $row['project_id'];
			$name = $row['name'];
			$description = $row['description'];
			$amount = $row['amount'];
			$raised = $row['raised'];
			$endDate = $row['end_date'];

			echo '<table class="table table-hover table-inverse">';
				echo "<thead>";
					echo "<tr>";
					echo "<th>Project ID</th>";
					echo "<th>Project Name</th>";
					echo "<th>Description</th>";
					echo "<th>Total Amount</th>";
					echo "<th>Amount Raised</th>";
					echo "<th>Closing Date</th>";
					echo "</tr>";
				echo "</thead>";

				echo("<tbody>");
					echo "<tr>";
					echo "<td>$projectID</td>";
					echo "<td>$name</td>";
					echo "<td>$description</td>";
					echo "<td>$amount</td>";
					echo "<td>$raised</td>";
					echo "<td>$endDate</td>";
					echo "</tr><br>";
				echo("</tbody>");
			echo "</table>";
		} 
		echo "</div>"; 
	echo "</div>"; 
	/**************************************************
	 *end html formatting)
	 **************************************************/

	/**************************************************
	 *clsoe DB
	 **************************************************/
	pg_free_result($result);
}
pg_close($dbconn);
?>