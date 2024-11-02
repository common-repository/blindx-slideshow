
<?php /*
Plugin Name: blindx-slideshow
Plugin URI: http://prasanna.freeoda.com/blog/blindx-show
Description:Image slide show
Author:Prasanna 
Version: 1
Author URI:http://prasanna.freeoda.com/blog/*/

function blindx_show(){
	$img_siteurl = get_option('siteurl');
	$img_siteurl = $img_siteurl . "/wp-content/plugins/blindx-slideshow/";
	$imgfolder = "Slideshow/";
	$imgfolder = get_option('imgfolder')."/";
	$folder=getcwd(). "/wp-content/plugins/blindx-slideshow/".$imgfolder;
	/*echo $folder;
	$path=getcwd();
	echo $path;*/
	
	$imght = get_option('imght');
	$imghwt = get_option('imghwt');
	$imgcl = get_option('imgcl');

if ($dh = @opendir($folder)) {
					while (($f = readdir($dh)) !== false) {
						if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-3) == 'gif') || (substr(strtolower($f),-3) == 'png')) {
							$noimage++;
							//$images[] = array('filename' => $f, 'flastmod' => filemtime($mosConfig_absolute_path.$rootfolder.$_images_dir_."/".$f));
							$images[] = array('filename' => $f);  
						}
					}
				closedir($dh);	
			}		
			//echo "tttr--->".$images;			   
	  
?>	  	
	<style type="text/css">
	.blindXshow img { padding: 5px; border: 1px solid #ccc; background-color: #eee; width:<?php echo $imghwt;?>; height:<?php echo $imght;?> }
	</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $img_siteurl;?>jquery.cycle.all.mine.js"></script>
</head>
<body>
	<div  <?php if($imgcl==''){?>class="blindXshow" <?php } else { ?> class="<?php echo $imgcl;?>" <?php }?>>
					<?php	
									
				foreach($images as $img=>$val)
				{
				 	 foreach($val as $img=>$lval)
					{
					  
						echo "<img src='".$img_siteurl.$imgfolder.$lval."'  />"."<br/>";
					
					}
				} ?>
				</div>
				<script type="text/javascript">
$(document).ready(function() {
	<?php if($owncss==''){?>
	$('.blindXshow').cycle({
    fx: 'blindX'
	<?php } else { ?>
    $('.<?php echo $imgcl;?>').cycle({
    fx: 'blindX'
	<?php }?>
});
});

</script>
<?php 
}



function blindx_admin_option() 
{
	//include_once("extra.php");
	echo "<div class='wrap'>";
	echo "<h2>"; 
	echo wp_specialchars( "Image Gallery" ) ; 
	echo "</h2>";
    
	$imgfolder = get_option('imgfolder');
	$imght = get_option('imght');
	$imghwt = get_option('imghwt');
	$imgcl = get_option('imgcl');
	
	
	if ($_POST['cd_submit']) 
	{
		$imgfolder = stripslashes($_POST['imgfolder']);
		$imght = stripslashes($_POST['imght']);
		$imghwt = stripslashes($_POST['imghwt']);
		$imgcl = stripslashes($_POST['imgcl']);
		
		update_option('imgfolder', $imgfolder );
		update_option('imght', $imght );
		update_option('imghwt', $imghwt );
		update_option('imgcl', $imgcl );
	
	}
	?>
   

   
	<form name="cd_form" method="post" action="">
     <input name="hiddenid" type="hidden" id="hiddenid" value="<?php echo $edit_id; ?>">
        <input name="process" type="hidden" id="process" value="<?php echo $process; ?>">
   
	<table width="382" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="169">Image Folder </td>
    <td width="203"><input type="text" name="imgfolder" id="imgfolder" value="<?php echo $imgfolder; ?>" /></td>
  </tr>
  <tr>
    <td>Height</td>
    <td><input type="text" name="imght" id="imght"  value="<?php echo $imght; ?>"/></td>
  </tr>
  <tr>
    <td>Width</td>
    <td><input type="text" name="imghwt" id="imghwt"  value="<?php echo $imghwt; ?>"/></td>
  </tr>
  <tr>
    <td>Class</td>
    <td><input type="text" name="imgcl" id="imgcl"  value="<?php echo $imgcl; ?>"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="cd_submit" id="cd_submit" class="button-primary" value="Submit" type="submit" /></td>
  </tr>
</table>

</form>
<?php
	echo "</div>";
}



function blindx_install () 
 {
     add_option('imgfolder', "Slideshow");
	 add_option('imght', "170px");
	 add_option('imghwt', "160px");
	 add_option('imgcl', ""); 
  
  
  }

function blindx_deactivation() 
{
	delete_option('imgfolder');
	delete_option('imght');
	delete_option('imghwt');
	delete_option('imgcl');

}
function blindx_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo "Blind X Slideshow";
	
	echo $after_title;
	blindx_show();
	echo $after_widget;
}


function blindx_control()
{
	echo '<p>Image slideshow.<br> Goto Image slideshow link.';
	echo ' <a href="options-general.php?page=blindx-slideshow.php">';
	echo 'click here</a></p>';
}


function blindx_widget_init() 
{
  	register_sidebar_widget(('Blindx slideshow'), 'blindx_widget');   
	
	if(function_exists('register_sidebar_widget')) 	
	{
		register_sidebar_widget('Blindx slideshow', 'blindx_widget');
	}
	
	if(function_exists('register_widget_control')) 	
	{
		register_widget_control(array('Blindx slideshow', 'widgets'), 'blindx_control');
	} 
}

function blindx_add_to_menu() 
{
 add_options_page('Blindx slideshow', 'Blindx slideshow', 3, __FILE__, 'blindx_admin_option' );
}

add_action('admin_menu', 'blindx_add_to_menu');
add_action("plugins_loaded", "blindx_widget_init");
register_activation_hook(__FILE__, 'blindx_install');
register_deactivation_hook(__FILE__, 'blindx_deactivation');
add_action('init', 'blindx_widget_init');
add_option("jal_db_version", "2.0");






?>


