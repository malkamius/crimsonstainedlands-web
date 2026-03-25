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

    <div style="margin:10px">
        <h3>Races</h3>
        <ul>
            <li><b>Human</b>: Balanced stats, suited for any class. (Max: 20 across all stats)</li>
            <li><b>Elf</b>: Highly intelligent and wise, agile but fragile. (Max: 25 Int, 20 Wis, 22 Dex)</li>
            <li><b>Dwarf</b>: Tough and strong, boasting high constitution and strength. (Max: 25 Con, 22 Str)</li>
            <li><b>Orc</b>: Physically imposing with great strength and constitution. (Max: 23 Str, 23 Con)</li>
            <li><b>Minotaur</b>: Extremely powerful and resilient beings. (Max: 23 Str, 22 Con)</li>
        </ul>

        <h3>Classes</h3>
        <ul>
            <li><b>Warrior</b>: Focus on weapon specialization, decent at taking and dealing damage. Masters shield block, dodge, parry, and feinting.</li>
            <li><b>Paladin</b>: Takes the lead in combat. Can grant sanctuary to themselves and specialize in either two-handed damage or weapon-and-shield blocking.</li>
            <li><b>Thief</b>: Excels at subterfuge. Can hide, sneak, pick locks, and incapacitate enemies by knocking them out or binding them.</li>
            <li><b>Bard</b>: Boosts party morale and health, while dashing enemy spirits with offensive lyrics.</li>
            <li><b>Mage</b>: Focuses on perfecting elemental magic. Unlocks new elemental spells upon mastering earlier ones.</li>
            <li><b>Shapeshifter</b>: Masters of transformation, able to change into various animal forms starting at levels 5-8 with unique shapefocus paths.</li>
            <li><b>Ranger</b>: Excels in the wilderness. Can gather healing herbs, ambush prey, and even forge imbued weapons in the wild.</li>
            <li><b>Healer</b>: Masters of restoration. Can cleanse maladies, heal friends, and grant blessings, flight, sanctuary, or battle frenzy.</li>
            <li><b>Assassin</b>: Martial arts specialists with improved kicks, dodging, and parrying skills. Can also bind wounds when hurt.</li>
        </ul>

        <h3>Alignments & Ethos</h3>
        <p>Characters are shaped by their Alignment (Good, Neutral, Evil) and Ethos (Lawful/Orderly, Neutral, Chaotic).</p>
        <ul>
            <li><b>Good vs. Evil</b>: Slaying characters of the opposite alignment grants bonus experience.</li>
            <li><b>Ethos</b>: Determines your character's adherence to rules, structure, and individual freedom (Lawful values society and rules; Chaotic values individual freedom and resists external codes; Neutral lies between).</li>
        </ul>
    </div>

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