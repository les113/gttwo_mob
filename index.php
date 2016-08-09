<?php require_once('../connections/cardealer.php'); ?>
<?php
/* page content */
$colname_rs_pagecontent = "-1";
if (isset($_GET['pageid'])) {
  $colname_rs_pagecontent = (get_magic_quotes_gpc()) ? $_GET['pageid'] : addslashes($_GET['pageid']);
}
mysql_select_db($database_cardealer, $cardealer);
$query_rs_pagecontent = sprintf("SELECT * FROM pagecontent WHERE pageid = %s", $colname_rs_pagecontent);
$rs_pagecontent = mysql_query($query_rs_pagecontent, $cardealer) or die(mysql_error());
$row_rs_pagecontent = mysql_fetch_assoc($rs_pagecontent);
$totalRows_rs_pagecontent = mysql_num_rows($rs_pagecontent);
?>
<?php
// stocklist page
$maxRows_rs_stocklist = 8;
$pageNum_rs_stocklist = 0;
if (isset($_GET['pageNum_rs_stocklist'])) {
  $pageNum_rs_stocklist = $_GET['pageNum_rs_stocklist'];
}
$startRow_rs_stocklist = $pageNum_rs_stocklist * $maxRows_rs_stocklist;

mysql_select_db($database_cardealer, $cardealer);

// latest stock 
$query_rs_stocklist = "SELECT * FROM vehicledata, fuel, registration, transmission, manufacturer 
WHERE vehicledata.fuel = fuel.fuelID AND vehicledata.registration = registration.RegistrationID 
AND vehicledata.transmission = transmission.transmissionID 
AND vehicledata.manufacturer = manufacturer.manufacturerID 
ORDER BY price DESC";

$query_limit_rs_stocklist = sprintf("%s LIMIT %d, %d", $query_rs_stocklist, $startRow_rs_stocklist, $maxRows_rs_stocklist);
$rs_stocklist = mysql_query($query_limit_rs_stocklist, $cardealer) or die(mysql_error());
$row_rs_stocklist = mysql_fetch_assoc($rs_stocklist);

if (isset($_GET['totalRows_rs_stocklist'])) {
  $totalRows_rs_stocklist = $_GET['totalRows_rs_stocklist'];
} else {
  $all_rs_stocklist = mysql_query($query_rs_stocklist);
  $totalRows_rs_stocklist = mysql_num_rows($all_rs_stocklist);
}
$totalPages_rs_stocklist = ceil($totalRows_rs_stocklist/$maxRows_rs_stocklist)-1;

?>
<?php
// detail page
$colname_rs_vehicledetails = "-1";
if (isset($_GET['vehicleID'])) {
  $colname_rs_vehicledetails = (get_magic_quotes_gpc()) ? $_GET['vehicleID'] : addslashes($_GET['vehicleID']);
}
mysql_select_db($database_cardealer, $cardealer);
$query_rs_vehicledetails = sprintf("SELECT * FROM vehicledata, fuel, registration, manufacturer, transmission, bodytype
WHERE vehicleID = %s AND vehicledata.fuel = fuel.fuelID 
AND vehicledata.registration = registration.RegistrationID 
AND vehicledata.manufacturer = manufacturer.manufacturerID 
AND vehicledata.transmission = transmission.transmissionID 
AND vehicledata.bodytype = bodytype.bodytypeID", $colname_rs_vehicledetails);
$rs_vehicledetails = mysql_query($query_rs_vehicledetails, $cardealer) or die(mysql_error());
$row_rs_vehicledetails = mysql_fetch_assoc($rs_vehicledetails);
//$totalRows_rs_vehicledetails = mysql_num_rows($rs_vehicledetails);
?>
<?php
//Truncate description
function myTruncate($string, $limit, $break=".", $pad="...")
{
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }
  return $string;
}
?>
<?php
// Gallery
mysql_select_db($database_cardealer, $cardealer);
$query_rs_gallery = "SELECT * FROM gallery";
$rs_gallery = mysql_query($query_rs_gallery, $cardealer) or die(mysql_error());
$row_rs_gallery = mysql_fetch_assoc($rs_gallery);
$totalRows_rs_gallery = mysql_num_rows($rs_gallery);

/* recordset paging */
$rs_gallery = mysql_query($query_rs_gallery, $cardealer) or die(mysql_error());
$row_rs_gallery = mysql_fetch_assoc($rs_gallery);
$totalRows_rs_gallery = mysql_num_rows($rs_gallery);

$maxRows_rs_gallery = 15;
$pageNum_rs_gallery = 0;
if (isset($_GET['pageNum_rs_gallery'])) {
  $pageNum_rs_gallery = $_GET['pageNum_rs_gallery'];
}
$startRow_rs_gallery = $pageNum_rs_gallery * $maxRows_rs_gallery;

$colname_rs_gallery = "-1";
if (isset($_POST['price'])) {
  $colname_rs_gallery = (get_magic_quotes_gpc()) ? $_POST['price'] : addslashes($_POST['price']);
}
mysql_select_db($database_cardealer, $cardealer);

$query_limit_rs_gallery = sprintf("%s LIMIT %d, %d", $query_rs_gallery, $startRow_rs_gallery, $maxRows_rs_gallery);
$rs_gallery = mysql_query($query_limit_rs_gallery, $cardealer) or die(mysql_error());
$row_rs_gallery = mysql_fetch_assoc($rs_gallery);

if (isset($_GET['totalRows_rs_gallery'])) {
  $totalRows_rs_gallery = $_GET['totalRows_rs_gallery'];
} else {
  $all_rs_gallery = mysql_query($query_rs_gallery);
  $totalRows_rs_gallery = mysql_num_rows($all_rs_gallery);
}
$totalPages_rs_gallery = ceil($totalRows_rs_gallery/$maxRows_rs_gallery)-1;

$queryString_rs_gallery = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_gallery") == false && 
        stristr($param, "totalRows_rs_gallery") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_gallery = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_gallery = sprintf("&totalRows_rs_gallery=%d%s", $totalRows_rs_gallery, $queryString_rs_gallery);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="initial-scale=1.0, width=device-width"/> <!-- for iphone -->
<title>GT Two</title>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<link rel="stylesheet" href="style.css" />

</head>

<body>


<!--- home page -->
<div data-role="page" data-url="index.php"  data-theme="a">

	<div data-role="header">
		<h1>GT Two - Prestige Cars<br/>Cranleigh, Surrey</h1>
    </div>

	<div data-role="content">
		<a href="index.php">
			<img src="images/logo.png" width="300" alt="GT Two Logo" style="padding-left:0px;" />
		</a>

		<div id="home_mainimage" >
			<div>
				<div class="mainimage">
					<img src="http://www.gttwo.com/images/mainimage1.jpg" alt="main image"/>
				</div>
				<div class="transbg">
					<h1>Welcome to GT Two. Established in 2001 we have 35 years experience in the high performance and 
					prestige car sectors.</h1>
				</div>
			</div>
		</div>	
	
        <ul data-role="listview" data-inset="true">
        <li><a href="index.php?pageid=2#Content" rel="external">About Us</a></li>
        <li><a href="#Stocklist">Current Stock</a></li>
        <li><a href="index.php?pageid=3#Content" rel="external">Finance</a></li>
        <li><a href="index.php?pageid=4#Content" rel="external">Car Finder</a></li>
		<li><a href="#Gallery">Gallery</a></li>
        <li><a href="#Contact">Contact Us</a></li>
        </ul>
    </div>
    
    <?php include('footer.php') ?> 

</div>
    
<!-- content pages -->    
<div data-role="page"  data-theme="a" id="Content">

	<div data-role="header">
		<h1><?php echo $row_rs_pagecontent['pagetitle']; ?></h1>
    </div>

	<div data-role="content">
		
		<?php echo $row_rs_pagecontent['pagecontent']; ?>

		<h3><a href="#Contact">If you have any questions please contact us.</a></h3>
    </div>
    
    <?php include('footer.php') ?>   

</div>

<!-- stocklist page -->    
<div data-role="page" data-url="index.php" data-theme="a" id="Stocklist">

	<div data-role="header">
		<h1>Stocklist</h1>
    </div>

	<div data-role="content">
		<ul data-role="listview" data-inset="true" data-dividertheme="a">
        	<li data-role="list-divider">Our Used Cars</li>
      
        <?php
		$rs_stocklist_endRow = 8;
		$rs_stocklist_columns = 1; // number of columns
		$rs_stocklist_hloopRow1 = 0; // first row flag
		do {
		if($rs_stocklist_endRow == 0  && $rs_stocklist_hloopRow1++ != 0) echo "";
	    ?>

		<li>
		<a href="index.php?vehicleID=<?php echo $row_rs_stocklist['vehicleID']; ?>#Detail" rel="external" >
			<img src="http://www.gttwo.com/vehicledata/<?php echo $row_rs_stocklist['image1']; ?>" alt="<?php echo $row_rs_stocklist['title']; ?>" />
		</a>
		
		<h3><a href="index.php?vehicleID=<?php echo $row_rs_stocklist['vehicleID']; ?>#Detail" rel="external" ><?php echo $row_rs_stocklist['title']; ?></a></h3>
		
		<p><strong>Registration: </strong><?php echo $row_rs_stocklist['regYear']; ?></p>
		<p><strong>Mileage: </strong><?php echo $row_rs_stocklist['mileage']; ?> Miles</p>
		<p><strong>Transmission: </strong><?php echo $row_rs_stocklist['transmission']; ?></p>
		<p><strong>Fuel: </strong><?php echo $row_rs_stocklist['fuel']; ?></p>
		<h5>Price: &pound;<?php echo number_format($row_rs_stocklist['price'],0); ?></h5>
		<p><a href="index.php?vehicleID=<?php echo $row_rs_stocklist['vehicleID']; ?>#Detail" rel="external" >More details..</a></p>
		<p>&nbsp;</p>
		</li>

        <?php  $rs_stocklist_endRow++;
		if($rs_stocklist_endRow >= $rs_stocklist_columns) {
		  ?>

      <?php
		 $rs_stocklist_endRow = 0;
		  }
		} while ($row_rs_stocklist = mysql_fetch_assoc($rs_stocklist));
		if($rs_stocklist_endRow != 0) {
		while ($rs_stocklist_endRow < $rs_stocklist_columns) {
			echo("&nbsp;");
			$rs_stocklist_endRow++;
		}
		echo("");
		}?>
        
        </ul>        
    </div>
    
    <?php include('footer.php') ?> 

</div>

<!-- detail page -->    
<div data-role="page"  data-theme="a" id="Detail">

	<div data-role="header">
		<h1>Vehicle Details</h1>
    </div>

	<div data-role="content">
	<ul data-role="listview" data-inset="true" data-dividertheme="a">
         	<li data-role="list-divider"><?php echo $row_rs_vehicledetails['title']; ?></li>
		
		<p>&nbsp;</p>
	  <?php  $i = 1;
		while ($i <= 6)
		{ 
		 if ($row_rs_vehicledetails['image'.$i] != NULL)
			{?>
				<div style="margin-bottom:10px">
					<img src="http://www.gttwo.com/vehicledata/<?php  echo $row_rs_vehicledetails['image'.$i]; ?>" alt="<?php echo $row_rs_vehicledetails['title'];?> thumbnail image" title="<?php echo $row_rs_vehicledetails['title']; ?>"  class="detailimage"/>
				</div> 
	  <?php } 
		$i++;
		};	
		?>

		<h3><?php echo $row_rs_vehicledetails['title']; ?></h3>
		<p><strong>Registration: </strong><?php echo $row_rs_vehicledetails['regYear']; ?></p>
		<p><strong>Mileage: </strong><?php echo $row_rs_vehicledetails['mileage']; ?> Miles</p>
		<p><strong>Transmission: </strong><?php echo $row_rs_vehicledetails['transmission']; ?></p>
		<p><strong>Fuel: </strong><?php echo $row_rs_vehicledetails['fuel']; ?></p>
		<p><?php  $shortdesc = myTruncate($row_rs_vehicledetails['description'], 100);echo "$shortdesc"; ?></p>
		<h4><strong>Price: </strong>&pound;<?php echo number_format($row_rs_vehicledetails['price'],0); ?></h4>
		<h3><a href="#Contact">contact us for further details of this vehicle.</a></h3>

    </ul>  
    </div>
    
    <?php include('footer.php') ?> 

</div>

<!-- gallery page -->    
<div data-role="page" data-theme="a" id="Gallery">

	<div data-role="header">
		<h1>Gallery</h1>
    </div>

	<div data-role="content">
		<h1>Recently sold vehicles</h1>
		<?php do { ?>
			<div class="gallery">
				<img src="http://www.gttwo.com/gallery/<?php echo $row_rs_gallery['image']; ?>" alt="<?php echo $row_rs_gallery['description']; ?>" />
				<div class="cardesc"><h3><?php echo $row_rs_gallery['description']; ?></h3></div>
			</div>
		<?php } while ($row_rs_gallery = mysql_fetch_assoc($rs_gallery)); ?>
    </div>
 
    <?php include('footer.php') ?> 
    
</div>

<!-- contact page -->    
<div data-role="page" data-theme="a" id="Contact">

	<div data-role="header">
		<h1>Contact Us</h1>
    </div>

	<div data-role="content">
		<h1>Contact GT Two at:</h1>
		<p><strong>email: </strong><a href="mailto:sales@gttwo.com">sales@gttwo.com</a><br/><br/>
  		<strong>tel: </strong><a href="tel:01483548991"> 01483 548991</a><br/><br/>
  		<strong>mob: </strong><a href="tel:07764460891"> 07764 460891</a></p>

		  <p>GT TWO<br/>
			Stable Cottage<br/>
			Horsham Road<br/>
			Cranleigh<br/>
			Surrey<br/>
			GU6 8EJ</p>
		<p><strong>map: </strong><a href="https://www.google.co.uk/maps/place/Cranleigh,+Surrey+GU6+8EJ/@51.1292198,-0.471183,15z/data=!3m1!4b1!4m2!3m1!1s0x4875c47b8eb33e85:0x80d8f736f51db38?hl=en">click here</a></p>
    </div>
 
    <?php include('footer.php') ?> 
    
</div>

    <?php include('statcounter.php') ?> 

</body>
</html>

<?php
mysql_free_result($rs_pagecontent);
mysql_free_result($rs_stocklist);
mysql_free_result($rs_vehicledetails);
mysql_free_result($rs_gallery);
?>