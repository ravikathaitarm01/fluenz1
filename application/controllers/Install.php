<?php
namespace app\controllers
{
	use app\core\Controller;
	use app\models\simple\Campaign;
	use app\models\simple\User;
	use app\models\notify\Notify;
	use app\models\Package;
	use app\models\Statistics;

	class Install extends Controller
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			(new User(null))->install();
			(new Package())->install();
			(new Notify())->install();
			(new Statistics())->install();
			(new Campaign(null))->install();
			//(new User(null))->install();
			//(new Role())->install();
			/*
			(new Brand(null))->install();

			(new facebook\Post(null))->install();
			(new facebook\Conversation(null))->install();
			(new twitter\Mention(null))->install();
			(new twitter\Search(null))->install();
			(new twitter\Keyword())->install();
			(new Query(null))->install();
			(new NetworkItemInfo(null))->install();
			*/
		}
	}
}
