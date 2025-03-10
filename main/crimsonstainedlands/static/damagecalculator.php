<?php 
    include 'controllers/PageController.php';
    $Page->Title = "Damage Calculator";
    $Page->Start();
?>
	<table>
		<tr>
			<td>Number of hits</td>
			<td><input id="NumberHits" type="text" value="1" />
		</tr>
		<tr>
			<td>Dice Sides</td>
			<td><input id="DiceSides" type="text" value="6" />
		</tr>
		<tr>
			<td>Dice Count</td>
			<td><input id="DiceCount" type="text" value="24" />
		</tr>
		<tr>
			<td>Dice Bonus</td>
			<td><input id="DiceBonus" type="text" value="80" />
		</tr>
		<tr>
			<td>Minimum Damage</td>
			<td><label id="MinDamage">0</label></td>
		</tr>
		<tr>
			<td>Maximum Damage</td>
			<td><label id="MaxDamage">0</label></td>
		</tr>
		<tr>
			<td>Average Damage</td>
			<td><label id="AvgDamage">0</label></td>
		</tr>
		<tr>
			<td>Average Per Round</td>
			<td><label id="AvgPerRound">0</label></td>
		</tr>
		<tr>
			<td colspan="2"><center><input type="button" value="Calculate" onclick="CalculateDamage()"/></td>
		</tr>
		<script>
			function CalculateDamage()
			{
				var numHits = document.getElementById("NumberHits").value;
				var DiceSides = parseInt(document.getElementById("DiceSides").value);
				var DiceCount = parseInt(document.getElementById("DiceCount").value);
				var DiceBonus = parseInt(document.getElementById("DiceBonus").value);
				document.getElementById("MinDamage").innerHTML = (DiceCount + DiceBonus);
				document.getElementById("MaxDamage").innerHTML = (DiceCount * DiceSides + DiceBonus);
				document.getElementById("AvgDamage").innerHTML = (((DiceCount + DiceBonus) + (DiceCount * DiceSides + DiceBonus)) / 2);
				document.getElementById("AvgPerRound").innerHTML = (((DiceCount + DiceBonus) + (DiceCount * DiceSides + DiceBonus)) / 2 * numHits);
			}
		</script>
	</table>
<?php 
    $Page->End();
?>