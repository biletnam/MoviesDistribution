<table width="100%" class="tablesorter">
<tr>
			<th>Šifra</th>
			<th>Naziv</th>
			<th>Org. Naziv</th>
			<th>Žanr</th>
			<th>Trajanje</th>
			<th>Br. Činova</th>
			<th>Tehnika</th>
			<th>Producent</th>
			<th></th>
			<th></th>
</tr>
		
<?php 	
	if( isset( $data ) && count( $data ) > 0 )
	{
		foreach( $data as $row )
		{
			echo '<tr>';
			echo '<td>'.$row['filmovi_id'].'</td>';
			echo '<td>'.$row['naziv'].'</td>';
			echo '<td>'.$row['originalni_naziv'].'</td>';
			echo '<td>'.$row['zanr'].'</td>';
			echo '<td>'.$row['trajanje'].'</td>';
			echo '<td>'.$row['broj_cinova'].'</td>';
			echo '<td>'.$row['tehnika'].'</td>';
			echo '<td>'.$row['producent'].'</td>';
			echo '<td><a href="javascript:editMovie('.$row['filmovi_id'].')"><img src="images/edit.png"/></a></td>';
			echo '<td><a href="javascript:deleteMovie('.$row['filmovi_id'].')"><img src="images/del.png"/></a></td>';
		}
	}	
	
?>
	  
</table>