<?php

function showNote($note){
	$noteSql = mysql_query("SELECT * FROM note WHERE noteID='".$note."'");
		while($row = mysql_fetch_object($noteSql)){
			$noteID = $row->noteID;
			$noteTitle = $row->noteTitle;
			$noteContent = $row->noteContent;
			$noteCategory = $row->noteCategory;
			$noteProject = $row->noteProject;
			$noteSourceExtern = $row->noteSourceExtern;
			$noteSource = $row->noteSource;
			$notePageStart = $row->pageStart;
			$notePageEnd = $row->pageEnd;
			$noteMedia = $row->noteMedia;
			$notePublic = $row->notePublic;
			echo "<div class='note'>";
			/*
				echo "<div class='cache'>";
					echo $noteID."·".$noteTitle."·".$noteContent."·".$noteCategory."·".$noteProject."·".$noteSourceExtern."·".$noteSource."·".$notePageStart."·".$notePageEnd."·".$noteMedia."·".$notePublic;
				
				echo "</div>";
			*/
				if ($noteMedia!="") {
					showMedia($noteID, $noteMedia, $noteTitle);
				}
				echo "<h3>".$noteTitle."</h3>"; //
				echo "<p class='content'>";
				

				if ($noteSource!=0){
					echo "``".makeurl(nl2br($noteContent))."''";
				} else {
					echo makeurl(nl2br($noteContent));
				}
				echo "<br>";
				showSourceCite($noteSource,$notePageStart,$notePageEnd);
				echo "</p>";
				//echo "<br>";
				//showSourceLink($noteSource,$notePageStart,$notePageEnd);

				if($noteSourceExtern!=""){
					echo "<p class='linkText'>--&gt; <a href='".$noteSourceExtern."' title='extern'>".$noteSourceExtern."</a></p>";
				}
//				echo "<br>";
					echo "<div class='set'>";
						echo "<button class='mark'>mark</button>";
					echo "</div>";
				echo "<br>";
				echo "<p class='linkText'>";
					echo linkIndex('note', 'category', $noteCategory);
					echo " &gt; ";
					echo linkIndex('note', 'project', $noteProject);
					linkIndexMN('note','tag', $noteID);
					//linkEdit('note', $noteID, $notePublic);
					if($_SERVER["QUERY_STRING"]){
						$editLink = "index.php?".$_SERVER['QUERY_STRING']."&amp;edit=".$note;
					} else {
						$editLink = "index.php?edit=".$note;
					}
					
					if($notePublic == 1){
						//echo "<a href='index.php?type=note&part=edit&id=".$note."' class='public' title='edit'>e</a>";
						echo "<a href='".$editLink."' class='edit public' title='edit' name=".$note.">e</a>";
					} else {
						//echo "<a href='index.php?type=note&part=edit&id=".$note."' class='nonpublic' title='edit'>e</a>";
						echo "<a href='".$editLink."' class='edit nonpublic' title='edit' name=".$note.">e</a>";
					}
				echo "</p>";
				echo "</div>";
		}
}

function showMedia($noteID, $noteMedia, $noteTitle){
	$mediaFile = explode(".", $noteMedia);
	$fileName = $mediaFile[0];
	$extension = $mediaFile[1];
	switch($extension){ 
		case "jpg";
		case "png";
		case "gif";
		case "jpeg";
		case "tif";
		{
			$mediaInfo = MEDIA_FOLDER."/pictures/".$noteMedia;
			
			if (@fopen($mediaInfo,"r")==true){
			
//			if (file_exists($mediaInfo)){
				$size = ceil(filesize(MEDIA_FOLDER."/pictures/".$noteMedia)/1024);
				$fileName = $mediaInfo['filename'];
				$infoSize = getimagesize(MEDIA_FOLDER."/pictures/".$noteMedia);
				// ergibt mit $infoSize[0] für breite und $infoSize[1] für höhe
				echo "<img class='staticMedia' src='".MEDIA_FOLDER."/pictures/".$noteMedia."' alt='".$noteTitle."' title='".$noteID."' />";
			} else {
				echo "<p class='warning' title='".MEDIA_FOLDER."/pictures/".$noteMedia."'>[The picture file is missing!]</p>";
			}
			break;
		}
		
		case "pdf";
		{
			$mediaInfo = MEDIA_FOLDER."/documents/".$noteMedia;
			if (file_exists($mediaInfo)){
				echo "<a href='".MEDIA_FOLDER."/documents/".$noteMedia."' title='Download ".$noteMedia." (".$size."kb)' class='warning'>Open / Download ".$noteMedia." (".$size."kb)</a><br>";
			} else {
				echo "<p class='warning'>[The pdf document is missing!]</p>";
			}
			break;
		}
		
		case "mp4";
		case "webm";
		{
			$mediaInfo = MEDIA_FOLDER."/movies/".$noteMedia;
			if (file_exists($mediaInfo)){
				echo "<video class='dynamicMedia' controls preload='auto' poster='".SITE_URL."/media/movies/".$fileName.".png'>";
					echo "<source src='".MEDIA_FOLDER."/movies/".$fileName.".mp4' type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"'>";
					echo "<source src='".MEDIA_FOLDER."/movies/".$fileName.".webm' type='video/webm; codecs=\"vp8, vorbis\"'>";
				echo "</video>";
			} else {
				echo "<p class='warning'>[The movie file is missing!]</p>";
			}
			break;
		}

		case "mp3";
		case "wav";
		{
			$mediaInfo = MEDIA_FOLDER."/sound/".$noteMedia;
			if (file_exists($mediaInfo)){
				echo "<audio class='dynamicMedia' controls preload='auto'>";
					echo "<source src='".MEDIA_FOLDER."/sound/".$fileName.".mp3' type='audio/mpeg; codecs=mp3'>";
					echo "<source src='".MEDIA_FOLDER."/sound/".$fileName.".wav' type='audio/wav; codecs=1'>";
				echo "</audio>";
			} else {
				echo "<p class='warning'>[The audio file is missing!]</p>";
			}
			break;
		}
		
		default; {
			echo "<p class='warning'>[".$extension."] is not supported in notizblogg!?</p>";
		}
		
	}
}


function showSourceCite($noteSource,$notePageStart,$notePageEnd){
	
	if($noteSource!=0){
		$sourceSql = mysql_query("SELECT sourceName FROM source WHERE sourceID='".$noteSource."'");
			while($row = mysql_fetch_object($sourceSql)){
				$noteSourceName = $row->sourceName;
			}
		if($notePageStart!=0){
			$pages=$notePageEnd-$notePageStart;
			if($pages<=0){
				$copy2tex = "\\footcite[][".$notePageStart."]{<a href='index.php?type=note&amp;part=source&amp;id=".$noteSource."' title='source'>".$noteSourceName."</a>}";
			/*} elseif($pages==1){
				$copy2tex = "\footcite[][".$notePageStart."f.]{".$noteSource."}";*/
			} else {
				$copy2tex = "\\footcite[][".$notePageStart."--".$notePageEnd."]{<a href='index.php?type=note&amp;part=source&amp;id=".$noteSource."' title='source'>".$noteSourceName."</a>}";
			}
		} else {
			$copy2tex = "\\footcite{<a href='index.php?type=note&amp;part=source&amp;id=".$noteSource."' title='source'>".$noteSourceName."</a>}";
		}
		//echo "<p class='linkText'>--&gt; <a href='index.php?sourceID=".$noteSource."' title='extern'>".$noteSource."</a></p>";
		echo $copy2tex;
	}
	
}

?>
