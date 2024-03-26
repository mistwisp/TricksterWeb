<div class="ranking-container">
  <div class="title">
    <div class="name">
      TOP RANKING
    </div>
    <div class="link">
      <a href="/ranking/level/all">VIEW ALL</a>
    </div>
  </div>
  <div class="tabs">
    <div class="tab first active" onclick="changeTab(1)">LEVEL</div>
    <div class="tab last" onclick="changeTab(2)">GUILD</div>
  </div>
  <div class="content">
    <div id="tab1Content">
      <?php $rankclass->topRankingData( $dbconntrickster, 0 ); ?>
    </div>
    <div id="tab2Content">
      <?php $rankclass->topRankingData( $dbconntrickster, 1 ); ?>
    </div>
  </div>
</div>
<div class="carrousel">
  <div class="carrousel-slide">
    <?php
      $newsclass->sliderData();
    ?>
  </div>
  <div class="carrousel-buttons"></div>
</div>
<div class="separator"></div>
<div class="news-container">
  <div class="title">
    <div class="name">
      NEWS
    </div>
    <div class="link">
      <a href="/news">VIEW ALL</a>
    </div>
    <?php
      $newsclass->topNewsData( 0 );
    ?>
  </div>
</div>
<div class="links-container">
  <a href="<?php echo $discord_link; ?>" target="_blank"><img src="images/discord.png" alt="" /></a>
  <a href="/download"><img src="images/download.png" alt="" class="margin" /></a>
</div>
<div class="separator-footer"></div>
