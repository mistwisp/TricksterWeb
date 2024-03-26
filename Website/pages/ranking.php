<?php 
	$currentRank = $rankclass->currentRanking( $dbconntrickster );
	$currentChar = $rankclass->currentRankingDetail( $dbconntrickster ) 
?>
<div class="rank-container">
    <div class="title">
        <div class="name">
        	RANKING
        </div>
        <div class="link">
			<a href="/ranking/level/all"><button class="rank_category <?php if( $currentRank == 0 ) { echo "rank_active"; } ?>">LEVEL</button></a>
			<a href="/ranking/guild"><button class="rank_category <?php if( $currentRank == 1 ) { echo "rank_active"; } ?>">GUILD</button></a>
			<a href="/ranking/boss/tutankhamen"><button class="rank_category <?php  if( $currentRank == 2 ) { echo "rank_active"; } ?>">BOSS</button></a>
        </div>
    </div>
    <div class="separator<?php if( $currentRank == 2 ){ echo "-3"; } ?>"></div>
	<?php
		if( $currentRank == 0 )
		{
			echo
			'	
				<div class="level-extra">
					<div class="inside">
						<a href="/ranking/level/all"><img src="/images/char/0.gif" alt="" '.(($currentChar == 0)?'class="active"':"").'/></a>
						<a href="/ranking/level/bunny"><img src="/images/char/1.gif" alt="" '.(($currentChar == 1)?'class="active"':"").'/></a>
						<a href="/ranking/level/buffalo"><img src="/images/char/2.gif" alt="" '.(($currentChar == 2)?'class="active"':"").'/></a>
						<a href="/ranking/level/sheep"><img src="/images/char/3.gif" alt="" '.(($currentChar == 3)?'class="active"':"").'/></a>
						<a href="/ranking/level/dragon"><img src="/images/char/4.gif" alt="" '.(($currentChar == 4)?'class="active"':"").'/></a>
						<a href="/ranking/level/fox"><img src="/images/char/5.gif" alt="" '.(($currentChar == 5)?'class="active"':"").'/></a>
						<a href="/ranking/level/lion"><img src="/images/char/6.gif" alt="" '.(($currentChar == 6)?'class="active"':"").'/></a>
						<a href="/ranking/level/cat"><img src="/images/char/7.gif" alt="" '.(($currentChar == 7)?'class="active"':"").'/></a>
						<a href="/ranking/level/raccoon"><img src="/images/char/8.gif" alt="" '.(($currentChar == 8)?'class="active"':"").'/></a>
					</div>
				</div>
				<div class="separator"></div>
			';
		}
		elseif( $currentRank == 2 )
		{
			echo
			'	
				<div class="boss-extra">
					<div class="inside">
						<a href="/ranking/boss/tutankhamen"><img src="/images/boss/tut.gif" alt="" '.(($currentChar == 0)?'class="active"':"").'/></a>
						<a href="/ranking/boss/tombeth"><img src="/images/boss/tombeth.gif" alt="" '.(($currentChar == 1)?'class="active"':"").'/></a>
						<a href="/ranking/boss/captain-skull"><img src="/images/boss/skull.gif" alt="" '.(($currentChar == 2)?'class="active"':"").'/></a>
						<a href="/ranking/boss/count-blood"><img src="/images/boss/count.gif" alt="" '.(($currentChar == 3)?'class="active"':"").'/></a>
						<a href="/ranking/boss/tenter-lion"><img src="/images/boss/tenter.gif" alt="" '.(($currentChar == 4)?'class="active"':"").'/></a>
						<a href="/ranking/boss/queen-odinea"><img src="/images/boss/odinea.gif" alt="" '.(($currentChar == 5)?'class="active"':"").'/></a>
						<a href="/ranking/boss/soki"><img src="/images/boss/soki.gif" alt="" '.(($currentChar == 6)?'class="active"':"").'/></a>
						<a href="/ranking/boss/spicy-dragon"><img src="/images/boss/spicy.gif" alt="" '.(($currentChar == 7)?'class="active"':"").'/></a>
						<a href="/ranking/boss/karan"><img src="/images/boss/karan.gif" alt="" '.(($currentChar == 8)?'class="active"':"").'/></a>
					</div>
				</div>
				<div class="separator-3"></div>
			';
		}
	?>
    <table>
		<?php $rankclass->rankingData( $dbconntrickster ); ?>
	</table>
</div>
<div class="separator-2"></div>
