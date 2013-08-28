<?php
/**
 * Class CopyFilesTask
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 28.08.13
 */
namespace Tasker\Copy;

use Tasker\ErrorException;
use Tasker\Setters\IRootPathSetter;
use Tasker\Utils\FileSystem;
use Tasker\Tasks\ITaskService;

class CopyFilesTask implements ITaskService, IRootPathSetter
{

	private $root;

	/**
	 * @param string $root
	 * @return $this
	 */
	public function setRootPath($root)
	{
		$this->root = (string) $root;
		return $this;
	}

	/**
	 * @param array $config
	 * @return array
	 * @throws \Tasker\ErrorException
	 */
	public function run(array $config)
	{
		$results = array();
		if(count($config)) {
			foreach ($config as $dest => $sources) {
				if(!is_string($dest)) {
					throw new ErrorException('Destination must be valid path');
				}

				if(is_string($sources)) {
					$sources = array($sources);
				}

				if(count($sources)) {
					foreach ($sources as $source) {
						FileSystem::cp($this->getFullPath($dest), $this->getFullPath($source));
						$results[] = 'Files from folder "' . $source . '" was copied to "' . $dest . '"';
					}
				}
			}
		}

		return $results;
	}

	/**
	 * @param $path
	 * @return string
	 */
	protected function getFullPath($path)
	{
		return $this->root . DIRECTORY_SEPARATOR . $path;
	}
}