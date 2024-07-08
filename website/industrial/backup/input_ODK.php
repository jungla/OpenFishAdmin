<?php
require("../top_foot.inc.php");
require("../functions.inc.php");

$_SESSION['where'][0] = 'industrial';

top();

?>
<p>
ODK Aggregate is running on the server and can be used to upload data to the database.<p/>
<p>
Steps required to configure ODK Collect are,
</p>
<ol>
    <li>Open the App</li>
    <li>In General Settings set,</li>
    <ul>
        <li>URL: http://192.168.0.1/ODKAggregate</li>
        <li>Username: user</li>
        <li>password: user</li>
    </ul>
    <li>Get, Fill and Finalize Blank Form: Peche_trawlers</li>
</ol>
<p>The uploaded data goes directly in the database.</p>
<?php
foot();