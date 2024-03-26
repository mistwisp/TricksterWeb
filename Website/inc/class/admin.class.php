<?php
	class Admin extends Connection
	{
		function __construct( $dbinfo )
		{
			parent::__construct( $dbinfo );
		}
		public function checkHaveAccess( $uid )
		{
			$infoAcc = $this->select( 0, "web_admin", array( "uid" ), array( "uid" => $uid ) );
			if( !empty( $infoAcc ) )
			{
				return 0;
			}
			return -1;
		}
		public function getNewsEditList()
		{
			$numerator = 0;
			$newsQuery = "SELECT nid, Title, Content, Date, Author, Thumbnail FROM web_news ORDER BY nid DESC";
			$news_result = $this->select( 2, $newsQuery );
			foreach( $news_result as $news )
			{
				$newsDate = date( "d/m/Y", strtotime( $news->Date ) );
				echo
				'
					<script type="text/javascript">
						bkLib.onDomLoaded(function() {
							new nicEditor({maxHeight : 310, fullPanel : true}).panelInstance("newsedit'.$numerator.'",{hasPanel : true});
						});
					</script>
					<div class="news-entry">
						<div class="news-info">
							<div class="news-image">
								<img src="'.$news->Thumbnail.'" alt="" />
							</div>
							<div class="news-title">
								<h2>'.$news->Title.'</h2>
								<div class="news-author">
									['.$newsDate.'] ~ '.$news->Author.'
								</div>
								<div class="news-actions">
									<button id="editNews'.$numerator.'" class="edit-button extra_size">✎ Edit</button>
									<form method="post">
										<input type="hidden" name="newsdelete-nid" value="'.$news->nid.'">
										<input type="submit" class="delete-button extra_size" value="✖ Delete"/>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div id="editNewsModal'.$numerator.'" class="modal-news">
						<div class="modal-news-content">
							<form id="editNewsForm'.$numerator.'" method="post">
								<input type="hidden" name="newsedit-nid" value="'.$news->nid.'">
								<span class="text-4">Title</span>
								<input type="text" name="newsedit-title" value="'.$news->Title.'" required>
								<span class="text-4">Thumbnail URL</span>
								<input type="text" name="newsedit-thumbnail" value="'.$news->Thumbnail.'" required>
								<span class="text-4">Content</span>
								<textarea id="newsedit'.$numerator.'" name="newsedit-content" required>'.$news->Content.'</textarea>
								<input type="submit" value="✎ EDIT NEWS" class="edit-button-modal-news edit-button"/>
							</form>
							<button class="edit-button-modal-news delete-button close'.$numerator.'">✖ CANCEL</button>
						</div>
				  	</div>
					<script>
						document.getElementById("editNews'.$numerator.'").addEventListener("click", () => { document.getElementById("editNewsModal'.$numerator.'").style.display = "flex"; });
						document.getElementsByClassName("close'.$numerator.'")[0].addEventListener("click", () => { document.getElementById("editNewsModal'.$numerator.'").style.display = "none"; });
						window.addEventListener("click", (event) => { if (event.target === document.getElementById("editNewsModal'.$numerator.'")) { document.getElementById("editNewsModal'.$numerator.'").style.display = "none"; } });
						document.getElementById("editNewsForm'.$numerator.'").addEventListener("submit", (event) => { document.getElementById("editNewsModal'.$numerator.'").style.display = "none"; });
					</script>
				';
				$numerator++;
			}
		}
		public function getSliderEditList()
		{
			$sliderQuery = "SELECT sid, Image FROM web_slider ORDER BY sid DESC";
			$slider_result = $this->select( 2, $sliderQuery );
			foreach( $slider_result as $slider )
			{
				echo
				'
					<div class="slider-entry">
						<div class="slider-info">
							<div class="slider-image">
								<img src="'.$slider->Image.'" alt="" />
							</div>
							<div class="slider-actions">
								<form method="post">
									<input type="hidden" name="slidedelete-sid" value="'.$slider->sid.'">
									<input type="submit" class="delete-button button-fill" value="✖ Delete"/>
								</form>
							</div>
						</div>
					</div>
				';
			}
		}
		public function doEditNews( $nid, $title_news, $thumbnail, $content )
		{
			$title = "Success!";
			$message = "News ID ".$nid." was edited successfully!";
			$icon = "success";
			$info = $this->update( "web_news", array( "Title" => $title_news, "Thumbnail" => $thumbnail, "Content" => $content ), array( "nid" => $nid ) );
			if( $info < 0 )
			{
				$title = "Error!";
				$message = "Failed to edit News ID ".$nid.".";
				$icon = "error";
			}
			echo 
			'
				<script>
					Swal.fire({ 
						title: "'.$title.'",
						html: "'.$message.'",
						icon: "'.$icon.'",
						confirmButtonText: "Ok",
						allowOutsideClick: false,
						allowEscapeKey: false,
						closeOnClickOutside: false
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.replace("/admin");
						}
					});
				</script>
			';
		}
		public function doAddNews( $title_news, $thumbnail, $content )
		{
			$title = "Success!";
			$message = "News was created successfully!";
			$icon = "success";
			$insertArray = array( "Title" => $title_news, "Thumbnail" => $thumbnail, "Content" => $content, "Author" => $_SESSION[ "TricksterWebLogin" ] );
			$insertResult = $this->insert( 0, "web_news", $insertArray );
			if( $insertResult != 0 )
			{
				$title = "Error!";
				$message = "Failed to create News.";
				$icon = "error";
			}
			echo 
			'
				<script>
					Swal.fire({ 
						title: "'.$title.'",
						html: "'.$message.'",
						icon: "'.$icon.'",
						confirmButtonText: "Ok",
						allowOutsideClick: false,
						allowEscapeKey: false,
						closeOnClickOutside: false
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.replace("/admin");
						}
					});
				</script>
			';
		}
		public function doDeleteNews( $nid )
		{
			$title = "Success!";
			$message = "News ID ".$nid." was deleted successfully!";
			$icon = "success";
			$deleteQuery = "DELETE FROM web_news WHERE nid = ".$nid;
			$this->select( 5, $deleteQuery );
			echo 
			'
				<script>
					Swal.fire({ 
						title: "'.$title.'",
						html: "'.$message.'",
						icon: "'.$icon.'",
						confirmButtonText: "Ok",
						allowOutsideClick: false,
						allowEscapeKey: false,
						closeOnClickOutside: false
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.replace("/admin");
						}
					});
				</script>
			';
		}
		public function doAddSlide( $image )
		{
			$title = "Success!";
			$message = "Slide was created successfully!";
			$icon = "success";
			$insertArray = array( "Image" => $image );
			$insertResult = $this->insert( 0, "web_slider", $insertArray );
			if( $insertResult != 0 )
			{
				$title = "Error!";
				$message = "Failed to add Slide.";
				$icon = "error";
			}
			echo 
			'
				<script>
					Swal.fire({ 
						title: "'.$title.'",
						html: "'.$message.'",
						icon: "'.$icon.'",
						confirmButtonText: "Ok",
						allowOutsideClick: false,
						allowEscapeKey: false,
						closeOnClickOutside: false
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.replace("/admin");
						}
					});
				</script>
			';
		}
		public function doDeleteSlider( $sid )
		{
			$title = "Success!";
			$message = "Slide ID ".$sid." was deleted successfully!";
			$icon = "success";
			$deleteQuery = "DELETE FROM web_slider WHERE sid = ".$sid;
			$this->select( 5, $deleteQuery );
			echo 
			'
				<script>
					Swal.fire({ 
						title: "'.$title.'",
						html: "'.$message.'",
						icon: "'.$icon.'",
						confirmButtonText: "Ok",
						allowOutsideClick: false,
						allowEscapeKey: false,
						closeOnClickOutside: false
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.replace("/admin");
						}
					});
				</script>
			';
		}
		public function doPostCheck()
		{
			$editNewsArray = array( "newsedit-nid", "newsedit-title", "newsedit-thumbnail", "newsedit-content" );
			$addNewsArray = array( "newsaddtitle", "newsaddthumbnail", "newsaddcontent" );
			$deleteNewsArray = array( "newsdelete-nid" );
			$addSlideArray = array( "slideaddimage" );
			$deleteSliderArray = array( "slidedelete-sid" );
			if( $this->isSetPostArray( $editNewsArray ) == 0 )
			{
				$nid = $this->getPostFromArray( $editNewsArray, 0 );
				$title_news = $this->getPostFromArray( $editNewsArray, 1 );
				$thumbnail = $this->getPostFromArray( $editNewsArray, 2 );
				$content = $this->getPostFromArray( $editNewsArray, 3 );
				$this->doEditNews( $nid, $title_news, $thumbnail, $content );
			}
			else if( $this->isSetPostArray( $addNewsArray ) == 0 )
			{
				$title_news = $this->getPostFromArray( $addNewsArray, 0 );
				$thumbnail = $this->getPostFromArray( $addNewsArray, 1 );
				$content = $this->getPostFromArray( $addNewsArray, 2 );
				$this->doAddNews( $title_news, $thumbnail, $content );
			}
			else if( $this->isSetPostArray( $deleteNewsArray ) == 0 )
			{
				$nid = $this->getPostFromArray( $deleteNewsArray, 0 );
				$this->doDeleteNews( $nid );
			}
			else if( $this->isSetPostArray( $addSlideArray ) == 0 )
			{
				$image = $this->getPostFromArray( $addSlideArray, 0 );
				$this->doAddSlide( $image );
			}
			else if( $this->isSetPostArray( $deleteSliderArray ) == 0 )
			{
				$sid = $this->getPostFromArray( $deleteSliderArray, 0 );
				$this->doDeleteSlider( $sid );
			}
		}
   	}
?>