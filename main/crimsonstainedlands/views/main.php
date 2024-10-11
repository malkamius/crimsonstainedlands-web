<?php 
    include_once 'controllers/PageController.php';
    $Page->Title = "Homepage";
    $Page->Start();
?>
	<h2>Welcome to Crimson Stained Lands MUD!</h2>
    <p style="max-width: 100ch; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
    Crimson Stained Lands is a Multi-User Dungeon (MUD) featuring races such as <br>
    human, elf, dwarf, pennatus, orc, and minotaur.  Play as one of our many classes <br>
    including assassin, bard, healer, mage, paladin, ranger, shapeshifter, thief, and warrior.</p>

    <p style="margin:10px">There is currently no PvP, but we have plans for arena-style combat in the future.</p>

    <p style="margin:10px">You can connect to our MUD off and on at host: <code>crimsonstainedlands.net</code> and port: <code>4000</code></p>
	<p style="margin:10px">If your client supports secure connections, you can use <code>crimsonstainedlands.net</code> and port: <code>4001</code></p>

    <h2>Resources:</h2>
    <ul>
        <li><a href="damagecalculator.php">Damage Calculator</a></li>
        <li><a href="guild_skilllevels.php">Guild Skill Levels</a></li>
        <li><a href="Map.jpg" target="_blank">MUD Map</a></li>
		<li><a href="https://mudmapbuilder.github.io/">Maps and EQ lists provided by Roman Shapiro</a></li>
		<li><a href="maps.php">Most Individual Maps</a> provided by Roman Shapiro</li>
		<li><a href="areas_json.php">Maps JSon used to generate images</a> provided by Roman Shapiro</li>
    </ul>
	<br />
	<a href="https://github.com/rds1983/MUDMapBuilder">Roman Shapiro's map making utility</a>
	<br />
    <h2>Source Code:</h2>
    <p>The source code for our MUD is publicly available on GitHub. You can check it out <a href="https://github.com/malkamius/CrimsonStainedLands" target="_blank">here</a>.</p>
<?php 
    $Page->End();
?>