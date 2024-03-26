<?php
    if( !isset( $_SESSION[ "TricksterWebLogin" ] ) )
    {
        echo '<script>window.location.replace("/");</script>';
    }
    else
    {
        if( $adminclass->checkHaveAccess( $userclass->getUID( $use_master_account, $dbconnacc ) ) != 0 )
        {
            echo '<script>window.location.replace("/userarea");</script>';
        }
    }
    $adminclass->doPostCheck();
?>
<div class="admin-container">
    <div class="title">
        <div class="name">
            NEWS MANAGEMENT
        </div>
        <div class="link">
			<button id="addNewsWindow" class="admin_category">ADD NEWS</button>
        </div>
    </div>
    <div class="separator"></div>
    <script type="text/javascript" src="/js/nicEdit.js"></script> 
    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor({maxHeight : 310, fullPanel : true}).panelInstance('newsedittextarea',{hasPanel : true});
        });
    </script>
    <div class="content">
        <div class="content-square">
            <div class="subnews-square">
                <div id="news-scroll">
                    <?php $adminclass->getNewsEditList(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="addNewsModal" class="modal-news">
	<div class="modal-news-content">
		<form id="addNewsForm" method="post">
			<span class="text-4">Title</span>
			<input type="text" name="newsaddtitle" required>
			<span class="text-4">Thumbnail URL</span>
			<input type="text" name="newsaddthumbnail" required>
			<span class="text-4">Content</span>
			<textarea id="newsedittextarea" name="newsaddcontent" ></textarea>
			<input type="submit" class="edit-button-modal-news edit-button" value="✔ ADD NEWS"/>
		</form>
		<button class="edit-button-modal-news delete-button closeaddnews">✖ CANCEL</button>
	</div>
</div>
<script>
	document.getElementById("addNewsWindow").addEventListener("click", () => { document.getElementById("addNewsModal").style.display = "flex"; });
	document.getElementsByClassName("closeaddnews")[0].addEventListener("click", () => { document.getElementById("addNewsModal").style.display = "none"; });
	window.addEventListener("click", (event) => { if (event.target === document.getElementById("addNewsModal")) { document.getElementById("addNewsModal").style.display = "none"; } });
    document.getElementById("addNewsForm").addEventListener("submit", (event) => { document.getElementById("addNewsModal").style.display = "none"; });
</script>
<div class="admin-container second-container">
    <div class="title">
        <div class="name">
        SLIDE MANAGEMENT
        </div>
        <div class="link">
			<button id="addSlideWindow" class="admin_category">ADD SLIDE</button>
        </div>
    </div>
    <div class="separator"></div>
    <div class="content">
        <div class="content-square">
            <div class="subnews-square">
                <div id="slider-scroll">
                    <?php $adminclass->getSliderEditList(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="separator-2"></div>
<div id="addSlideModal" class="modal-news">
	<div class="modal-news-content">
		<form id="addSlideForm" method="post">
			<span class="text-4">Slide Image URL</span>
			<input type="text" name="slideaddimage" required>
			<input type="submit" class="edit-button-modal-news edit-button" value="✔ ADD SLIDE"/>
		</form>
		<button class="edit-button-modal-news delete-button closeaddslide">✖ CANCEL</button>
	</div>
</div>
<script>
	document.getElementById("addSlideWindow").addEventListener("click", () => { document.getElementById("addSlideModal").style.display = "flex"; });
	document.getElementsByClassName("closeaddslide")[0].addEventListener("click", () => { document.getElementById("addSlideModal").style.display = "none"; });
	window.addEventListener("click", (event) => { if (event.target === document.getElementById("addSlideModal")) { document.getElementById("addSlideModal").style.display = "none"; } });
    document.getElementById("addSlideForm").addEventListener("submit", (event) => { document.getElementById("addSlideModal").style.display = "none"; });
</script>