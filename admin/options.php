<?php

// SVN file version:
// $Id$

if(!isset($_SESSION["pixelpost_admin"]) || $cfgrow['password'] != $_SESSION["pixelpost_admin"] || $_GET["_SESSION"]["pixelpost_admin"] == $_SESSION["pixelpost_admin"] || $_POST["_SESSION"]["pixelpost_admin"] == $_SESSION["pixelpost_admin"] || $_COOKIE["_SESSION"]["pixelpost_admin"] == $_SESSION["pixelpost_admin"]) {
	die ("Try another day!!");
}

// create banlist table if it does not exist
create_banlist();

// view = options

if($_GET['view'] == "options") {
// switch ($_GET['optaction']) {

//=========== START PAGE ONE: GENERAL ===========
if ($_GET['optaction']=='updateall')
{
	if($_GET['optionsview']=='' OR $_GET['optionsview']=='general')
	{
		//case "updatetitle":
		//case "updatesubtitle":
		//case "updateurl":
		if(!get_magic_quotes_gpc())
		{
			// we need to escape the string before saving it to the db
			$update = sql_query(
				"UPDATE ".$pixelpost_db_prefix."config
				SET
					sitetitle='".addslashes($_POST['new_site_title'])."',
					subtitle='".addslashes($_POST['new_sub_title'])."',
					siteurl='".addslashes($_POST['new_site_url'])."'");
		}
		else
		{
			$update = sql_query(
				"UPDATE ".$pixelpost_db_prefix."config
				SET
					sitetitle='".($_POST['new_site_title'])."',
					subtitle='".($_POST['new_sub_title'])."',
					siteurl='".($_POST['new_site_url'])."'");
		}

		//case "updateadmin": // admin user+password
		if ($_POST['newadminpass']!= '')
		{
			if ($_POST['newadminpass_re'] == $_POST['newadminpass'])
			{
				$new_pass = md5($_POST['newadminpass']);
				$update = sql_query(
					"UPDATE ".$pixelpost_db_prefix."config
					SET
						admin='".$_POST['new_admin_user']."',
						password='$new_pass'");

				echo "<div class='content confirm'>$admin_lang_optn_pass_chngd_txt</div>";
				unset($_SESSION["pixelpost_admin"]);
			 	setcookie( "pp_user", "", time()-36000);
				setcookie( "pp_password", "", time()-36000);
			}
			elseif ( $_POST['newadminpass_re']!='')
			{
				echo "<div class='content confirm'>$admin_lang_optn_pass_notchngd_txt</div>";
			}
		}
		//break;

		//case "updatelang":
		if ($_POST['new_lang']!=$_POST['alt_lang']){
			$update = sql_query(
				"UPDATE ".$pixelpost_db_prefix."config
				SET
					langfile='".$_POST['new_lang']."',
					altlangfile='".$_POST['alt_lang']."'");

			$lang_error=0;
		} else {
			$lang_error=1;
		}
		//break;

		//case "updatecommentemail":
		//case "updatehtmlemailnote":
		//case "updateallowcomments":
		//case "updatetimezone":
		//case "updatethumbnailpath": // imagepath
		//case "updateimagepath": // imagepath
		//case "updateemail":
		//case "updateadminlang"
			$update = sql_query(
				"UPDATE ".$pixelpost_db_prefix."config
				SET
					commentemail='".$_POST['new_commentemail']."',
					htmlemailnote='".$_POST['new_htmlemailnote']."',
					global_comments='".$_POST['global_comments']."',
					timezone='".$_POST['timezone']."',
					thumbnailpath='".$_POST['new_thumbnail_path']."',
					imagepath='".$_POST['new_image_path']."',
					email='".$_POST['new_email']."',
					admin_langfile='".$_POST['new_admin_lang']."'");

		// markdown
		// exif
		// book visitors
		// time stamps
			$upquery = sql_query(
				"UPDATE ".$pixelpost_db_prefix."config
				SET
					markdown='".$_POST['markdown']."',
					exif='".$_POST['exif']."',
					visitorbooking='".$_POST['visitorbooking']."',
					timestamp='".$_POST['timestamp']."'");

		// RSS settings
		if(!get_magic_quotes_gpc())
		{
			// we need to escape the string before saving it to the db
			$upquery = sql_query(
				"UPDATE ".$pixelpost_db_prefix."config
				SET
					rsstype='".$_POST['rsstype']."',
					feed_discovery='".$_POST['feed_discovery']."',
					feeditems='".(int) $_POST['feeditems']."',
					feed_title='".addslashes($_POST['feed_title'])."',
					feed_description='".addslashes($_POST['feed_description'])."',
					feed_copyright='".addslashes($_POST['feed_copyright'])."',
					allow_comment_feed='".$_POST['allow_comment_feed']."'");
		}
		else
		{
			$upquery = sql_query(
				"UPDATE ".$pixelpost_db_prefix."config
				SET
					rsstype='".$_POST['rsstype']."',
					feed_discovery='".$_POST['feed_discovery']."',
					feeditems='".(int) $_POST['feeditems']."',
					feed_title='".$_POST['feed_title']."',
					feed_description='".$_POST['feed_description']."',
					feed_copyright='".$_POST['feed_copyright']."',
					allow_comment_feed='".$_POST['allow_comment_feed']."'");
		}
		
		// external feed
		// do not bother to update unless the external feed option is selected and not null
		if($_POST['feed_discovery'] == 'E' &&  $_POST['feed_external'] != '')
		{
			if(!get_magic_quotes_gpc())
			{
				// we need to escape the string before saving it to the db
				$upquery = sql_query("UPDATE ".$pixelpost_db_prefix."config SET feed_external='".addslashes($_POST['feed_external'])."', feed_external_type='".$_POST['feed_external_type']."'");
			}
			else
			{
				$upquery = sql_query("UPDATE ".$pixelpost_db_prefix."config SET feed_external='".$_POST['feed_external']."', feed_external_type='".$_POST['feed_external_type']."'");
			}
		}
		
		// displayorder
			$upquery = sql_query("UPDATE ".$pixelpost_db_prefix."config SET display_order='".$_POST['display_order']."'");
		
		// Refresh the settings
		$cfgrow = sql_array("SELECT * FROM ".$pixelpost_db_prefix."config");

	}// end frist page
} //end update all

//=========== END OF PAGE ONE: GENERAL ===========
switch ($_GET['optaction'])
{

//=========== START PAGE TWO: TEMPLATE ===========
	case "updatetemplate":
		$update = sql_query("UPDATE ".$pixelpost_db_prefix."config SET template='".$_POST['new_template']."'");
	break;


	case "updatedateformat":
		$update = sql_query("UPDATE ".$pixelpost_db_prefix."config SET dateformat='".$_POST['new_dateformat']."'");
	break;

	case "updatecatformat":
		if ($_POST['catformat']=='') break;

		if ($_POST['catformat']!='custom')
		{
			// selected from the drop box
			$startcatformat = '';
			$endcatformat = $_POST['catformat'];
			if ($_POST['catformat'] == '[')
			{
						$startcatformat = '[';
						$endcatformat = ']';
			} // end if '['
		} // end if custom
		else
		{
			// custom fromat
			$startcatformat = $_POST['startcatformat'];
			$endcatformat = $_POST['endcatformat'];
		}

		$upquery = sql_query("UPDATE ".$pixelpost_db_prefix."config SET catgluestart='" .$startcatformat ."', catglueend='" .$endcatformat ."'");
	break;

	case "updatecalendar":
		$update = sql_query("UPDATE ".$pixelpost_db_prefix."config SET calendar='".$_POST['cal']."'");
	break;
//=========== END OF PAGE TWO: TEMPLATE ===========


//=========== START PAGE THREE: THUMBNAIL ===========
	case "updatethumbrow":
		$update = sql_query("UPDATE ".$pixelpost_db_prefix."config SET thumbnumber='".$_POST['thumbnumber']."'");
	break;

	case "updatecrop":
		$update = sql_query("UPDATE ".$pixelpost_db_prefix."config SET crop='".$_POST['new_crop']."'");
	break;

	case "updatethumbsize":
		$upquery = sql_query("UPDATE ".$pixelpost_db_prefix."config SET thumbwidth='".$_POST['thumbwidth']."', thumbheight='".$_POST['thumbheight']."'");
	break;

	case "updatethumbnails":
		if(function_exists('gd_info'))
		{
			$thumbnail_counter = 0;
			//$dir = "../images"; // images folder
			$dir = rtrim($cfgrow['imagepath'],"/");
			if($handle = opendir($dir))
			{
				while (false !== ($file = readdir($handle)))
				{
					if(is_file($dir."/".$file))
					{
						$thumbnail = createthumbnail($file);
						$thumbnail_counter++;
					} // if
				} // while

				closedir($handle);

				echo "<div class='jcaption'>$admin_lang_optn_thumb_updated</div><div class='content confirm'>$admin_lang_done $thumbnail_counter $admin_lang_optn_updated </div><p />";
			} // if handle
		} // if function exists
	break;

	case "updatecompression":
		$update = sql_query("UPDATE ".$pixelpost_db_prefix."config SET compression='".$_POST['new_compression']."'");
	break;
	case "updatethumbsharpening":
		$update = sql_query("UPDATE ".$pixelpost_db_prefix."config SET thumb_sharpening='".$_POST['new_thumb_sharpening']."'");
	break;
//=========== END OF PAGE THREE: THUMBNAIL ===========

} // end of switch:


if($_GET['optaction'] != "")
{
	if ($lang_error==0){
		echo "<div class='jcaption'>$admin_lang_optn_upd_done</div><div class='content confirm'>$admin_lang_done <a href='$PHP_SELF?view=options'>$admin_lang_reload";
		if ($_POST['token_time'] < 1 && $_GET['optionsview'] == 'antispam') {
			// token time < 1 minute is not allowed. Correct it and show error mesage.
			$_POST['token_time']=1;
			echo "<br /><br />".$admin_lang_optn_token_error;
		}
		echo "</a></div><p />\n";
	} else {
		echo "<div class='jcaption'>$admin_lang_optn_upd_error</div><div class='content'><font color=\"red\">$admin_lang_optn_upd_lang_error</font></div><p />\n";
	}
	
}

echo "<div id='caption'>$admin_lang_options</div>\n<div id='submenu'>";

if (!isset($_GET['optionsview']) || $_GET['optionsview']=='general')	$submenucssclass = 'selectedsubmenu';

echo "<a href='index.php?view=options&amp;optionsview=general' class='".$submenucssclass."'>$admin_lang_optn_general</a>\n";
			$submenucssclass = 'notselected';

if (isset($_GET['optionsview']) && $_GET['optionsview']=='template')	$submenucssclass = 'selectedsubmenu';

echo "|<a href='index.php?view=options&amp;optionsview=template' class='".$submenucssclass."'>$admin_lang_optn_template</a>\n";
			$submenucssclass = 'notselected';


if (isset($_GET['optionsview']) && $_GET['optionsview']=='thumb')	$submenucssclass = 'selectedsubmenu';

echo "|<a href='?view=options&amp;optionsview=thumb' class='".$submenucssclass."'>$admin_lang_optn_thumbnails</a>\n";
			$submenucssclass = 'notselected';


if (isset($_GET['optionsview']) && $_GET['optionsview']=='antispam')	$submenucssclass = 'selectedsubmenu';

echo "|<a href='?view=options&amp;optionsview=antispam' class='".$submenucssclass."'>$admin_lang_spam</a>\n";

echo_addon_admin_menus($addon_admin_functions,"options");
echo "</div>\n";

// get the config row again after updates
if($cfgquery = mysql_query("select * from ".$pixelpost_db_prefix."config"))	$cfgrow = mysql_fetch_assoc($cfgquery);

eval_addon_admin_workspace_menu("options","options");

	//showoptions();
// functions for view = options
//function showoptions(){
	//global $cfgrow;

if ($_GET['optionsview']=='general' OR $_GET['optionsview']=='')
{
		$decoded_pass = "";
		$_POST['newadminpass'] ='';
		$pixelpost_site_title = $cfgrow['sitetitle'];
		$pixelpost_site_title = pullout($cfgrow['sitetitle']);
		$pixelpost_site_title = htmlspecialchars($pixelpost_site_title,ENT_QUOTES);
		
		$pixelpost_sub_title = $cfgrow['subtitle'];
		$pixelpost_sub_title = pullout($cfgrow['subtitle']);
		$pixelpost_sub_title = htmlspecialchars($pixelpost_sub_title,ENT_QUOTES);
		echo "
			<form method='post' action='$PHP_SELF?view=options&amp;optaction=updateall&optionsview=general' accept-charset='UTF-8'>
			<div class='jcaption'>
			$admin_lang_optn_title_url
			</div>

			<div class='content'>
			$admin_lang_optn_title_url_text<p />
			$admin_lang_optn_title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='new_site_title' value='".$pixelpost_site_title."' class='input' style='width:300px;' />
			<p />
			
			$admin_lang_optn_sub_title&nbsp;&nbsp;<input type='text' name='new_sub_title' value='".$pixelpost_sub_title."' class='input' style='width:300px;' />
			<p />

		$admin_lang_optn_url&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='new_site_url' value='".$cfgrow['siteurl']."' class='input' style='width:300px;' />
		<p />
		$admin_lang_optn_tip
		</div>

		<div class='jcaption'>
		$admin_lang_optn_usr_pss
		</div>
		<div class='content'>
		$admin_lang_optn_usr_pss_txt <p />
		$admin_lang_optn_usr <input type='text' name='new_admin_user' value='".$cfgrow['admin']."' />

		$admin_lang_optn_pss <input type='password' name='newadminpass' value='$decoded_pass' />
		$admin_lang_optn_pss_re <input type='password' name='newadminpass_re' value=''  />
		<input type='hidden' name='passchanged' value='no' />
  	</div>

		<div class='jcaption'>
		$admin_lang_optn_lang_file
		</div>

		<div class='content'>
		$admin_lang_optn_lang<br/>
		<select name='new_lang'>
		<option value='".$cfgrow['langfile']."'>".$cfgrow['langfile']."</option>
		";
		// go through template folder
		$dir = "../language";

		if($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
					if(is_file('../language/'.$file) && ($file != "index.html")) {
					$file = ereg_replace("lang-","",$file);
					$file = ereg_replace(".php","",$file);
		// check that admin-language-files are not listed
					$admin_pre = substr("$file",0,6);
					if ($admin_pre != "admin-"){
						if ($file !== $cfgrow['langfile']) {
							echo "<option value='$file'>$file</option>";}
					}
				}
			}
			closedir($handle);
			}
		echo "</select><p />";
		// Alternative language settings
		echo "$admin_lang_optn_alt_lang<br />
		<select name='alt_lang'>";
		if ($cfgrow['altlangfile'] != 'Off')
		{
			echo " <option value='".$cfgrow['altlangfile']."'>".$cfgrow['altlangfile']."</option>
			<option value='Off'> -=$admin_lang_optn_alt_lang_dis=-</option>";
		} else {
			echo "<option value='Off'>".ucfirst($admin_lang_optn_alt_lang_no)."</option>";
		}
		// go through template folder
		$dir = "../language";
		if($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
					if(is_file('../language/'.$file) && ($file != "index.html")) {
					$file = ereg_replace("lang-","",$file);
					$file = ereg_replace(".php","",$file);
			// check that admin-language-files are not listed
					$admin_pre = substr("$file",0,6);
					if ($admin_pre != "admin-"){
						if ($file !== $cfgrow['altlangfile']) {
						echo "<option value='$file'>$file</option>";}
						}
					}
				}
			closedir($handle);
			}
		echo "
			</select></div>
			
		<div class='jcaption'>
		$admin_lang_optn_lang_file_admin
		</div>

		<div class='content'>
		<select name='new_admin_lang'>";
		if (!isset($cfgrow['admin_langfile'])) $cfgrow['admin_langfile'] = $cfgrow['langfile'];
		echo "<option value='".$cfgrow['admin_langfile']."'>".$cfgrow['admin_langfile']."</option>
		";
		// go through template folder
		$dir = "../language";

		if($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				$admin_pre = substr("$file",0,6);
				// only admin-language-files are listed
				if(is_file('../language/'.$file) && ($file != "index.html") && $admin_pre == "admin-") {
					$file = ereg_replace("admin-lang-","",$file);
					$file = ereg_replace(".php","",$file);
					if ($file !== $cfgrow['admin_langfile']) {
						echo "<option value='$file'>$file</option>";
					}
				}
			}
			closedir($handle);
		}
			
		echo "</select></div>
		
		
		<div class='jcaption'>
		$admin_lang_optn_email
		</div>

		<div class='content'>
		<input type='text' name='new_email' value='".$cfgrow['email']."' />
		<br />
		$admin_lang_optn_fillit
		</div>


		<div class='jcaption'>
		$admin_lang_optn_img_path
		</div>

		<div class='content'>
		<input type='text' name='new_image_path' value='".$cfgrow['imagepath']."' style='width:300px;' /> <br /><br />
		<input type='text' name='new_thumbnail_path' value='".$cfgrow['thumbnailpath']."' style='width:300px;' /> <br />
		<br />";
		echo str_replace("http://www.pixelpost.org/", "../images/ or ../thumbnails/", $admin_lang_optn_tip);
		echo"</div>


		<div class='jcaption'>
		$admin_lang_optn_tz
		</div>
		<div class='content'>
		$admin_lang_optn_tz_txt <br />
		<select name='timezone'>";
		if ($cfgrow['timezone']=='0')
		echo "<option selected='selected' value='0'>GMT</option>";
		else {
			$timezonevalue = $cfgrow['timezone'];
			if ($timezonevalue >0) $timezonevalue = '+ ' .abs($timezonevalue);
				else $timezonevalue = '- ' .abs($timezonevalue);
		echo "<option selected='selected' value='".$cfgrow['timezone']."'>GMT ".$timezonevalue." hours</option>";
			}
		echo "
			<option value='-12'>GMT - 12 Hours</option><option value='-11'>GMT - 11 Hours</option><option value='-10'>GMT - 10 Hours</option><option value='-9'>GMT - 9 Hours</option><option value='-8'>GMT - 8 Hours</option><option value='-7'>GMT - 7 Hours</option><option value='-6'>GMT - 6 Hours</option><option value='-5'>GMT - 5 Hours</option><option value='-4'>GMT - 4 Hours</option><option value='-3.5'>GMT - 3.5 Hours</option><option value='-3'>GMT - 3 Hours</option><option value='-2'>GMT - 2 Hours</option><option value='-1'>GMT - 1 Hours</option><option value='0' >GMT</option><option value='1'>GMT + 1 Hour</option><option value='2'>GMT + 2 Hours</option><option value='3'>GMT + 3 Hours</option><option value='3.5'>GMT + 3.5 Hours</option><option value='4'>GMT + 4 Hours</option><option value='4.5'>GMT + 4.5 Hours</option><option value='5'>GMT + 5 Hours</option><option value='5.5'>GMT + 5.5 Hours</option><option value='6'>GMT + 6 Hours</option><option value='6.5'>GMT + 6.5 Hours</option><option value='7'>GMT + 7 Hours</option><option value='8'>GMT + 8 Hours</option><option value='9'>GMT + 9 Hours</option><option value='9.5'>GMT + 9.5 Hours</option><option value='10'>GMT + 10 Hours</option><option value='11'>GMT + 11 Hours</option><option value='12'>GMT + 12 Hours</option><option value='13'>GMT + 13 Hours</option>
		</select>
		$admin_lang_optn_gmt<p />
		</div>
		<!-- Allow commenting on pictures -->
		<div class='jcaption'>$admin_lang_optn_comment_setting</div>
    <div class='content'>$admin_lang_optn_cmnt_mod_txt
    		<select name=\"global_comments\">";
    		if ($cfgrow["global_comments"] =='A')
    		{
 					echo "<option selected=\"selected\" value=\"A\">$admin_lang_optn_cmnt_mod_allowed</option><option value=\"M\">$admin_lang_optn_cmnt_mod_moderation</option><option value=\"F\">$admin_lang_optn_cmnt_mod_forbidden</option>";
 				}
 				elseif ($cfgrow["global_comments"] =='M')
 				{
 					echo "<option value=\"A\">$admin_lang_optn_cmnt_mod_allowed</option><option  selected=\"selected\" value=\"M\">$admin_lang_optn_cmnt_mod_moderation</option><option value=\"F\">$admin_lang_optn_cmnt_mod_forbidden</option>";
 				}
 				else
 				{
					echo "<option value=\"A\">$admin_lang_optn_cmnt_mod_allowed</option><option value=\"M\">$admin_lang_optn_cmnt_mod_moderation</option><option selected=\"selected\" value=\"F\">$admin_lang_optn_cmnt_mod_forbidden</option>";
 				}
		echo"</select></div>
		<div class='jcaption'>
		$admin_lang_optn_sendemail
		</div>
		<div class='content'>
										";
				if ($cfgrow['commentemail']=='yes')
					$toecho = $admin_lang_optn_yes;
				else
					$toecho = $admin_lang_optn_no;

				if ($cfgrow['commentemail']=='yes')
					$optnecho = $admin_lang_optn_no;
				else
					$optnecho = $admin_lang_optn_yes;

				if ($cfgrow['commentemail']=='yes')
					$optnval = 'no';
				else
					$optnval = 'yes';
				echo "
		$admin_lang_optn_sendemail_txt
		<select name='new_commentemail'>
		<option value='".$cfgrow['commentemail']."'>".$toecho."</option>
		<option value='$optnval'>$optnecho</option>
		</select>
										";
				if ($cfgrow['htmlemailnote']=='yes')
					$toecho = $admin_lang_optn_yes;
				else
					$toecho = $admin_lang_optn_no;

				if ($cfgrow['htmlemailnote']=='yes')
					$optnecho = $admin_lang_optn_no;
				else
					$optnecho = $admin_lang_optn_yes;

				if ($cfgrow['htmlemailnote']=='yes')
					$optnval = 'no';
				else
					$optnval = 'yes';
				echo "
		$admin_lang_optn_sendemail_html_txt
		<select name='new_htmlemailnote'>
		<option value='".$cfgrow['htmlemailnote']."'>".$toecho."</option>
		<option value='$optnval'>$optnecho</option>
		</select>
		</div>

		<!-- comment moderation
		<div class='jcaption'>
		$admin_lang_optn_comment_moderation
		</div> -->

		<!-- Timestamp -->
		<div class='jcaption'>
		$admin_lang_optn_timestamps_title
		</div>

		<div class='content'>
				";
		if ($cfgrow['timestamp']=='yes')
			$toecho = $admin_lang_optn_yes;
		else
			$toecho = $admin_lang_optn_no;

		if ($cfgrow['timestamp']=='yes')
			$optnecho = $admin_lang_optn_no;
		else
			$optnecho = $admin_lang_optn_yes;

		if ($cfgrow['timestamp']=='yes')
			$optnval = 'no';
		else
			$optnval = 'yes';

		echo "
		$admin_lang_optn_timestamps_desc
		<select name='timestamp'><option value='".$cfgrow['timestamp']."'>".$toecho."</option>
		<option value='$optnval'>$optnecho</option>
		</select>

		</div>

		<!-- Visitor Booking -->
				<div class='jcaption'>
				$admin_lang_optn_visitorbooking_title
				</div>

				<div class='content'>
						";
				if ($cfgrow['visitorbooking']=='yes')
					$toecho = $admin_lang_optn_yes;
				else
					$toecho = $admin_lang_optn_no;

				if ($cfgrow['visitorbooking']=='yes')
					$optnecho = $admin_lang_optn_no;
				else
					$optnecho = $admin_lang_optn_yes;

				if ($cfgrow['visitorbooking']=='yes')
					$optnval = 'no';
				else
					$optnval = 'yes';

				echo "
				$admin_lang_optn_visitorbooking_desc
				<select name='visitorbooking'><option value='".$cfgrow['visitorbooking']."'>".$toecho."</option>
				<option value='$optnval'>$optnecho</option>
				</select>

				</div>

		<!-- Markdown -->
				<div class='jcaption'>
				$admin_lang_optn_markdown
				</div>

				<div class='content'>
						";
				if ($cfgrow['markdown']=='T')
					$toecho = $admin_lang_optn_yes;
				else
					$toecho = $admin_lang_optn_no;

				if ($cfgrow['markdown']=='T')
					$optnecho = $admin_lang_optn_no;
				else
					$optnecho = $admin_lang_optn_yes;

				if ($cfgrow['markdown']=='T')
					$optnval = 'F';
				else
					$optnval = 'T';

				echo "
				$admin_lang_optn_markdown_desc
				<select name='markdown'><option value='".$cfgrow['markdown']."'>".$toecho."</option>
				<option value='$optnval'>$optnecho</option>
				</select>

				</div>
				<div class='jcaption'>
				$admin_lang_optn_exif
				</div>

				<div class='content'>
						";
				if ($cfgrow['exif']=='T')
					$toecho = $admin_lang_optn_yes;
				else
					$toecho = $admin_lang_optn_no;

				if ($cfgrow['exif']=='T')
					$optnecho = $admin_lang_optn_no;
				else
					$optnecho = $admin_lang_optn_yes;

				if ($cfgrow['exif']=='T')
					$optnval = 'F';
				else
					$optnval = 'T';
				
				echo "
				$admin_lang_optn_exif_desc
				<select name='exif'><option value='".$cfgrow['exif']."'>".$toecho."</option>
				<option value='$optnval'>$optnecho</option>
				</select>

				</div>";
				
				$feed_title = pullout($cfgrow['feed_title']);
				$feed_title = htmlspecialchars($feed_title,ENT_QUOTES);
				
				$feed_description = pullout($cfgrow['feed_description']);
				$feed_description = htmlspecialchars($feed_description,ENT_QUOTES);
				
				$feed_copyright = pullout($cfgrow['feed_copyright']);
				$feed_copyright = htmlspecialchars($feed_copyright,ENT_QUOTES);
				
				$feed_external = pullout($cfgrow['feed_external']);
				
				echo "
				<!-- RSS feed options -->
				<div class='jcaption'>$admin_lang_optn_rss_setting</div>
    			<div class='content'>
    			$admin_lang_optn_rss_title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"feed_title\" value=\"".$feed_title."\" style=\"width:300px;\" />
    			<br /><br />
    			$admin_lang_optn_rss_desc:&nbsp;&nbsp;<input type=\"text\" name=\"feed_description\" value=\"".$feed_description."\" style=\"width:300px;\" />
    			<br /><br />
    			$admin_lang_optn_rss_copyright:&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"feed_copyright\" value=\"".$feed_copyright."\" style=\"width:300px;\" />
    			<br /><br />
    			$admin_lang_optn_rss_discovery:
    			<select name=\"feed_discovery\" onchange=\"if (this.selectedIndex=='3') {flip('external_feed_discovery')};return false;\" >";
    			if($cfgrow['feed_discovery'] == 'RA')
    			{
 					echo "<option selected=\"selected\" value=\"RA\">".$admin_lang_optn_rss_opt_both."</option><option value=\"R\">".$admin_lang_optn_rss_opt_rss."</option><option value=\"A\">".$admin_lang_optn_rss_opt_atom."</option><option value=\"E\">".$admin_lang_optn_rss_opt_ext."</option><option value=\"N\">".$admin_lang_optn_rss_opt_none."</option>";
 				}
 				elseif($cfgrow['feed_discovery'] == 'R')
 				{
 					echo "<option value=\"RA\">".$admin_lang_optn_rss_opt_both."</option><option selected=\"selected\" value=\"R\">".$admin_lang_optn_rss_opt_rss."</option><option value=\"A\">".$admin_lang_optn_rss_opt_atom."</option><option value=\"E\">".$admin_lang_optn_rss_opt_ext."</option><option value=\"N\">".$admin_lang_optn_rss_opt_none."</option>";
 				}
 				elseif($cfgrow['feed_discovery'] == 'A')
 				{
 					echo "<option value=\"RA\">".$admin_lang_optn_rss_opt_both."</option><option value=\"R\">".$admin_lang_optn_rss_opt_rss."</option><option selected=\"selected\" value=\"A\">".$admin_lang_optn_rss_opt_atom."</option><option value=\"E\">".$admin_lang_optn_rss_opt_ext."</option><option value=\"N\">".$admin_lang_optn_rss_opt_none."</option>";
 				}
 				elseif($cfgrow['feed_discovery'] == 'E')
 				{
 					echo "<option value=\"RA\">".$admin_lang_optn_rss_opt_both."</option><option value=\"R\">".$admin_lang_optn_rss_opt_rss."</option><option value=\"A\">".$admin_lang_optn_rss_opt_atom."</option><option selected=\"selected\" value=\"E\">".$admin_lang_optn_rss_opt_ext."</option><option value=\"N\">".$admin_lang_optn_rss_opt_none."</option>";
 				}
 				else
 				{
 					echo "<option value=\"RA\">".$admin_lang_optn_rss_opt_both."</option><option value=\"R\">".$admin_lang_optn_rss_opt_rss."</option><option value=\"A\">".$admin_lang_optn_rss_opt_atom."</option><option value=\"E\">".$admin_lang_optn_rss_opt_ext."</option><option selected=\"selected\" value=\"N\">".$admin_lang_optn_rss_opt_none."</option>";
 				}
    			echo "
    			</select>
    			<br /><br />
    			<div id=\"external_feed_discovery\">
    			$admin_lang_optn_rss_ext_type: 
    			<select name=\"feed_external_type\">";
    			if($cfgrow['feed_external_type'] == 'ER')
    			{
 					echo "<option selected=\"selected\" value=\"ER\">".$admin_lang_optn_rss_opt_rss."</option><option class=\"EA\" value=\"EA\">".$admin_lang_optn_rss_opt_atom."</option>";
 				}
 				else
 				{
 					echo "<option class=\"ER\" value=\"ER\">".$admin_lang_optn_rss_opt_rss."</option><option selected=\"selected\" value=\"EA\">".$admin_lang_optn_rss_opt_atom."</option>";
 				}
        		echo "
    			</select>
    			<br /><br />
    			$admin_lang_optn_rss_ext:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"feed_external\" value=\"".$feed_external."\" style=\"width:300px;\" />
    			<br />
    			$admin_lang_example: http://feeds.feedburner.com/YourBurnedBlog
    			<br /><br />
    			</div>";
    			// always show the external feed option if selected
    			if($cfgrow['feed_discovery'] != 'E')
    			{
    				echo "<script type=\"text/javascript\">flip('external_feed_discovery');</script>";
    			}
    			if ($cfgrow['allow_comment_feed']=='Y'){
					$toecho = $admin_lang_optn_yes;
				}else{
					$toecho = $admin_lang_optn_no;
				}
				if ($cfgrow['allow_comment_feed']=='Y'){
					$optnecho = $admin_lang_optn_no;
				}else{
					$optnecho = $admin_lang_optn_yes;
				}
				if ($cfgrow['allow_comment_feed']=='Y'){
					$optnval = 'N';
				}else{
					$optnval = 'Y';
				}
				echo "
				$admin_lang_optn_rss_enable_comment_feed:
				<select name=\"allow_comment_feed\">
				<option value=\"".$cfgrow['allow_comment_feed']."\">".$toecho."</option>
				<option value=\"".$optnval."\">".$optnecho."</option>
				</select>
    			<br /><br />
    			$admin_lang_optn_rsstype_desc 
    			<select name=\"rsstype\">";
    			if ($cfgrow["rsstype"] =='F')
    			{
 						echo "<option selected=\"selected\" value=\"F\">$admin_lang_optn_rss_full</option><option value=\"FO\">$admin_lang_optn_rss_full_only</option><option value=\"T\">$admin_lang_optn_rss_thumbs</option><option value=\"O\">$admin_lang_optn_rss_thumbs_only</option><option value=\"N\">$admin_lang_optn_rss_text</option>";
 					}
 					elseif ($cfgrow["rsstype"] =='FO')
 					{
 						echo "<option value=\"F\">$admin_lang_optn_rss_full</option><option selected=\"selected\" value=\"FO\">$admin_lang_optn_rss_full_only</option><option value=\"T\">$admin_lang_optn_rss_thumbs</option><option value=\"O\">$admin_lang_optn_rss_thumbs_only</option><option value=\"N\">$admin_lang_optn_rss_text</option>";
 					}
 					elseif ($cfgrow["rsstype"] =='T')
 					{
 						echo "<option value=\"F\">$admin_lang_optn_rss_full</option><option value=\"FO\">$admin_lang_optn_rss_full_only</option><option selected=\"selected\" value=\"T\">$admin_lang_optn_rss_thumbs</option><option value=\"O\">$admin_lang_optn_rss_thumbs_only</option><option value=\"N\">$admin_lang_optn_rss_text</option>";
 					}
 					elseif ($cfgrow["rsstype"] =='O')
 					{
 						echo "<option value=\"F\">$admin_lang_optn_rss_full</option><option value=\"FO\">$admin_lang_optn_rss_full_only</option><option value=\"T\">$admin_lang_optn_rss_thumbs</option><option selected=\"selected\" value=\"O\">$admin_lang_optn_rss_thumbs_only</option><option value=\"N\">$admin_lang_optn_rss_text</option>";
 					}
 					else
 					{
						echo "<option value=\"F\">$admin_lang_optn_rss_full</option><option value=\"FO\">$admin_lang_optn_rss_full_only</option><option value=\"T\">$admin_lang_optn_rss_thumbs</option><option value=\"O\">$admin_lang_optn_rss_thumbs_only</option><option selected=\"selected\" value=\"N\">$admin_lang_optn_rss_text</option>";
 					}
			echo"</select><br /><br />
			$admin_lang_optn_feeditems_desc <input type='text' style=\"text-align: right;\" size=\"2\" name='feeditems' value='".$cfgrow['feeditems']."' />
			</div>
			<div class='jcaption'>
				$admin_lang_optn_img_display
				</div>

				<div class='content'>
						";
				if ($cfgrow['display_order']=='default')
					$toecho = $admin_lang_optn_img_display_default;
				else
					$toecho = $admin_lang_optn_img_display_reversed;

				if ($cfgrow['display_order']=='default')
					$optnecho = $admin_lang_optn_img_display_reversed;
				else
					$optnecho = $admin_lang_optn_img_display_default;

				if ($cfgrow['display_order']=='default')
					$optnval = 'reversed';
				else
					$optnval = 'default';
				
				echo "
				$admin_lang_optn_img_display_desc
				<select name='display_order'><option value='".$cfgrow['display_order']."'>".$toecho."</option>
				<option value='$optnval'>$optnecho</option>
				</select>

				</div>
		<div class='jcaption'>
				$admin_lang_optn_update
				</div>

		<div class='content'>
		<input type='submit' value='$admin_lang_optn_update'  />
		</div>
		</form>
		";

	} // end option / general

	if ($_GET['optionsview']=='template')
	{
	echo "

		<div class='jcaption'>
		$admin_lang_optn_switch_template
		</div>

		<div class='content'>
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatetemplate&optionsview=template' accept-charset='UTF-8'>
		<select name='new_template'>
		<option value='".$cfgrow['template']."'>".$cfgrow['template']."</option>
		";
		// go through template folder
		$dir = "../templates";
		if($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if($file != "." && $file != ".." && $file != "splash_page.html" && $file != "index.html" && $file != $cfgrow['template']) {
					echo "<option value='$file'>$file</option>";
					}
				}
			closedir($handle);
			}
		echo "
		</select>
		<input type='submit' value='$admin_lang_optn_update' />
		</form>
		</div>


	     <div class='jcaption'>
		$admin_lang_optn_dateformat
		</div>

		<div class='content'>
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatedateformat&optionsview=template' accept-charset='UTF-8'>
		$admin_lang_optn_dateformat_txt<p />
		<input type='text' name='new_dateformat' value='".$cfgrow['dateformat']."' style='width:150px;' />
		<input type='submit' value='$admin_lang_optn_update' />
		</form>
		</div>

		<div class='jcaption'>
		$admin_lang_optn_cat_link_format
		</div>

		<div class='content'>
		<form name='catformatform' method='post' action='$PHP_SELF?view=options&amp;optaction=updatecatformat&optionsview=template' accept-charset='UTF-8' >
		$admin_lang_optn_cat_link_format_txt<br />
		<select name='catformat' onchange=\"if (this.selectedIndex=='6') {flip('costumcatformat')};return false;\" >";
		if($cfgrow['catglueend'] == "")
		{
      $catglue = "Select Category Links Format";
    }
    else
    {
      $catglue = $cfgrow['catgluestart'] . "category-1" . $cfgrow['catglueend']." ".$cfgrow['catgluestart']. "category-2" . $cfgrow['catglueend']." ".$cfgrow['catgluestart'] . "etc" . $cfgrow['catglueend'];
    }
		echo"
		<option value='".$cfgrow['catglueend']."' >$catglue</option>

		<option value='['>[category-1] [category-2] [etc]</option>
		<option value='	,'>category-1, category-2, etc</option>
		<option value=' -'>category-1 - category-2 - etc</option>
		<option value=' |'>category-1 | category-2 | etc</option>
		<option value=' /'>category-1 / category-2 / etc</option>
		<option value='custom' >custom...</option>

		</select>

		<div id='costumcatformat'>
		<p />
		 $admin_lang_optn_catlinkformat_custom_start <input type='text' name='startcatformat' class='input' size='3' value='[' />
		$admin_lang_optn_catlinkformat_custom_end  <input type='text' name='endcatformat' size='3' value=']' />
		</div>
		<script type='text/javascript'>flip('costumcatformat');</script>
		<input type='submit' value='$admin_lang_optn_update' />

		</form>
		</div>


		<div class='jcaption'>
		$admin_lang_optn_calendar_type
		</div>

		<div class='content'>
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatecalendar&optionsview=template' accept-charset='UTF-8'>
		<select name='cal'>
		";
		if($cfgrow['calendar'] == "") { $nice_calendar = "No Calendar"; } else { $nice_calendar = $cfgrow['calendar']; }
		echo "
		<option value='".$cfgrow['calendar']."'>$nice_calendar</option>
		<option value='Horizontal'>Horizontal</option>
		<option value='Normal'>Normal</option>
		<option value='No Calendar'>Don't Use a Calendar</option>
		</select>
		<input type='submit' value='$admin_lang_optn_update' />
		</form>
		</div>


	";
	}
	if ($_GET['optionsview']=='thumb')
	{

	echo "

		<div class='jcaption'>
		$admin_lang_optn_thumb_row
		</div>

		<div class='content'>
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatethumbrow&optionsview=thumb' accept-charset='UTF-8'>
		$admin_lang_optn_thumb_row_txt<p />
		<input type='text' name='thumbnumber' value='".$cfgrow['thumbnumber']."' style='width:50px;' />
		<input type='submit' value='$admin_lang_optn_update' />
		</form>
		</div>

		<div class='jcaption'>
		$admin_lang_optn_crop_thumbs
		</div>

		<div class='content'>
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatecrop&optionsview=thumb' accept-charset='UTF-8'>
		$admin_lang_optn_crop_thumbs_txt<p />
		<select name='new_crop'>";
		$cropmethod = $cfgrow['crop'];
		if ($cropmethod=='12c')
			$cropmethod = '12CropImage';
		echo "
		<option value='".$cfgrow['crop']."'>".$cropmethod."</option>
		<option value='yes'>$admin_lang_optn_yes</option>
		<option value='12c'>12CropImage</option>
		<option value='no'>$admin_lang_optn_no</option>
		</select>
		<input type='submit' value='$admin_lang_optn_update' />
		</form>
		</div>

		<div class='jcaption'>
		$admin_lang_optn_thumb_size
		</div>

		<div class='content'>
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatethumbsize&optionsview=thumb' accept-charset='UTF-8'>
		$admin_lang_optn_thumb_size_txt<br />
		<input type='text' name='thumbwidth' value='".$cfgrow['thumbwidth']."' /> x
		<input type='text' name='thumbheight' value='".$cfgrow['thumbheight']."' />
		<input type='submit' value='$admin_lang_optn_thumb_size_new' />
		</form><p />

		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatethumbnails&optionsview=thumb' accept-charset='UTF-8'>
		<input type='submit' value='$admin_lang_optn_reg_thumbs_button' /><br />$admin_lang_optn_regen_thumbs_txt
		</form>
		</div>

		<div class='jcaption'>
		$admin_lang_optn_img_compression
		</div>

		<div class='content'>
		$admin_lang_optn_img_compression_txt<p />
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatecompression&optionsview=thumb' accept-charset='UTF-8'>
		<input type='text' name='new_compression' value='".$cfgrow['compression']."' style='width:50px;' />
		<input type='submit' value='$admin_lang_optn_update' />
		</form>
		</div>

		<div class='jcaption'>
		$admin_lang_optn_thumb_sharp
		</div>

		<div class='content'>
		$admin_lang_optn_thumb_sharp_txt<p />
		<form method='post' action='$PHP_SELF?view=options&amp;optaction=updatethumbsharpening&optionsview=thumb' accept-charset='UTF-8'>
		<select name='new_thumb_sharpening' value='".$cfgrow['thumb_sharpening']."' style='width:150px;'>";

	for($i = 0; $i < 5; $i++)
	{
		echo '
			<option value="' . $i . '"' . (($i==$cfgrow['thumb_sharpening']) ? ' selected' : '') . '>' . ${'admin_lang_optn_thumb_sharp'.$i} . '</option>';
	}

	echo "
		</select>
		<input type='submit' value='$admin_lang_optn_update' />
		</form>
		</div>
		";
    };


    if ($_GET['optionsview']=='antispam'){
    	if ($_GET['optaction']=='updateantispam'){
				// token
				// DSBL settings
				// SPAM Flood settings
				// Maximum URI in comment
				$upquery = sql_query(
					"UPDATE ".$pixelpost_db_prefix."config
					SET
						token='".$_POST['token']."',
						token_time='".(int) $_POST['token_time']."',
						comment_dsbl='".$_POST['comment_dsbl']."',
						comment_timebetween='".(int) $_POST['comment_timebetween']."',
						max_uri_comments='".(int) $_POST['max_uri_comment']."'
						");
				// refresh the settings
				$cfgrow = sql_array("SELECT * FROM ".$pixelpost_db_prefix."config");
			} 
    	show_anti_spam();
    	
    	echo "<form method='post' action='$PHP_SELF?view=options&amp;optionsview=antispam&amp;optaction=updateantispam&optionsview=antispam' accept-charset='UTF-8'>
    		<div class='jcaption'>
				$admin_lang_optn_token
				</div>

				<div class='content'>
						";
				if ($cfgrow['token']=='T')
					$toecho = $admin_lang_optn_yes;
				else
					$toecho = $admin_lang_optn_no;

				if ($cfgrow['token']=='T')
					$optnecho = $admin_lang_optn_no;
				else
					$optnecho = $admin_lang_optn_yes;

				if ($cfgrow['token']=='T')
					$optnval = 'F';
				else
					$optnval = 'T';

				echo "
				$admin_lang_optn_token_desc
				<select name='token'><option value='".$cfgrow['token']."'>".$toecho."</option>
				<option value='$optnval'>$optnecho</option>
				</select><br />
				$admin_lang_optn_token_time <input type='text' style=\"text-align: right;\" size=\"1\" name='token_time' value='".$cfgrow['token_time']."' />
				</div>
				
				<div class='jcaption'>
				$admin_lang_optn_dsbl_list
				</div>

				<div class='content'>
						";
				if ($cfgrow['comment_dsbl']=='T')
					$toecho = $admin_lang_optn_yes;
				else
					$toecho = $admin_lang_optn_no;

				if ($cfgrow['comment_dsbl']=='T')
					$optnecho = $admin_lang_optn_no;
				else
					$optnecho = $admin_lang_optn_yes;

				if ($cfgrow['comment_dsbl']=='T')
					$optnval = 'F';
				else
					$optnval = 'T';

				echo "
				$admin_lang_optn_dsbl_list_desc
				<select name='comment_dsbl'><option value='".$cfgrow['comment_dsbl']."'>".$toecho."</option>
				<option value='$optnval'>$optnecho</option>
				</select>
				</div>
				
				<div class='jcaption'>
				$admin_lang_optn_time_between_comments
				</div>
				<div class='content'>
				$admin_lang_optn_time_between_comments_desc <input type='text' style=\"text-align: right;\" size=\"2\" name='comment_timebetween' value='".$cfgrow['comment_timebetween']."' /> s
				</div>
				<div class='jcaption'>
				$admin_lang_optn_max_uri_comment
				</div>
				<div class='content'>
				$admin_lang_optn_max_uri_comment_desc <input type='text' style=\"text-align: right;\" size=\"2\" name='max_uri_comment' value='".$cfgrow['max_uri_comments']."' />
				</div>";
				eval_addon_admin_workspace_menu("additional_spam_measures","");
				echo "<div class='jcaption'>
				$admin_lang_optn_update
				</div>

		<div class='content'>
		<input type='submit' value='$admin_lang_optn_update'  />
		</div>
		</form>";
		echo options_refererlog_html();
		}

} // end if view options    }

//========================== functions =================================

// do show anti spam in admin >> option page
function show_anti_spam()
{
// update it if neccessary
$additional_msg = update_banlist();
$additional_msg .= moderate_past_with_list();
$additional_msg .= delete_past_with_list();
$additional_msg .= delete_from_badreferer_list();
$html = options_anti_spam_html($additional_msg);
echo $html;

}
// create options HTML code
function options_anti_spam_html($additional_msg)
{
global $pixelpost_db_prefix,$admin_lang_spam_ban,$admin_lang_spam_content,$admin_lang_spam_modlist,$admin_lang_spam_blacklist,$admin_lang_spam_reflist,$admin_lang_spam_blacklist_text,$admin_lang_spam_htaccess_text,$admin_lang_spam_check_comm,$admin_lang_spam_del_bad_comm,$admin_lang_spam_del_bad_ref,$admin_lang_spam_updateblacklist;


	$mod_list   = get_moderation_banlist();
	$black_list = get_blacklist();
	$ref_list		= get_ref_ban_list();

	$query = "SELECT acceptable_num_links FROM {$pixelpost_db_prefix}banlist LIMIT 1";
	$result = mysql_query($query) or die( mysql_error());
	if( $row = mysql_fetch_row($result))
		$acceptable_num_links = $row[0];

	// htaccess stuff
	$htaccess = create_htaccess_banlist();


	$HTML = "
		<div class='jcaption'>
		$admin_lang_spam_ban
		</div>
		<div class='content'>
		$admin_lang_spam_content
		<form method='post' action='index.php?view=options&amp;optionsview=antispam&optionsview=antispam'>\n
		<input type='hidden' name='banlistupdate' value='1' />
		<table >\n
		<tr >
		\t<td style='padding-right:5px;'>\n
				<b>$admin_lang_spam_modlist</b> <br/>
				<textarea name='moderation_list' class='banlists' style='width:200px;height:100px;' rows=\"\" cols=\"\">$mod_list</textarea>
				<br/><a href=\"index.php?view=options&amp;optionsview=antispam&amp;antispamaction=moderation\">$admin_lang_spam_check_comm</a>
		\t</td>\n

		\t<td style='padding-right:5px;'>\n
				<b>$admin_lang_spam_blacklist</b> <br/>
				<textarea name='blacklist' class='banlists' style='width:200px;height:100px;' rows=\"\" cols=\"\">$black_list</textarea>
				<br/><a href=\"index.php?view=options&amp;optionsview=antispam&amp;antispamaction=deletecmnt\">$admin_lang_spam_del_bad_comm</a>
			</td>\n


		\t<td style='padding-right:5px;'>\n
				<b>$admin_lang_spam_reflist </b> <br/>
				<textarea name='ref_ban_list' class='banlists' style='width:200px;height:100px;' rows=\"\" cols=\"\">$ref_list</textarea>
				<br/><a href='index.php?view=options&amp;optionsview=antispam&amp;antispamaction=deleterefs' >$admin_lang_spam_del_bad_ref</a>
			</td>\n


		</tr>\n
		</table >\n
		<input type='submit' value='$admin_lang_spam_updateblacklist' />\n
		</form>
		$additional_msg
		";
		if( isset( $_POST['banlistupdate'])){
			$HTML .="
			<div id='htaccess-deny' >
			$admin_lang_spam_blacklist_text
			<textarea name='htaccess-deny-list' style='width:600px;height:200px;' >$htaccess </textarea>
			</div>";
			}	else{
			$HTML .="
			<p />
			<a href=\"#\" onclick=\"flip('htaccess-deny'); return false;\"><i>$admin_lang_spam_htaccess_text</i></a><p />
			<div id=\"htaccess-deny\" >
			<script type=\"text/javascript\">flip('htaccess-deny');</script>
			$admin_lang_spam_blacklist_text
			<textarea name=\"htaccess-deny-list\" style=\"width:600px;height:200px;\" rows=\"\" cols=\"\">$htaccess</textarea>
			</div>";
			}
		$HTML .="
		</div> <!-- end of content-->
		";
	return $HTML;
}

function options_refererlog_html() {
			// refererlog
		global $admin_lang_pp_ref_log_title;
		global $pixelpost_db_prefix;
		$HTML .= "<div class=\"jcaption\">$admin_lang_pp_ref_log_title</div>
			<div class=\"content\">";

		    $referer_print = "<ul><li>&nbsp;</li>";
		    // only count referers from the last seven days
		    gmdate("Y-m-d H:i:s",time()+(3600 * $cfgrow['timezone'])); // current date+time
		    $from_date = mktime(0,0,0,gmdate("m",time()+(3600 * $cfgrow['timezone'])) ,gmdate("d",time()+(3600 * $cfgrow['timezone'])) -7,gmdate("Y",time()+(3600 * $cfgrow['timezone'])));
		    $from_date = strftime("%Y-%m-%d", $from_date);
		    $from_date = "$from_date 00:00:00";
		    $referer = "";
		    $query = mysql_query("select distinct referer from ".$pixelpost_db_prefix."visitors WHERE (referer!='') AND (datetime>'$from_date')");
		    while(list($nreferer) = mysql_fetch_row($query)) {
		       $nreferer = htmlentities($nreferer);
		  	    $referer .= "!".$nreferer;
		    	}
		    $referer = split("!",$referer);
		    $ref_biglist = "";
		    foreach($referer as $value) {
			    if($value != "") {
		   	    	$row = sql_array("select count(*) as count from ".$pixelpost_db_prefix."visitors WHERE (referer='$value') AND (datetime>'$from_date')");
		       		$refnumb = $row['count'];
			    	$ref_biglist .= "$refnumb@$value!";
		            }
			    }
		    $ref_biglist = split("!",$ref_biglist);
		    rsort($ref_biglist,SORT_NUMERIC);
		    foreach($ref_biglist as $value) {
			    list($numb,$referer) = explode("@",$value);
			    if($numb > "0") {
			    	if($numb < "10") { $numb = "0$numb"; }
			    	$referername = $referer;
				$length = strlen($referername);
				if($length > 50) { $referername = substr($referername,0,50); $referername = "$referername..."; }

		$referer_print .= "<li><a href='$referer' rel='nofollow'>$numb &nbsp;&nbsp;&nbsp; $referername</a></li>";
				}
			}
			$referer_print .= "</ul>";
			$HTML .= $referer_print;
			$HTML .= "</div><p />";
//-------------
return $HTML;
}
 ?>