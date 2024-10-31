<link type="text/css" rel="StyleSheet" href="<?php echo plugins_url("css/page.css", __FILE__); ?>" />
<script type='text/javascript' src="<?php echo plugins_url("js/jquery.validate.js", __FILE__); ?>"></script>
<script type="text/javascript">
jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery("#add_form").validate();
		jQuery('#m1').fadeOut(5000);
		
		jQuery("#all_sites").click(function(){
			if((this.checked))
			{
				jQuery(".post_sites").each(function(){
					this.checked = true;
				});
			}
			else
			{
				jQuery(".post_sites").each(function(){
					this.checked = false;
				});
			}
		});
	});
</script>	
<?php
global $wpdb;
$table = $wpdb->prefix."pcx";

if(isset($_POST['register']))
{
	//echo "<pre>"; print_r($_POST); exit;
	if(!empty($_POST['sites']))
	{
		extract($_POST);	
		$sql = "SELECT siteurl, username, password FROM ".$table." WHERE id IN (".implode(',', $_POST['sites']).")";
		$sites = $wpdb->get_results($sql);
		if(!empty($sites))
		{
			foreach($sites as $site)
			{
				pcs_add_post($post_title, $content, $site->siteurl, $site->username, $site->password, $category, $tags);
			}
			$message ="<div id='m1'>Content posted successfully...</div>";
		}
	}
	else
	{
		$message ="<div id='m1'>No site posted successfully...</div>";
	}
}//end of submit if

if($message != '')
{
?>
		<div class="middle_div">
			<div class="middle_msg">
				<?php echo $message;?>
			</div>
			<div class="middle_msg">
				<a href="?page=pcx_content" class="a_msg"> Add more posts</a>
			</div>
		</div>
		
		
<?php
}//end of message if
else
{
	global $wpdb;
	$sql = "SELECT id, sitetitle, siteurl FROM ".$table;
	$all_sites = $wpdb->get_results($sql);
?>
<div id="msg" class="wrap">

		<table class="widefat" style="margin-bottom: 0.5em;">
        <thead>
            <tr valign="top">
                <th>Important Instructions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size:12px;">
                    &#8226;&nbsp; Please verify remote site has <strong>XMLRPC enabled</strong> from <strong>Settings >> Writing</strong>. 
                    <br class="clear">
                    &#8226;&nbsp; Please also verify Current site & remote sites has <strong>CURL enabled</strong>.
                    <br class="clear">
                </td>
            </tr>
        </tbody>
	</table>
		<div id="message" class="updated" style="display:none;margin:20px 0px;"></div>
        <h2>Add new post to remote site(s)</h2>
		<form name="frm" id="add_form" method="post">	
			<table class="widefat" style="margin-top: 0.5em;">
<!--			<thead>
					<tr valign="top">
						<th bgcolor="#dddddd" colspan="4">Add new post to remote site(s).</th>
					</tr>
				</thead>
-->
				<tbody>
					<tr> 
						<th width="25%" scope="row">Post Title</th>
						<td colspan="3">
							<input type="text" name="post_title" class="required" size="130" />
						</td>
					</tr>
						
				
					<tr>
						<th width="25%" scope="row">Post Content</th>
						<td colspan="3">
                        	<div id="poststuff">
								<?php the_editor("", "content", "", true); ?>
                            </div>
                        </td>
					</tr>
                    
                    <tr> 
						<th width="25%" scope="row">Post Tags</th>
						<td colspan="3">
							<input type="text" name="tags" size="130" /> <br/>
                            <strong>Note : Enter multiple tags each seperated by comma ( , )</strong>
						</td>
					</tr>
                    
                    <?php
					if(!empty($all_sites))
					{
?>
	                    <tr> 
							<th width="25%" scope="row">Remote Sites</th>
                            <td colspan="3">
                            	<input type="checkbox" id="all_sites" name="all_sites" /> <label for="all_sites"><span style="cursor:pointer; color:#21759B">Check / Uncheck All</span></label>
                            	<ul class="remote_site_list">
<?php						
						foreach($all_sites as $site)
						{
?>
							<li>
                                <input type="checkbox" name="sites[]" class="post_sites" value="<?php echo $site->id; ?>" /> <a href="<?php echo $site->siteurl; ?>" target="_blank"><?php echo $site->sitetitle; ?></a>
                            </li>
<?php							
						}
?>
								</ul>
							</td>
						</tr>
<?php						
					}
?>
				</tbody>
                
			</table>
			
		<p class="submit">
			<input type="submit" class="button-primary" name="register" value="Publish" />
			<input type="reset" name="reset" value="Reset" class="button-primary" />
		</p>
		</form>
		<div class="back"><h2><a href="?page=pcx_add_sites" style="text-decoration:none;">Back To Listing Page</a></h2></div>
</div>
<?php
}
?>