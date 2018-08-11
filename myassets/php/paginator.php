
<?php
	
	// How many adjacent pages should be shown on each side?
	$adjacents =2;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query = "SELECT COUNT(*) as num FROM ".$q;
	$total_ = mysqli_query($con,$query);
	//$total_pages = mysqli_fetch_array($total_);
	//$total_pages = $total_pages[num];
	$row=mysqli_fetch_array($total_);
	$total_pages = $row['num'];
	
	/* Setup vars for query. */
	//--(i commented it out)$targetpage = "paginator2.php"; 	//your file name  (the name of this file)
	//--$limit = 5; 								//how many items to show per page
	//--@$page = intval($_GET['page']) ;
	@$page =  number_format($_GET['page']);
	//$page = 1;
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	//$sql = "SELECT * FROM $tbl_name order by rec_id desc LIMIT $start, $limit ";	
	//$sql = "SELECT * ".$q. " ORDER by rec_id desc LIMIT $start, $limit";	
	$sql = "SELECT * FROM ".$q."  LIMIT $start, $limit";	
	$my_paginator_query = mysqli_query($con,$sql) or die('Error in the Query');
	
	

	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage&page=$prev\">&laquo; previous</a>";
		else
			$pagination.= "<span class=\"disabled\"> &laquo; previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1 &nbsp; &nbsp;</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2 &nbsp;&nbsp;</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1 &nbsp;</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2&nbsp; </a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&page=$next\">next &raquo; </a>";
		else
			$pagination.= "<span class=\"disabled\">next &raquo;</span>";
		$pagination.= "</div>\n";		
	}
	else{
		//display_error('error');
	}
?>

	<?php
		//while($row = mysqli_fetch_array($my_paginator_query))
		//{
	?>
		<a href=""><?php //echo $row['post_title']?><br></a>
	<?php
		//}
	?>

<?//=$pagination?>