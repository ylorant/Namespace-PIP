<?php include('header.php'); ?>
	
    <div id="content">
        
        <h1>Welcome to PIP</h1>
        <p>To get started please read the documentation at <a href="http://pip.dev7studios.com/">http://pip.dev7studios.com</a>.</p>
        <p>System info : <?php echo php_uname('a'); ?></p>
	<p>OS Version : <?php echo file_get_contents('/etc/debian_version'); ?></p>
    </div>

<?php include('footer.php'); ?>