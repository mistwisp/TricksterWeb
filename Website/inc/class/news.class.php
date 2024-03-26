<?php
	class News extends Connection
	{
		function __construct( $dbinfo )
		{
			parent::__construct( $dbinfo );
		}
		public function sliderData()
		{
			$count = 1;
			$sliderQuery = "SELECT TOP 9 Image FROM web_slider ORDER BY sid DESC";
			$slider_result = $this->select( 2, $sliderQuery );
			foreach( $slider_result as $slider )
			{
				echo '<img src="'.$slider->Image.'" alt="Image '.$count.'" />';
				$count++;
			}
		}
		public function topNewsData( $type )
		{
			$newsQuery = "";
			if( $type == 0 )
			{
				$newsQuery = "SELECT TOP 4 nid, Title, Content, Date, Author, Thumbnail FROM web_news ORDER BY nid DESC";
			}
			else
			{
				$newsQuery = "SELECT TOP 50 nid, Title, Content, Date, Author, Thumbnail FROM web_news ORDER BY nid DESC";
			}
			$news_result = $this->select( 2, $newsQuery );
			foreach( $news_result as $news )
			{
				$newsDate = date( "d/m/Y", strtotime( $news->Date ) );
				echo
				'
					<a href="/news/view/'.$news->nid.'">
						<div class="news-entry">
							<div class="news-image">
								<img src="'.$news->Thumbnail.'" alt="" />
							</div>
							<div class="news-title">
								<h2>'.$news->Title.'</h2>
								<div class="news-author">
								['.$newsDate.'] ~ '.$news->Author.'
								</div>
							</div>
						</div>
					</a>
				';
			}
		}
		public function newsRecentList()
		{
			echo
			'
				<div class="title">
					<div class="name">
						LATEST NEWS
					</div>
				</div>
				<div class="separator"></div>
				<div class="content">
			';
			$this->topNewsData( 1 );
			echo
			'
				</div>
			';
		}
		public function newsData()
		{
			$newsID = $this->currentNews();
			if( $newsID < 0 )
			{
				$this->newsRecentList();
			}
			else
			{
				$news = $this->select( 0, "web_news", array( "Title", "Content", "Date", "Author", "Thumbnail" ), array( "nid" => $newsID ) );
				if( empty( $news ) )
				{
					$this->newsRecentList();
				}
				else
				{
					$newsDate = date( "d/m/Y", strtotime( $news->Date ) );
					echo
					'
						<div class="content">
							<div class="news-entry-full">
								<div class="news-image">
									<img src="'.$news->Thumbnail.'" alt="" />
								</div>
								<div class="news-title">
									<h2>'.$news->Title.'</h2>
									<div class="news-author">
									['.$newsDate.'] ~ '.$news->Author.'
									</div>
								</div>
							</div>
							<div class="separator"></div>
							<div class="news-content">
								'.$news->Content.'
							</div>
						</div>
					';
				}
			}
		}
		public function currentNews()
		{
			$page = explode( "/", $_SERVER["REQUEST_URI"] );
			if( isset( $page[2] ) && !empty( $page[2] ) )
			{
				if( $page[2] == "view" )
				{
					if( isset( $page[3] ) && !empty( $page[3] ) )
					{
						return $page[3];
					}
					else
					{
						return -1;
					}
				}
				else
				{
					return -1;
				}
			}
			return -1;
		}
   	}
?>