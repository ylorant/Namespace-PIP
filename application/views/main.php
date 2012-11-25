<?php include('header.php'); ?>
	
    <div id="content">
        
        <h1>Welcome to Namespace-PIP</h1>
        <p>To get started please read the documentation at <a href="http://ylorant.github.com/Namespace-PIP/doc/">http://ylorant.github.com/Namespace-PIP/doc/</a>.</p>
        <p>System info : <?php echo php_uname('a'); ?></p>
	<p>OS Version : <?php echo file_get_contents('/etc/debian_version'); ?></p>
	<a onclick="debug.toggleWindow();">Toggle debug window</a>
    </div>

<?php include('footer.php'); ?>
