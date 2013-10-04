<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

  define(MOB_TOOLS_URL, "https://www.flippersm.se");
  $mob_tools_url = MOB_TOOLS_URL;
    $oHTTPContext = new HTTPContext();

    $sub = "World";
    if ($oHTTPContext->getString("create") != null)
	{
		$sub = "Universe";
	} else if ($oHTTPContext->getString("reset") != null)
	{
		$sub = "2001";
	}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mobile tools">
    <meta name="author" content="Andreas Thorsen">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>Mobile tools</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="bootstrap/css/standings.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=MOB_TOOLS_URL;?>/mobile/tools.php">Mobile tools</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="<?=$mob_tools_url;?>/mobile/tools.php">Entry</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <div class="starter-template">
	  <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
	  <input type="submit" name="create" class="btn btn-primary" value="Create entry">
	  <input type="submit" name="reset" class="btn btn-primary" value="Reset entry">
	  </form>

<?php
		echo "Hello " . $sub;
?>

	  </div>

	</div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
  </body>
</html>
