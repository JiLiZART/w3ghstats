<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

  public function actionBans($server=false,$bot=false)
  {
    $app=Yii::app();

//    $serverCriteria = $botCriteria = array();
//
//    $serverCriteria['select'] = array('s.name AS servername', 's.id AS serverid');
//    $serverCriteria['join'] = ' INNER JOIN servers AS s ON b.server = s.server';
//    if(!empty($server)) {
//      $serverCriteria['condition'] = 's.id = :server';
//      $serverCriteria['params'][':server'] = $server;
//    }
//
//    $botCriteria['select'] = array('bo.name AS boname', 'bo.id AS boid');
//    $botCriteria['join'] = 'INNER JOIN bots AS bo ON b.botid = bo.botid';
//    if(!empty($bot)) {
//      $botCriteria['condition'] = ' bo.id = :bot';
//      $botCriteria['params'][':bot'] = $bot;
//    }
//
//    $serverBotCriteria = array('condition' => 's.id = bo.serversid');//(!empty($bot) && !empty($server)) ? array('condition' => 's.id = bo.serversid') : array();
//    //var_dump(Bans::model()->findAll($criteria)); die();
//
//    $criteria = new CDbCriteria($serverCriteria);
//    $criteria->mergeWith($botCriteria);
//    $criteria->mergeWith($serverBotCriteria);
    
//    $dataProvider=new CActiveDataProvider('Bans', array(
//			'pagination'=>array(
//				'pageSize'=>$app->params['postsPerPage'],
//			),
//			'criteria'=>$criteria,
//		));
		$rawData=Bans::model()->byServer($server)->byBot($bot)->findAll();
    $dataProvider=new CArrayDataProvider($rawData,array(
      'id'=>'bans',
      'sort'=>array(
        'attributes'=>array(
            'name', 'reason', 'gamename', 'admin'
        ),
      ),
      'pagination'=>array(
        'pageSize'=>$app->params['postsPerPage'],
      ),
    ));
 
    //var_dump($dataProvider->getData()); die();
    $data = $servers = $bots = array();
    $data['pages'] = $dataProvider->getPagination();
    $rows = $dataProvider->getData();
    //if(count($rows)==0) throw new CHttpException(404,'The requested page does not exist.');
    //Forming data rows
    foreach($rows as $id => $row){
      $data['rows'][$row->server]['name'] = $servers[$row->serverid] = !empty($row->servername) ? $row->servername : $row->server;
      $data['rows'][$row->server]['id'] = $row->serverid;
      $data['rows'][$row->server]['bots'][$row->botid]['name'] = $bots[$row->serverid][$row->boid] = !empty($row->boname) ? $row->boname : $row->botid;
      $data['rows'][$row->server]['bots'][$row->botid]['id'] = $row->boid;
      $data['rows'][$row->server]['bots'][$row->botid]['bans'][] = $row;
    }

    $data['servers']=$servers;
    $data['bots']=$bots;

    // Set Breadcrumbs
    /**  @var $this->breadcrumbs CMap */
		if(isset($servers[$server]) && isset($bots[$server][$bot]))
		{
			$this->breadcrumbs->add('Bans', array('/bans'));
      $this->breadcrumbs->add($servers[$server], array('bans', 'server'=>$server));
      $this->breadcrumbs->add(null, $bots[$server][$bot]);
			//$this->breadcrumbs[$servers[$server]]=array('bans', 'server'=>$server);
			//array_push($this->breadcrumbs, $bots[$bot][$server]);
		}
		elseif(isset($servers[$server]))
		{
      $this->breadcrumbs->add('Bans', array('/bans'));
      $this->breadcrumbs->add(null, $servers[$server]);
			//$this->breadcrumbs['Bans']= array('/bans');
			//array_push($this->breadcrumbs, $servers[$server]);
		}
		else
		{
			//array_push($this->breadcrumbs, 'Bans');
      $this->breadcrumbs->add(null, 'Bans');
		}

		$this->pageTitle = 'Bans';

    $this->render('bans', $data);
  }

  public function actionPlayers()
  {
    $rawData=Players::model()->findAll();
    $dataProvider=new CArrayDataProvider($rawData, array(
    'id'=>'players',
    'sort'=>array(
        'attributes'=>array(
             'name', 'games', 'kills', 'deaths', 'assists'
        ),
    ),
    'pagination'=>array(
        'pageSize'=>10,
    ),
    ));
    $data['dataProvider']=$dataProvider;
    $this->render('players', $data);
//    var_dump(Players::model()->findAll(array('limit'=>10)));
  }


	public function actionPlayer()
	{
	 $this->render('player');
	}

}