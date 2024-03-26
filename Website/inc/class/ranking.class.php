<?php
	class Ranking extends Connection
	{
		function __construct( $dbinfo )
		{
			parent::__construct( $dbinfo );
		}
		public function topRankingData( $dbconntrickster, $type )
		{
			switch( $type )
			{
				case 0:
				{
					$rankPos = 1;
					$rankQuery = "SELECT TOP 5 a.name, a.type, b.level FROM char_attr a INNER JOIN char_status b ON a.uid = b.uid ORDER BY b.level DESC";
					$rank_result = $dbconntrickster->select( 2, $rankQuery );
					foreach( $rank_result as $rank )
					{
						echo
						"
							<div class='ranking-unit'>
								<div class='inside'>
								<div class='pos'>".$rankPos."</div>
								<div class='nick'>".$rank->name."</div>
								<div class='stat'>LV ".$rank->level."</div>
								<div class='char'><img src='/images/char/".$rank->type.".gif' alt='' /></div>
								</div>
							</div>
						";
						$rankPos++;
					}
				}
				break;
				case 1:
				{
					$rankPos = 1;
					$rankQuery = "SELECT TOP 5 guild_name, guild_point FROM tbl_guild ORDER BY guild_point DESC, guild_members ASC";
					$rank_result = $dbconntrickster->select( 2, $rankQuery );
					foreach( $rank_result as $rank )
					{
						echo
						"
							<div class='ranking-unit'>
								<div class='inside'>
								<div class='pos'>".$rankPos."</div>
								<div class='nick'>".$rank->guild_name."</div>
								<div class='point'>LV ".$rank->guild_point."</div>
								</div>
							</div>
						";
						$rankPos++;
					}
				}
				break;
			}
		}
		public function rankingData( $dbconntrickster )
		{
			$rankType = $this->currentRanking();
			switch( $rankType )
			{
				case 0:
				{
					echo
					"	<thead>
							<tr>
								<th>Rank</th>
								<th>Nickname</th>
								<th>Class</th>
								<th>Level</th>
								<th>Exp</th>
							</tr>
						</thead>
						<tbody>
					";
					$rankPos = 1;
					$rankQuery = "";
					$currentChar = $this->currentRankingDetail();
					if( $currentChar == 0 )
					{
						$rankQuery = "SELECT TOP 50 a.name, a.type, b.exp, c.level FROM char_attr a INNER JOIN char_state b ON a.uid = b.uid INNER JOIN char_status c ON b.uid = c.uid ORDER BY c.level DESC, b.exp DESC";
					}
					else
					{
						$rankQuery = "SELECT TOP 50 a.name, a.type, b.exp, c.level FROM char_attr a INNER JOIN char_state b ON a.uid = b.uid INNER JOIN char_status c ON b.uid = c.uid WHERE a.type = ".$currentChar." ORDER BY c.level DESC, b.exp DESC";
					}
					$rank_result = $dbconntrickster->select( 2, $rankQuery );
					foreach( $rank_result as $rank )
					{
						echo
						"
							<tr>
								<td>".$rankPos."</td>
								<td>".$rank->name."</td>
								<td><img src='/images/char/".$rank->type.".gif' alt=''/></td>
								<td>".$rank->level."</td>
								<td>".$rank->exp."</td>
							</tr>
						";
						$rankPos++;
					}
					echo "</tbody>";
				}
				break;
				case 1:
				{
					echo
					"	<thead>
							<tr>
								<th>Rank</th>
								<th>Guild</th>
								<th>Members</th>
								<th>Points</th>
							</tr>
						</thead>
						<tbody>
					";
					$rankPos = 1;
					$rankQuery = "SELECT TOP 50 guild_name, guild_members, guild_point FROM tbl_guild ORDER BY guild_point DESC, guild_members ASC";
					$rank_result = $dbconntrickster->select( 2, $rankQuery );
					foreach( $rank_result as $rank )
					{
						echo
						"
							<tr>
								<td>".$rankPos."</td>
								<td>".$rank->guild_name."</td>
								<td>".$rank->guild_members."</td>
								<td>".$rank->guild_point."</td>
							</tr>
						";
						$rankPos++;
					}
					echo "</tbody>";
				}
				break;
				case 2:
				{
					echo
					"	<thead>
							<tr>
								<th>Rank</th>
								<th>Nickname</th>
								<th>Class</th>
								<th>Level</th>
								<th>Seals</th>
							</tr>
						</thead>
						<tbody>
					";
					$rankPos = 1;
					$currentBoss = $this->bossMonsterID();
					$rankQuery = "SELECT TOP 50 a.name, a.type, b.level, c.kill_count FROM char_attr a INNER JOIN char_status b ON a.uid = b.uid INNER JOIN tbl_bossmon c ON b.uid = c.char_uid WHERE c.mon_id = ".$currentBoss." ORDER BY c.kill_count DESC";
					$rank_result = $dbconntrickster->select( 2, $rankQuery );
					foreach( $rank_result as $rank )
					{
						echo
						"
							<tr>
								<td>".$rankPos."</td>
								<td>".$rank->name."</td>
								<td><img src='/images/char/".$rank->type.".gif' alt=''/></td>
								<td>".$rank->level."</td>
								<td>".$rank->kill_count."</td>
							</tr>
						";
						$rankPos++;
					}
					echo "</tbody>";
				}
				break;
			}
		}
		public function currentRanking()
		{
			$page = explode( "/", $_SERVER["REQUEST_URI"] );
			if( isset( $page[2] ) && !empty( $page[2] ) )
			{
				$rank_list = array( "level","guild","boss" );
				$rank_search = array_search( strtolower( $page[2] ), $rank_list );
				if( $rank_search !== FALSE )
				{
					return $rank_search;
				}
				else
				{
					return 0;
				}
			}
			return 0;
		}
		public function currentRankingDetail()
		{
			$page = explode( "/", $_SERVER["REQUEST_URI"] );
			if( isset( $page[3] ) && !empty( $page[3] ) )
			{
				$boss_list = array( "tutankhamen","tombeth","captain-skull","count-blood","tenter-lion","queen-odinea","soki","spicy-dragon","karan" );
				$char_list = array( "all","bunny","buffalo","sheep","dragon","fox","lion","cat","raccoon" );
				$boss_search = array_search( strtolower( $page[3] ), $boss_list );
				$char_search = array_search( strtolower( $page[3] ), $char_list );
				if( $boss_search !== FALSE )
				{
					return $boss_search;
				}
				elseif( $char_search !== FALSE )
				{
					return $char_search;
				}
				else
				{
					return 0;
				}
			}
			return 0;
		}
		public function bossMonsterID()
		{
			$currentBossMonster = $this->currentRankingDetail();
			switch($currentBossMonster)
			{
				case 0: return 2097; break;
				case 1: return 2072; break;
				case 2: return 2213; break;
				case 3: return 2138; break;
				case 4: return 2152; break;
				case 5: return 2123; break;
				case 6: return 2216; break;
				case 7: return 2029; break;
				case 8: return 2295; break;
			}
			return 2097;
		}
   	}
?>