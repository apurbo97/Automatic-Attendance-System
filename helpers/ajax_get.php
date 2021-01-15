<?php 

include 'connection.php';

	$select_sql = "SELECT * FROM `attendance_log` WHERE date(`creation_date`) = CURDATE()";
	$result = $conn->query($select_sql);
	if ($result && $result->num_rows >0) {
		?>
		<table class="table">
           <tr>
               <th>Image</th>
               <th>Name</th>
               <th>Date/Time</th>
               <th>IN/OUT</th>
           </tr>
           <tbody>
           	<?php 
           		while ($row = $result->fetch_assoc()) {
           			?>
           			<tr>
                  <td><img height="100px" src="../labeled_images/<?=$row['name']?>/1.JPG"></td>
           				<td><?=$row['name']?></td>
           				<td><?=$row['creation_date']?></td>
           				<td><?=$row['type']?></td>
           			</tr>
           			<?php
           		}
           	 ?>
           </tbody>
       </table>
		<?php
	}
  else{
    ?>
    <table class="table">
           <tr>
               <th>Image</th>
               <th>Name</th>
               <th>Date/Time</th>
               <th>IN/OUT</th>
           </tr>
           <tbody>
                <tr>
                  <td colspan="4"><h3 style="text-align: center;">No Data Found!!<h3></td>
                </tr>
           </tbody>
       </table>
    <?php
  }


// print_r($profile_data);
?>