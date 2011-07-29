<html>
<head>
<title><?php echo $this->getData('title')?></title>
<link rel="shortcut icon" href="favicon.ico">
<?php echo $this->getData('-style-')?>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->getData('ENCODING')?>" />
<?php echo $this->getData('-meta-')?>
<script type="text/javascript" src="scripts/overlib.js"></script>
</head>
<?php echo $this->getData('-body-')?>
