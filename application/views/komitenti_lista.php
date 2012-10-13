<table width="100%" class="tablesorter">
<thead>
	<tr>
		<th>Naziv</th>
		<th>Adresa</th>
		<th>P.I.B</th>
		<th>Mesto</th>
		<th>Tel1</th>
		<th>Tel2</th>			
		<th>E-mail</th>
		<th>PIB</th>
		<th><sub>Gledanost</sub></th>
		<th></th>
		<th></th>		
	</tr>
</thead>	
	
		
<?php 	
	if( isset( $data ) && count( $data ) > 0 )
	{
		foreach( $data as $row )
		{
			echo '<tr>';
			echo '<td>'.$row['naziv'].'</td>';
			echo '<td>'.$row['adresa'].'</td>';
			echo '<td>'.$row['pbroj'].'</td>';
			echo '<td>'.$row['mesto'].'</td>';
			echo '<td>'.$row['tel1'].'</td>';
			echo '<td>'.$row['tel2'].'</td>';
			echo '<td><sub>'.$row['email'].'</sub></td>';
			echo '<td>'.$row['pib'].'</td>';
			echo '<td>'.$row['gledanost'].'</td>';
			echo '<td><a href="javascript:editUser('.$row['komitenti_id'].')"><img src="images/edit.png"/></a></td>';
			echo '<td align="center" valign="center"><a href="javascript:deleteUser('.$row['komitenti_id'].')"><img src="images/del.png"/></a></td>';
			echo '</tr>';
		}
	}	
	
?>
	  
</table>