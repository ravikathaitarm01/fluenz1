<?php

namespace sys\libraries\database\nosql
{

	use \MongoDB as BaseMongoDB;

	class MongoDB
	{
		/**
		 * @var \MongoClient|null
		 */
		protected $_connection = null;

		/**
		 * @var \MongoDB
		 */
		protected $_database = null;

		/**
		 * @var \MongoException
		 */
		protected $_last_error = null;

		/**
		 * Constructor - Sets Email Preferences
		 *
		 * The constructor can be passed an array of config values
		 *
		 * @param array $config = array()
		 * @return MongoDB
		 * @throws \Exception
		 */
		public function __construct($config)
		{
			if ( ! class_exists('Mongo'))
			{
				throw new \Exception('The MongoDB PECL extension has not been installed or enabled');
			}

			$this->connect($config);
		}

		/**
		 * Returns the current MongoClient version
		 *
		 * @return string
		 */
		public function get_driver_version()
		{
			return \MongoClient::VERSION;
		}

		/**
		 * Attempts to connect to the database. Builds the DSN if not already built.
		 *
		 * @param array
		 * @throws \Exception
		 * @throws \MongoConnectionException
		 * @return \MongoDB|null
		 */
		public function connect($config)
		{
			$this->_database = $config['database'];
			try
			{
				if (empty($config['dsn']))
				{
					$config['dsn'] = $this->_build_dsn($config);
				}

				$this->_connection = new \MongoClient($config['dsn'], array(
					'w' => $config['options']['write_concern'],
					//'wTimeout' => $config['options']['timeout']
					'socketTimeoutMS' => $config['options']['timeout'],
					'wTimeoutMS' => $config['options']['timeout']
				));

				$this->_database = $this->_connection->{$config['database']};
				return $this;
			}
			catch (\MongoConnectionException $e)
			{
				throw $e;
			}
		}

		/**
		 * Builds the DSN for the connection
		 *
		 * @param array
		 * @throws \Exception
		 * @return string
		 */
		protected function _build_dsn($config)
		{
			$dsn = 'mongodb://';

			if (empty($config['hostname']))
			{
				throw new \Exception('MongoDB hostname not specified');
			}

			if (empty($config['database']))
			{
				throw new \Exception('MongoDB database not specified');
			}

			if ( ! empty($config['username']) && ! empty($config['password']))
			{
				$dsn .= "{$config['username']}:{$config['password']}@";
			}

			if ( ! empty($config['port']))
			{
				$dsn .= "{$config['hostname']}:{$config['port']}";
			}
			else
			{
				$dsn .= "{$config['hostname']}";
			}

			return trim($dsn).'/'.$config['database'];
		}

		/**
		 * Returns the last error message
		 *
		 * @return string
		 */
		public function last_error_message()
		{
			return (is_object($this->_last_error))
				? $this->_last_error->getMessage()
				: '';
		}

		/**
		 * Returns the last error code
		 *
		 * @return int
		 */
		public function last_error_code()
		{
			return (is_object($this->_last_error))
				? $this->_last_error->getCode()
				: 0;
		}

		/**
		 * Select database
		 *
		 * @param string $database
		 * @return bool
		 */
		public function use_db($database)
		{
			if ( ! empty($database))
			{
				try
				{
					$this->_database = $this->_connection->{$database};
					return $this;
				}
				catch (\Exception $e)
				{
					$this->_last_error = $e;
				}
			}
			return null;
		}

		/**
		 * Drop database
		 *
		 * @param string $database
		 * @return bool|array
		 */
		public function drop_db($database)
		{
			if ( ! empty($database))
			{
				try
				{
					return $this->_database->drop();
				}
				catch (\Exception $e)
				{
					$this->_last_error = $e;
				}
			}
			return false;
		}

		/**
		 * @return \MongoDB
		 */
		public function db()
		{
			return $this->_database;
		}
	}
}
