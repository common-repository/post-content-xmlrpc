<link type="text/css" rel="StyleSheet" href="<?php echo plugins_url("css/page.css", __FILE__); ?>" />
<script type='text/javascript' src="<?php echo plugins_url("js/jquery.validate.js", __FILE__); ?>"></script>
<script type="text/javascript">
jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery("#add_form").validate();
		jQuery('#m1').fadeOut(7000);
	});
</script>	

<script type="text/javascript">

	function display_confirm()
	{
	
		var del = confirm("Do you really want to delete this record ?");
		if(del==false){
			return false;
		}
		else {
			return true;
		}
	
	}
	
	function validate_chkbox()
	{
		var chk=document.getElementsByName('users[]');
		var i=0;	
		for(i=0;i<chk.length;i++)
		{	//alert(chk.length);
			if(chk[i].checked==true) { break; }
		}

		if(i==chk.length)
		{
			document.getElementById('message').style.display="block";
			document.getElementById('message').innerHTML="Please select atleast one check box";
			document.location.href="#msg";
			return false;

		}
		else
			return true;
	
	}
	
	function dropDownAction()
	{
		var a=document.getElementById('act').value;
		if(a==0)
		{
			//alert("Please select action from dropdown"); return false;
			document.getElementById('message').style.display="block";
			document.getElementById('message').innerHTML="Please select action ";
			document.location.href="#msg";
			return false;
		}
		return true;
	}

	function validate()
	{
		document.getElementById('message').style.color = "red";
		document.getElementById('message').style.padding = "5px 5px";

		if(validate_chkbox() && dropDownAction()) 
		{
			return true;
		}
	return false;
	}
</script>	
		
<?php


global $wpdb;
if($_GET['mode']=="add")
{
//echo "in add mode";exit;
	if($_POST['register']=="Add")
	{
		extract($_POST);	
		$table=$wpdb->prefix."pcx";
		$success = $wpdb->insert($table, array('sitetitle'=> $sitetitle, 'siteurl' => $siteurl, 'username' => $username, 'password' => $password));
		
		if($success === false)
		{
			$message ="<div id='m1' style='color:red'>Error while saving site record...</div>";
		}
		else
		{
			$message ="<div id='m1'>Site record added successfully...</div>";
		}
		
	}//end of submit if
	
	if($_GET['id'])
	{
		$id=$_GET['id'];
		$table=$wpdb->prefix."pcx";
		$results_edit=$wpdb->get_row("SELECT * FROM ".$table." WHERE id = ".$id);	
	}

	if($_POST['register']=="Update")
	{
		extract($_POST);
		$table=$wpdb->prefix."pcx";
		$success = $wpdb->update($table, array('sitetitle'=> $sitetitle, 'siteurl' => $siteurl, 'username' => $username, 'password' => $password), array('id'=>$id));
		$rt_type = gettype($success);
		
		if($rt_type == "boolean")
		{
			$message ="<div id='m1' style='color:red'>Error while updating site record...</div>";
		}
		else
		{
			$message ="<div id='m1'>Site record updated successfully...</div>";
		}
	}

if($message!='')
{
?>
		<div class="middle_div">
			<div class="middle_msg">
				<?php echo $message;?>
			</div>
			<div class="middle_msg">
				<a href="?page=pcx_add_sites" class="a_msg"> Back to main page </a>
			</div>
			<div class="middle_msg">				
					OR
			</div>
			<div class="middle_msg">				
			<a href="?page=pcx_add_sites&mode=add" class="a_msg">Add more site</a>
			</div>
		</div>
		
		
<?php
}//end of message if
else
{
?>	
<div id="msg" class="wrap">
		
		<div id="message" class="updated" style="display:none;margin:20px 0px;"></div>
		<form name="frm" id="add_form" method="post">	
			<table class="widefat" style="margin-top: 0.5em;">
				<thead>
					<tr valign="top">
						<th bgcolor="#dddddd" colspan="4">Add New Site</th>
					</tr>
				</thead>

				<tbody>
                	<tr> 
						<th width="25%" scope="row">Site</th>
						<td colspan="3">
							<input type="text" name="sitetitle" value="<?php echo $results_edit->sitetitle; ?>" class="required" size="40" />
                            <strong>(Put Site name for your reference)</strong>
						</td>
					</tr>
                
					<tr> 
						<th width="25%" scope="row">Site</th>
						<td colspan="3">
							<input type="text" name="siteurl" value="<?php echo $results_edit->siteurl; ?>" class="required url" size="40" />
                            <strong>(Put your site url without / at the end of url)</strong>
						</td>
					</tr>
						
				
					<tr>
						<th width="25%" scope="row">Username</th>
						<td colspan="3">
                        	<input type="text" name="username" value="<?php echo $results_edit->username; ?>" class="required" size="30" />
                            <strong>(Put Wordpress username with admin access for above site.)</strong>
                        </td>
					</tr>
                    
                    <tr>
						<th width="25%" scope="row">Password</th>
						<td colspan="3">
                        	<input type="text" name="password" value="<?php echo $results_edit->password; ?>" class="required" size="30" />
                            <strong>(Put password of above username.)</strong>
                        </td>
					</tr>
				</tbody>
			</table>
			
		<p class="submit">
			<input type="submit" class="button-primary" name="register" value="<?php if($_GET['id'])
			{
				echo "Update";
			}
			else
			{
				echo "Add";	
			}	
		 	?>" />
			<input type="reset" name="reset" value="Reset" class="button-primary" />
		</p>
		</form>
		<div class="back"><h2><a href="?page=pcx_add_sites" style="text-decoration:none;">Back To Listing Page</a></h2></div>
</div>

<?php
}//end of message else	
}
else
{

	$message = '';
	if($_POST['go'] == "Apply")
	{
		//echo $id=implode($_POST['chk'],",");
		extract($_POST);
		$table = $wpdb->prefix."pcx";
				
		if($dropdown == 'delete')
		{
			$ids = $_POST['users'];
			//count($id);

			foreach($ids as $idd)
			{
				$sql = "DELETE FROM ".$table." WHERE id = ".$idd;
				$wpdb->query($sql);
			}
			$message = "Sites deleted successfully";
		}
	}


	if($_GET['delete_id'])
	{
		$delid = $_GET['delete_id'];

		$table = $wpdb->prefix."pcx";
	
		$sql = "DELETE FROM ".$table." WHERE id = ".$delid;
		$wpdb->query($sql);
		$message = "Site deleted successfully";
	}
?>

	<div id="message" class="updated" style="display:none;margin-bottom:20px;">	</div>
<?php if($message != '') { ?>	<div id= "m1" class="updated" style="display:block;margin:20px 0px;padding:5px 5px;"><?php echo $message; ?></div> <?php } ?>

	<div class="wrap" id="msg">
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
	<h2>Remote Sites</h2>
	<div style="padding-bottom:10px;">
	<img src="<?php echo WP_PLUGIN_URL; ?>/post-content-xmlrpc/images/add.png" height="16" width="16" style="vertical-align:-4px"/><a href="?page=pcx_add_sites&mode=add" style="text-decoration:none;"> Add Site</a> &nbsp; 
	<img src="<?php echo WP_PLUGIN_URL; ?>/post-content-xmlrpc/images/add.png" height="16" width="16" style="vertical-align:-4px"/><a href="?page=pcx_content" style="text-decoration:none;"> Add Post</a>
	</div>
    
		<form name="frm" method="POST" onsubmit="return validate()">	
			<table cellspacing="0" class="widefat" style="margin-bottom:10px;">
    			<thead>
      				<tr class="thead">
						<th class="manage-column column-cb check-column" id="cb"><input type="checkbox" name="users" id="users" ></th>
						<th class="manage-column column-username">Sr No</th>
                        <th class="manage-column column-username">Site Title</th>
						<th class="manage-column column-username">Site URL</th>
						<th class="manage-column column-username" style="text-align:center">Username</th>
						<th class="manage-column column-username">Action</th>
					</tr>
    			</thead>
    			
    			<tfoot>
					<tr class="thead">
      					<th style="" class="manage-column column-cb check-column" id="cb"><input type="checkbox" name="users" id="users"></th>
						<th class="manage-column column-username">Sr No</th>
                        <th class="manage-column column-username">Site Title</th>
						<th class="manage-column column-username">Site URL</th>
						<th class="manage-column column-username" style="text-align:center">Username</th>
						<th class="manage-column column-username">Action</th>
					</tr>
				</tfoot>
				
				<tbody class="list:user user-list" id="users">
			<?php 
					global $wpdb;	
					$table= $wpdb->prefix."pcx";
					$results = $wpdb->get_results("SELECT id FROM ".$table);
					$c=1;
					$total= count($results);
				
					$tbl_name=$table;		//your table name
					// How many adjacent pages should be shown on each side?
					$adjacents = 3;
					
					/* 
					First get total number of rows in data table. 
					If you have a WHERE clause in your query, make sure you mirror it here.
					*/
					$total_pages = $total;
				
					/* Setup vars for query. */
					$targetpage = "?page=pcx_add_sites"; 				//your file name  (the name of this file)
					$limit = 10; 								//how many items to show per page
					$page = $_GET['page_new'];
					if($page) 
						$start = ($page - 1) * $limit; 			//first item to display on this page
					else
						$start = 0;								//if no page var is given, set start to 0
				
					/* Get data. */
					$sql = "SELECT id, sitetitle, siteurl, username, password FROM ".$table." LIMIT ".$start.", ". $limit;
				
					$result_img = $wpdb->get_results($sql);
					$row_count = count($result_img);
				
					/* Setup page vars for display. */
					if ($page == 0) $page = 1;					//if no page var is given, default to 1.
					$prev = $page - 1;							//previous page is page - 1
					$next = $page + 1;							//next page is page + 1
					$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
					$lpm1 = $lastpage - 1;						//last page minus 1
				
					/* 
						Now we apply our rules and draw the pagination object. 
						We're actually saving the code to a variable in case we want to draw it more than once.
					*/
					$pagination = "";
					if($lastpage > 1)
					{	
						$pagination .= "<div class=\"pagination\">";
						//previous button
						if ($page > 1)
						{ 
							$pagination.= "<a href=\"$targetpage&page_new=$prev\">« previous</a>";
						
						}
						else
							$pagination.= "<span class=\"disabled\">« previous</span>";	
					
						//pages	
						if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
						{	;
							for ($counter = 1; $counter <= $lastpage; $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>";
								else
									$pagination.= "<a href=\"$targetpage&page_new=$counter\">$counter</a>";					
							}
						}
						elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
						{
							//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 2))		
						{
							for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>";
								else
									$pagination.= "<a href=\"$targetpage?page_new=$counter\">$counter</a>";					
							}
							$pagination.= "...";
							$pagination.= "<a href=\"$targetpage&page_new=$lpm1\">$lpm1</a>";
							$pagination.= "<a href=\"$targetpage&page_new=$lastpage\">$lastpage</a>";		
						}
						//in middle; hide some front and some back
						elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
						{
							$pagination.= "<a href=\"$targetpage&page_new=1\">1</a>";
							$pagination.= "<a href=\"$targetpage&page_new=2\">2</a>";
							$pagination.= "...";
							for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>";
								else
									$pagination.= "<a href=\"$targetpage?page_new=$counter\">$counter</a>";					
							}
							$pagination.= "...";
							$pagination.= "<a href=\"$targetpage&page_new=$lpm1\">$lpm1</a>";
							$pagination.= "<a href=\"$targetpage&page_new=$lastpage\">$lastpage</a>";		
						}
						//close to end; only hide early pages
						else
						{
							$pagination.= "<a href=\"$targetpage&page_new=1\">1</a>";
							$pagination.= "<a href=\"$targetpage&page_new=2\">2</a>";
							$pagination.= "...";
							for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>";
								else
									$pagination.= "<a href=\"$targetpage&page_new=$counter\">$counter</a>";					
							}
						}
					}
					
					//next button
					if ($page < $counter - 1) 
						$pagination.= "<a href=\"$targetpage&page_new=$next\">next »</a>";
					else
						$pagination.= "<span class=\"disabled\">next »</span>";
					$pagination.= "</div>\n";		
				}
	
				$cnt=1;
				if($_GET['page_new'])
				{
					$page = $_GET['page_new'];
					$cnt = ($limit*($page-1))+$cnt;
				}	
				else
				{
					$cnt=1;
				}	
			
		if(!empty($result_img))	
		{
			foreach($result_img as $result)
			{ ?>	
	
			<tr class="alternate" id="user-367">
				
				<th class="check-column" scope="row"><input type="checkbox"  class="administrator" id="users[]" name="users[]" value="<?php echo $result->id ?>"></th>
				<td class="name column-name"><?php echo $cnt++; ?></td>
                <td class="name column-name" >
					<?php echo $result->sitetitle ?>
				</td>
				<td class="name column-name" >
					<?php echo $result->siteurl ?>
				</td>
				<td class="name column-name" style="text-align:center !important">
					<?php echo $result->username; ?>
				</td>
				<td class="name column-name">
					<a href="?page=pcx_add_sites&mode=add&id=<?php echo $result->id; ?>">Edit | </a>
					<a href="?page=pcx_add_sites&delete_id=<?php echo $result->id; ?>" onclick="return display_confirm();">Delete</a>
				</td>
				
			
				
			</tr>
			<?php
				}
		} else {
			?>
				<tr><td colspan="7" align="center"> <?php echo " No records added "; ?></td></tr>	
		<?php } ?>
			</tbody>
			</table>
		<div align="center"><?php  echo $pagination; ?></div>
		<div align="left" style="padding-top:12px">
			<select class="drop" name="dropdown" id="act">
					<option value="0">-Select-</option>
					<option value="delete">Delete</option>					
			</select>
			<input class="button-secondary" type="submit" name="go" value="Apply" > <br />	
		</div>
		</form>	
	</div>
<?php
}
?>