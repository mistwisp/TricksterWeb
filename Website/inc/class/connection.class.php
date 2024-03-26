<?php
    class Connection 
    {
        private $pdo;
        public function __construct( $info ) 
        {
            $dsn = "sqlsrv:Server=" . $info['dbserv'] . ";Database=" . $info['dbname'];
            try 
            {
                $this->pdo = new PDO( $dsn, $info['dbuser'], $info['dbpass'] );
                $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            } 
            catch ( PDOException $e ) 
            {
                die( "Connection failed: " . $e->getMessage() );
            }
        }
        public function select( $type, $table, $columns = array(), $where = array(), $check_type = 0, $method = 0 )
        {
            try 
            {
                if( $type < 2 )
                {
                    $pdo_method = PDO::FETCH_OBJ;
                    if( $method != 0 )
                    {
                        $pdo_method = PDO::FETCH_ASSOC;
                    }
                    $columns_str = !empty( $columns ) ? implode( ",", $columns ) : "*";
                    $where_str = "";
                    $params = array();
                    if ( !empty( $where ) ) 
                    {
                        $where_str = " WHERE ";
                        foreach ( $where as $key => $value ) 
                        {
                            if( $check_type == 1 )
                            {
                                $where_str .= "$key >= :$key AND ";						
                            }
                            else
                            {
                                $where_str .= "$key = :$key AND ";						
                            }
                            $params[ ":$key" ] = $value;
                        }
                        $where_str = rtrim( $where_str, " AND " );
                    }
                    $sql = "SELECT $columns_str FROM $table $where_str";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute( $params );
                    $result;
                    if( $type == 0 )
                    { 
                        $result = $stmt->fetch( $pdo_method );
                    }
                    else
                    {
                        $result = $stmt->fetchAll( $pdo_method );
                    }
                }
                else
                {
                    if( $type == 2 )
                    {
                        $stmt = $this->pdo->prepare( $table );
                        $stmt->execute();
                        $result = $stmt->fetchAll( PDO::FETCH_OBJ );
                    }
                    else if( $type == 3 )
                    {
                        $stmt = $this->pdo->query( $table );
                        $result = $stmt->fetchColumn( PDO::FETCH_OBJ );
                    }
                    else if( $type == 4 )
                    {
                        $stmt = $this->pdo->query( $table );
                        $result = $stmt->fetchColumn();
                    }
                    else if( $type == 5 )
                    {
                        $stmt = $this->pdo->prepare( $table );
                        $stmt->execute();
                        return 0;
                    }
                    else if( $type == 6 )
                    {
                        $stmt = $this->pdo->prepare( $table );
                        $stmt->execute();
                        $result = $stmt->fetch( PDO::FETCH_OBJ );
                    }
                    else
                    {
                        return array();
                    }
                }
                return $result;
            } 
            catch ( PDOException $e ) 
            {
                return array();
            }
        }
        public function insert( $type, $table, $data = array() ) 
        {
            try 
            {
                if( $type == 0 )
                {
                    $keys = implode( ",", array_keys( $data ) );
                    $values = implode( ",", array_map( function ( $v ) 
                    {
                        return ":$v";
                    }, array_keys( $data ) ) );
                    $sql = "INSERT INTO $table ( $keys ) VALUES ( $values )";
                    $stmt = $this->pdo->prepare( $sql );
                    $stmt->execute( $data );
                }
                else
                {
                    $stmt = $this->pdo->prepare( $table );
                    $stmt->execute();
                }
                return 0;
            } 
            catch ( PDOException $e ) 
            {
                return -1;
            }
        }
        public function update( $table, $data, $where = array(), $type = 0 ) 
        {
            try 
            {
                $set_str = "";
                $params = array();
                foreach ( $data as $key => $value ) 
                {
					if( $type == 1 )
					{
						$set_str .= "$key = $key + :$key, ";						
					}
					else
					{
						$set_str .= "$key = :$key, ";						
					}

                    $params[ ":$key" ] = $value;
                }
                $set_str = rtrim( $set_str, ", " );
                $where_str = "";
                if ( !empty( $where ) ) 
                {
                    $where_str = " WHERE ";
                    foreach ( $where as $key => $value ) 
                    {
                        $where_str .= "$key = :where_$key AND ";
                        $params[ ":where_$key" ] = $value;
                    }
                    $where_str = rtrim( $where_str, " AND " );
                }
                $sql = "UPDATE $table SET $set_str $where_str";
                $stmt = $this->pdo->prepare( $sql );
                $stmt->execute( $params );
            } 
            catch ( PDOException $e ) 
            {
                return -1;
            }
            return 0;
        }
		function genPubID() 
		{
			$characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$pubID = '';
			for ($i = 0; $i < 12; $i++) {
				$pubID .= $characters[mt_rand(0, strlen($characters) - 1)];
			}
			return $pubID;
		}
		public function isSetPostArray( $postArray )
		{
			foreach ( $postArray as $item ) 
			{
				if ( !isset( $_POST[ $item ] ) || empty( $_POST[ $item ] ) ) 
				{
					return -1;
				}
			}
			return 0;
		}
		public function getPostFromArray( $postArray, $arrayid )
		{
			if( $arrayid < 0 || $arrayid > sizeof( $postArray ) )
			{
				return "ERROR";
			}
			return isset( $_POST[ $postArray[ $arrayid ] ] ) ? $_POST[ $postArray[ $arrayid ] ] : "ERROR";
		}
		public function verifyRecaptcha( $secretKey, $response ) 
		{
            $remoteIP = $_SERVER[ 'REMOTE_ADDR' ];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret'   => $secretKey,
                'response' => $response,
                'remoteip' => $remoteIP
            ];
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query( $data )
                ]
            ];
            $context = stream_context_create( $options );
            $result = file_get_contents( $url, false, $context );
            $responseData = json_decode( $result, true );
            return $responseData['success'];
		}
    }
?>