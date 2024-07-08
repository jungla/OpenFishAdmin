<?php
require("top_foot.inc.php");
require("functions.inc.php");

top();

?>

<h3>Input Methods</h3>
<ul>
<li><a href="input_form_art.php">HTML form</a> Records can be inserted one-by-one via html form</li>
<li><a href="input_csv_art.php">Upload CSV</a> A formatted CSV file can be uploaded</li>
<li><a href="input_ODK_art.php">Use ODK</a> ODK Collect can be configured to upload data</li> 
</ul>
</br>
Other possible upload methods (not yet implemented)
<ul>
<li><b>Send Email</b> A formatted email can be sent to the server with the required info (in progress)</li>
<li><b>Synchronize database Access</b> Upload tables from a database MS Access</li>
</ul>
